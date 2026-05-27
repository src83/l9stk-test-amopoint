<?php

declare(strict_types=1);

namespace App\Modules\Example\Http\Controllers\Api;

use App\Exceptions\ItemNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Modules\Example\Http\Requests\Api\EventRequest;
use App\Modules\Example\Http\Resources\Api\EventResource;
use App\Modules\Example\Repositories\EarthquakeRepository;
use App\Support\Api\Pagination\ApiPaginator;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class EventsController extends Controller
{
    public function __construct(
        private readonly EarthquakeRepository $repository,
    ) {}

    public function index(EventRequest $request): JsonResponse
    {
        $page = $request->integer('page', 1);
        $perPage = (int) config('api.items_per_page');

        $paginator = $this->repository->getListPaginated($page, $perPage);
        if($paginator->isEmpty()) {
            return ApiSuccessResponse::make([]);
        }

        return ApiSuccessResponse::make(
            data: EventResource::collection($paginator),
            paginator: ApiPaginator::from($paginator),
            guiMessage: 'Опциональное сопроводительное сообщение (актуально для POST-запросов)',
        );
    }

    public function show(EventRequest $request, int $id): JsonResponse
    {
        if ($id === 13) {
            return ApiErrorResponse::make(
                Response::HTTP_CONFLICT,
                'Запись заблокирована бизнес-логикой',
            );
        }

        $item = $this->repository->findById($id);

        if (!$item) {
            throw new ItemNotFoundException("Event with ID {$id} not found");
        }

        return ApiSuccessResponse::make(new EventResource($item));
    }
}
