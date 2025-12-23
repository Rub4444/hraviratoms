<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\DTO\InvitationDto;
use App\Enums\InvitationStatus;
use App\Repositories\InvitationRepositoryInterface;
use Illuminate\Http\JsonResponse;
use App\Models\InvitationTemplate;
use App\Services\InvitationPriceCalculator;

class InvitationController extends Controller
{
    public function __construct(
        private readonly InvitationRepositoryInterface $invitations
    ) {
    }

    /**
     * Список приглашений для текущего пользователя.
     * - Суперадмин видит все
     * - Обычный пользователь — только свои
     *
     * Возвращаем DTO + meta (удобно для пагинации на фронте).
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        /** @var \Illuminate\Pagination\LengthAwarePaginator<\App\Models\Invitation> $paginator */
        $paginator = $this->invitations->forUser($user, perPage: 50);


        return response()->json([
            'data' => $paginator->getCollection()
                ->map(fn (Invitation $invitation) => InvitationDto::fromModel($invitation)->toArray())
                ->values()
                ->all(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ]);

    }

    /**
     * Показ конкретного приглашения, на которое у пользователя есть доступ.
     */
    public function show(int $id): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        $invitation = $this->invitations->findAccessible($user, $id);

        if (!$invitation || (method_exists($invitation, 'trashed') && $invitation->trashed())) {
            abort(404);
        }

        $invitation->load('template', 'guests', 'user');

        return response()->json(
            InvitationDto::fromModel($invitation)->toArray()
        );
    }

    /**
     * Создание приглашения — только для суперадмина.
     */
    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();
        if (!$user || !$user->is_superadmin) {
            abort(403);
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
            'user_id'       => ['nullable', 'exists:users,id'],

            // 🔥 ТОЛЬКО override-данные
            'data'          => ['nullable', 'array'],
        ]);

        /**
         * ❗ ВАЖНО
         * data = ТОЛЬКО то, что клиент изменил
         * template.config НЕ копируем
         */
        $data['data'] = $data['data'] ?? [];

        $template = InvitationTemplate::findOrFail(
            $data['invitation_template_id']
        );

        $features = $data['data']['features'] ?? [];

        $data['price'] = InvitationPriceCalculator::calculate(
            $template,
            $features
        );

        $invitation = $this->invitations->createForSuperAdmin($user, $data);

        $invitation->load('template', 'user');

        return response()->json(
            InvitationDto::fromModel($invitation)->toArray(),
            201
        );
    }



    /**
     * Публичный запрос с лендинга на создание приглашения.
     * Оставляем без репозитория.
     */
    public function publicStoreRequest(Request $request): JsonResponse
    {
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

            'customer_name'   => ['required', 'string', 'max:255'],
            'customer_email'  => ['nullable', 'email'],
            'customer_phone'  => ['nullable', 'string', 'max:50'],
            'customer_comment'=> ['nullable', 'string'],
        ]);

        $data['slug'] = Str::slug(
            ($data['bride_name'] ?? '') . '-' .
            ($data['groom_name'] ?? '') . '-' .
            ($data['date'] ?? now()->format('Y-m-d'))
        ) . '-' . Str::random(5);

        $data['status']       = InvitationStatus::Pending; // enum → каст в модели сохранит 'pending'

        Invitation::create($data);

        return response()->json([
            'ok'      => true,
            'message' => 'Ձեր հարցումը հաջողությամբ ուղարկվեց։',
        ], 201);
    }

    /**
     * Обновление приглашения — только суперадмин.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = auth()->user();
        if (!$user || !$user->is_superadmin) {
            abort(403);
        }

        $invitation = Invitation::with('template')->findOrFail($id);

        $data = $request->validate([
            'invitation_template_id' => ['nullable', 'exists:invitation_templates,id'],
            'title'         => ['nullable', 'string', 'max:255'],
            'bride_name'    => ['required', 'string', 'max:255'],
            'groom_name'    => ['required', 'string', 'max:255'],
            'date'          => ['nullable', 'date'],
            'time'          => ['nullable', 'string', 'max:50'],
            'venue_name'    => ['nullable', 'string', 'max:255'],
            'venue_address' => ['nullable', 'string', 'max:255'],
            'dress_code'    => ['nullable', 'string', 'max:255'],
            'user_id'       => ['nullable', 'exists:users,id'],
            'status'        => ['nullable', 'in:pending,published,rejected'],
            'data'          => ['nullable', 'array'],
        ]);

        if (isset($data['data'])) {
            $data['data'] = array_replace_recursive(
                $invitation->data ?? [],
                $data['data']
            );
        }

        if (
            isset($data['invitation_template_id']) ||
            isset($data['data']['features'])
        ) {
            $template = isset($data['invitation_template_id'])
                ? InvitationTemplate::findOrFail($data['invitation_template_id'])
                : $invitation->template;

            $features = $data['data']['features']
                ?? ($invitation->data['features'] ?? []);

            $data['price'] = InvitationPriceCalculator::calculate($template, $features);
        }

        if (array_key_exists('data', $data)) {
            $invitation->data = $data['data'];
            unset($data['data']);
        }

        $invitation->fill($data);
        $invitation->save();
        $invitation->refresh();

        $invitation->load('template', 'user');

        return response()->json(
            InvitationDto::fromModel($invitation)->toArray()
        );
    }



    /**
     * Удаление приглашения — только суперадмин.
     */
    public function destroy(int $id): JsonResponse
    {
        $user = auth()->user();

        if (!$user || !$user->is_superadmin) {
            abort(403);
        }

        $invitation = Invitation::findOrFail($id);

        $this->invitations->delete($invitation);

        return response()->json(null, 204);
    }
}
