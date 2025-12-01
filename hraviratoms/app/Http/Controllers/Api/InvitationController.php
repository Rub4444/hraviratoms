<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    protected function ensureCanAccessInvitation(Invitation $invitation): void
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        // Суперадмин видит всё
        if ($user->is_superadmin) {
            return;
        }

        // Обычный пользователь видит только свои приглашения
        if ($invitation->user_id !== $user->id) {
            abort(403);
        }
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->is_superadmin) {
            return Invitation::with(['template', 'user'])
                ->orderByDesc('created_at')
                ->get();
        }

        return Invitation::with(['template', 'user'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
    }


    public function show(Invitation $invitation)
    {
        $this->ensureCanAccessInvitation($invitation);

        if (method_exists($invitation, 'trashed') && $invitation->trashed()) {
            abort(404);
        }

        $invitation->load('template', 'guests', 'user'); // или rsvps

        return $invitation;
    }


    public function store(Request $request)
    {
        $user = auth()->user();

        // Обычный юзер НЕ создаёт приглашения
        if (!$user || !$user->is_superadmin) {
            abort(403, 'Только супер-админ может создавать приглашения.');
        }

        $data = $request->validate([
            'invitation_template_id' => ['required', 'exists:invitation_templates,id'],
            'title'         => ['nullable', 'string', 'max:255'],
            'bride_name'    => ['required', 'string', 'max:255'],
            'groom_name'    => ['required', 'string', 'max:255'],
            'date'          => ['nullable', 'date'],
            'time'          => ['nullable', 'string', 'max:50'],
            'venue_name'    => ['nullable', 'string', 'max:255'],
            'venue_address' => ['nullable', 'string', 'max:255'],
            'dress_code'    => ['nullable', 'string', 'max:255'],
            'data'          => ['nullable', 'array'],
            'is_published'  => ['boolean'],

            // ⬇ добавляем владельца, которого можно выбрать в форме
            'user_id'       => ['nullable', 'exists:users,id'],
        ]);

        $data['slug'] = Str::slug(
            ($data['bride_name'] ?? '') . '-' .
            ($data['groom_name'] ?? '') . '-' .
            ($data['date'] ?? now()->format('Y-m-d'))
        ) . '-' . Str::random(5);

        $invitation = Invitation::create($data);

        return response()->json($invitation->fresh('template'), 201);
    }

    public function publicStoreRequest(Request $request)
    {
        // гость или залогиненный пользователь — неважно
        // никакой superadmin проверки!

        $data = $request->validate([
            'invitation_template_id' => ['required', 'exists:invitation_templates,id'],
            'bride_name'    => ['required', 'string', 'max:255'],
            'groom_name'    => ['required', 'string', 'max:255'],
            'date'          => ['nullable', 'date'],
            'time'          => ['nullable', 'string', 'max:50'],
            'venue_name'    => ['nullable', 'string', 'max:255'],
            'venue_address' => ['nullable', 'string', 'max:255'],
            'dress_code'    => ['nullable', 'string', 'max:255'],
            'data'          => ['nullable', 'array'],

            // поля клиента
            'customer_name'   => ['required', 'string', 'max:255'],
            'customer_email'  => ['nullable', 'email'],
            'customer_phone'  => ['nullable', 'string', 'max:50'],
            'customer_comment'=> ['nullable', 'string'],
        ]);

        // Генерируем slug точно так же, как в store()
        $data['slug'] = Str::slug(
            ($data['bride_name'] ?? '') . '-' .
            ($data['groom_name'] ?? '') . '-' .
            ($data['date'] ?? now()->format('Y-m-d'))
        ) . '-' . Str::random(5);

        // ОБЯЗАТЕЛЬНО: статус pending
        $data['status'] = Invitation::STATUS_PENDING;

        // создаём приглашение
        $invitation = Invitation::create($data);

        // отправка письма админу
        // if ($adminEmail = config('mail.from.address')) {
        //     Mail::raw(
        //         "НОВАЯ ЗАЯВКА #{$invitation->id}\n".
        //         "Клиент: {$invitation->customer_name}\n".
        //         "Email: {$invitation->customer_email}\n".
        //         "Телефон: {$invitation->customer_phone}\n",
        //         fn($msg) => $msg->to($adminEmail)->subject("LoveLeaf: новая заявка")
        //     );
        // }

        return response()->json([
            'ok' => true,
            'message' => 'Ձեր հարցումը հաջողությամբ ուղարկվեց։'
        ], 201);
    }


    public function update(Request $request, Invitation $invitation)
    {
        $user = auth()->user();

        // изменять может только суперадмин
        if (!$user || !$user->is_superadmin) {
            abort(403);
        }

        $data = $request->validate([
            'title'         => ['nullable', 'string', 'max:255'],
            'bride_name'    => ['required', 'string', 'max:255'],
            'groom_name'    => ['required', 'string', 'max:255'],
            'date'          => ['nullable', 'date'],
            'time'          => ['nullable', 'string', 'max:50'],
            'venue_name'    => ['nullable', 'string', 'max:255'],
            'venue_address' => ['nullable', 'string', 'max:255'],
            'dress_code'    => ['nullable', 'string', 'max:255'],
            'data'          => ['nullable', 'array'],
            'is_published'  => ['boolean'],
            'user_id'       => ['nullable', 'exists:users,id'], // ⬅ добавили
        ]);

        $invitation->update($data);

        return $invitation->fresh('template');
    }


    public function destroy(Invitation $invitation)
    {
        $user = auth()->user();

        // ❗ Решаем жёстко: удалять может только суперадмин
        if (!$user || !$user->is_superadmin) {
            abort(403);
        }

        $invitation->delete();

        return response()->noContent();
    }
}
