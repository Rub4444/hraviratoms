<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        return response()->json([
            'id'            => $user->id,
            'name'          => $user->name,
            'email'         => $user->email,
            'is_superadmin' => $user->isSuperAdmin(),
        ]);
    }

    protected function ensureSuperadmin(): void
    {
        $user = auth()->user();

        if (!$user || !$user->is_superadmin) {
            abort(403);
        }
    }

    public function index()
    {
        $this->ensureSuperadmin();

        // Только не удалённые пользователи
        return User::withCount('invitations')
            ->orderByDesc('is_superadmin')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
                'is_superadmin',
                'deleted_at',
            ]);
    }

    public function store(Request $request)
    {
        $this->ensureSuperadmin();

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'      => ['required', 'string', 'min:6'],
            'is_superadmin' => ['boolean'],
        ]);

        $user = User::create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'password'      => Hash::make($data['password']),
            'is_superadmin' => $data['is_superadmin'] ?? false,
        ]);

        return response()->json($user, 201);
    }

    public function update(Request $request, User $user)
    {
        $this->ensureSuperadmin();

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password'      => ['nullable', 'string', 'min:6'],
            'is_superadmin' => ['boolean'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->is_superadmin = $data['is_superadmin'] ?? false;

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return $user;
    }

    public function createFromInvitation(Invitation $invitation)
    {
        $this->ensureSuperadmin();

        // Если уже есть пользователь — просто вернём его
        if ($invitation->user_id && $invitation->user) {
            return response()->json([
                'ok'         => false,
                'message'    => 'У этого приглашения уже есть привязанный пользователь.',
                'user'       => $invitation->user,
                'invitation' => $invitation->load('user'),
            ], 400);
        }

        // Берём данные клиента из JSON-поля "data"
        $payload = $invitation->data ?? [];

        $email = $payload['client_email'] ?? null;
        $phone = $payload['client_phone'] ?? null;
        $clientName = $payload['client_name'] ?? null;

        if (!$email || !$phone) {
            return response()->json([
                'ok'      => false,
                'message' => 'В данных заявки (data) нет email или телефона клиента.',
            ], 422);
        }

        // Имя: сначала client_name, если нет — на основе имён пары, если нет — email
        $name = $clientName
            ?: trim(($invitation->bride_name ?? '') . ' & ' . ($invitation->groom_name ?? ''))
            ?: $email;

        // Если пользователь с таким email уже есть — просто используем его
        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name'     => $name,
                'email'    => $email,
                // пароль = телефон из формы заявки
                'password' => Hash::make($phone),
            ]);
        }

        // Привязываем приглашение к пользователю
        $invitation->user_id = $user->id;
        $invitation->save();

        return response()->json([
            'ok'         => true,
            'user'       => $user,
            'invitation' => $invitation->fresh('user'),
        ]);
    }


    public function destroy(Request $request, User $user)
    {
        $this->ensureSuperadmin();

        // Нельзя удалить самого себя (по желанию)
        if (auth()->id() === $user->id) {
            abort(400, 'Нельзя удалить свой собственный аккаунт.');
        }

        $deleteInvitations = $request->boolean('delete_invitations');

        if ($deleteInvitations) {
            // мягко удаляем все приглашения юзера
            Invitation::where('user_id', $user->id)->delete();
        } else {
            // просто отвязываем приглашения от юзера
            Invitation::where('user_id', $user->id)->update(['user_id' => null]);
        }

        // мягко удаляем пользователя
        $user->delete();

        return response()->noContent();
    }
}
