<?php

namespace App\Http\Controllers\Api;

use App\DTO\GuestDto;
use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Invitation;
use App\Repositories\GuestRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GuestController extends Controller
{
    public function __construct(
        private readonly GuestRepositoryInterface $guests
    ) {
    }

    /**
     * Проверка доступа к приглашению:
     * - супер-админ видит всё
     * - обычный юзер видит только свои приглашения
     */
    protected function ensureCanAccessInvitation(Invitation $invitation): void
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        if ($user->is_superadmin) {
            return;
        }

        if ($invitation->user_id !== $user->id) {
            abort(403);
        }
    }

    /**
     * Список гостей по приглашению.
     *
     * GET /api/invitations/{invitation}/guests
     */
    public function index(Invitation $invitation): JsonResponse
    {
        $this->ensureCanAccessInvitation($invitation);

        $items = $this->guests->forInvitation($invitation);

        return response()->json(
            $items
                ->map(fn (Guest $guest) => GuestDto::fromModel($guest)->toArray())
                ->values()
                ->all()
        );
    }

    /**
     * Создать гостя для приглашения.
     *
     * POST /api/invitations/{invitation}/guests
     */
    public function store(Request $request, Invitation $invitation): JsonResponse
    {
        $this->ensureCanAccessInvitation($invitation);

        $data = $request->validate([
            'full_name'   => ['required', 'string', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:50'],
            'rsvp_status' => ['nullable', 'string', 'max:50'], // можно потом ограничить yes/no/maybe
            'comment'     => ['nullable', 'string'],
        ]);

        $guest = $this->guests->createForInvitation($invitation, $data);

        return response()->json(
            GuestDto::fromModel($guest)->toArray(),
            201
        );
    }

    /**
     * Обновить гостя.
     *
     * PUT /api/guests/{guest}
     */
    public function update(Request $request, Guest $guest): JsonResponse
    {
        $invitation = $guest->invitation;
        $this->ensureCanAccessInvitation($invitation);

        $data = $request->validate([
            'full_name'   => ['required', 'string', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:50'],
            'rsvp_status' => ['nullable', 'string', 'max:50'],
            'comment'     => ['nullable', 'string'],
        ]);

        $guest = $this->guests->update($guest, $data);

        return response()->json(
            GuestDto::fromModel($guest)->toArray()
        );
    }

    /**
     * Удалить гостя.
     *
     * DELETE /api/guests/{guest}
     */
    public function destroy(Guest $guest): Response
    {
        $invitation = $guest->invitation;
        $this->ensureCanAccessInvitation($invitation);

        $this->guests->delete($guest);

        return response()->noContent();
    }
}
