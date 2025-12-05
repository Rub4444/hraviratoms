<?php

namespace App\Http\Controllers\Api;

use App\DTO\InvitationTemplateDto;
use App\Http\Controllers\Controller;
use App\Repositories\InvitationTemplateRepositoryInterface;
use Illuminate\Http\JsonResponse;

class InvitationTemplateController extends Controller
{
    public function __construct(
        private readonly InvitationTemplateRepositoryInterface $templates
    ) {
    }

    public function index(): JsonResponse
    {
        $collection = $this->templates->allActive();

        return response()->json(
            $collection
                ->map(fn ($tpl) => InvitationTemplateDto::fromModel($tpl)->toArray())
                ->values()
                ->all()
        );
    }

    public function show(string $key): JsonResponse
    {
        $template = $this->templates->findActiveByKey($key);

        return response()->json(
            InvitationTemplateDto::fromModel($template)->toArray()
        );
    }
}
