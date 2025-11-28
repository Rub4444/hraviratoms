<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
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
