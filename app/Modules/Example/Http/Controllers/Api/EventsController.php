<?php

declare(strict_types=1);

namespace App\Modules\Example\Http\Controllers\Api;

use App\Exceptions\ItemNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiSuccessResponse;
use App\Modules\Example\Http\Requests\Api\EventRequest;
use App\Modules\Example\Http\Resources\Api\EventResource;
use App\Modules\Example\Repositories\EarthquakeRepository;
use App\Support\Api\Pagination\ApiPaginator;
use Illuminate\Http\JsonResponse;

class EventsController extends Controller
{
    public function __construct(
        private readonly EarthquakeRepository $repository,
    ) {}

    public function index(EventRequest $request): JsonResponse
    {
        $page = $request->integer('page', 1);
        $perPage = 15;

        $paginator = $this->repository->getListPaginated($page, $perPage);
        if($paginator->isEmpty()) {
            return ApiSuccessResponse::make([]);
        }

        return ApiSuccessResponse::make(
            data: EventResource::collection($paginator),
            paginator: ApiPaginator::from($paginator)
        );
    }

    public function show(EventRequest $request, int $id): JsonResponse
    {
        throw new ItemNotFoundException("Event with ID {$id} not found");
    }
}
