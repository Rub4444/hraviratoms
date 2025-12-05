<?php

namespace App\Http\Controllers\Api;

use App\DTO\UserDto;
use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $users
    ) {
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
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

    public function index(): JsonResponse
    {
        $this->ensureSuperadmin();

        $collection = $this->users->allWithInvitationsCount();

        return response()->json(
            $collection
                ->map(fn (User $user) => UserDto::fromModel($user)->toArray())
                ->values()
                ->all()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $this->ensureSuperadmin();

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'      => ['required', 'string', 'min:6'],
            'is_superadmin' => ['boolean'],
        ]);

        $user = $this->users->create($data);

        return response()->json(
            UserDto::fromModel($user)->toArray(),
            201
        );
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $this->ensureSuperadmin();

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password'      => ['nullable', 'string', 'min:6'],
            'is_superadmin' => ['boolean'],
        ]);

        $user = $this->users->update($user, $data);

        return response()->json(
            UserDto::fromModel($user)->toArray()
        );
    }

    public function createFromInvitation(Invitation $invitation): JsonResponse
    {
        $this->ensureSuperadmin();

        if ($invitation->user_id && $invitation->user) {
            return response()->json([
                'ok'         => false,
                'message'    => 'У этого приглашения уже есть привязанный пользователь.',
                'user'       => UserDto::fromModel($invitation->user)->toArray(),
                'invitation' => $invitation->load('user'),
            ], 400);
        }

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

        $name = $clientName
            ?: trim(($invitation->bride_name ?? '') . ' & ' . ($invitation->groom_name ?? ''))
            ?: $email;

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = $this->users->create([
                'name'          => $name,
                'email'         => $email,
                'password'      => $phone,
                'is_superadmin' => false,
            ]);
        }

        $invitation->user_id = $user->id;
        $invitation->save();

        return response()->json([
            'ok'         => true,
            'user'       => UserDto::fromModel($user)->toArray(),
            'invitation' => $invitation->fresh('user'),
        ]);
    }

    public function destroy(Request $request, User $user): Response
    {
        $this->ensureSuperadmin();

        if (auth()->id() === $user->id) {
            abort(400, 'Нельзя удалить свой собственный аккаунт.');
        }

        $deleteInvitations = $request->boolean('delete_invitations');

        $this->users->delete($user, $deleteInvitations);

        return response()->noContent();
    }
}
