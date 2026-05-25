<?php

declare(strict_types=1);

namespace App\Modules\Example\Http\Controllers\Api;

#use App\Enums\Api\MessageKeyEnum;
#use App\Examples\Http\Resources\Api\EventResource;
#use App\Examples\Support\Api\DTO\TestDto;
#use App\Examples\Http\Resources\Api\TestResource;
#use App\Exceptions\DomainLayerException;
use App\Exceptions\ItemNotFoundException;
use App\Http\Controllers\Controller;
#use App\Http\Requests\Api\Test\TestRequest;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiPaginatedCollectionResponse;
use App\Http\Responses\ApiResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\User;
use App\Modules\Example\DTO\EventDto;
use App\Modules\Example\Http\Requests\Api\EventRequest;
use App\Modules\Example\Http\Resources\Api\EventResource;
#use App\Support\Api\Logging\BusinessLogger;
use App\Support\Api\Pagination\ApiPaginator;
use App\Support\Api\Pagination\ArrayPaginator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\LockedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

/**
 * Example controller demonstrating ApiResponse, pagination and DTO usage.
 * Not part of real API.
 *
 * Тестовый стенд для Debug-Mode API с примерами:
 * - Разных исключений
 * - Валидации структуры
 * - Кейсов пользовательских ошибок
 * - Покрытия бизнес-валидации и доменного слоя
 */
class EventsController extends Controller
{
    private array $data;

    public function __construct()
    {
        $this->data = [
            ['id' => 1, 'name' => 'Test Model 01'],
            ['id' => 2, 'name' => 'Test Model 02'],
            ['id' => 3, 'name' => 'Test Model 03'],
        ];
    }

    /**
     * Пример / спецификация по ответам в методах контроллеров
     * Демонстрирует различные способы формирования ответов API
     * в зависимости от типа входных данных и стратегии пагинации.
     *
     * Input: array | Collection | DTO | Resource
     * Output: list response (paginated or not)
     *
     * @var array $items Исходные данные (например: $this->data)
     *
     *
     * @example Вывод массива без пагинации
     * return ApiSuccessResponse::make($items);
     *
     * @example Вывод коллекции DTO без пагинации
     * $data = collect($items)->map(fn ($item) => new TestDto($item['id'], $item['name']));
     * return ApiSuccessResponse::make($data);
     *
     * @example Вывод ресурса без пагинации (если данные уже представлены объектами (DTO / Model) - коллекцию можно не создавать)
     * $data = collect($items)->map(fn ($item) => new TestDto($item['id'], $item['name']));
     * return ApiSuccessResponse::make(
     *     TestResource::collection($data)
     * );
     *
     *
     * @example Вывод массива с пагинацией
     * $paginator = ArrayPaginator::paginate($items, 2, request('page', 1));
     * return ApiSuccessResponse::make(data: $paginator->items(), paginator: ApiPaginator::from($paginator));
     *
     * @example Вывод коллекции с пагинацией
     * $data = collect($items)->map(fn ($item) => new TestDto($item['id'], $item['name']));
     * $paginator = ArrayPaginator::paginate($data, 2, request('page', 1));
     * return ApiSuccessResponse::make(data: $paginator->items(), paginator: ApiPaginator::from($paginator));
     *
     * @example Вывод ресурса с пагинацией
     * $data = collect($items)->map(fn ($item) => new TestDto($item['id'], $item['name']));
     * $paginator = ArrayPaginator::paginate($data, 2, request('page', 1));
     * return ApiSuccessResponse::make(data: TestResource::collection($paginator), paginator: ApiPaginator::from($paginator));
     *
     * @example Вывод ресурса с пагинацией (через ещё одну коллекцию)
     * $data = collect($items)->map(fn ($item) => new TestDto($item['id'], $item['name']));
     * $paginator = ArrayPaginator::paginate($data, 2, request('page', 1));
     * $paginator->setCollection(TestResource::collection($paginator->items())->collection);
     * return ApiSuccessResponse::make(data: $paginator->items(), paginator: ApiPaginator::from($paginator));
     *
     * @example Вывод ресурса с пагинацией (через фабричный хелпер)
     * $data = collect($items)->map(fn ($item) => new TestDto($item['id'], $item['name']));
     * $paginator = ArrayPaginator::paginate($data, 2, request('page', 1));
     * return ApiPaginatedCollectionResponse::fromPaginator($paginator);
     *
     * @return JsonResponse
     */
    public function index(EventRequest $request): JsonResponse
    {
        $items = $this->data;

        $page = $request->integer('page');
        $perPage = 2;

        $data = collect($items)->map(
            fn ($item) => new EventDto($item['id'], $item['location'], $item['magnitude'])
        );

        if (!$page) {
            return ApiSuccessResponse::make(
                EventResource::collection($data)
            );
        }

        $paginator = ArrayPaginator::paginate($data, $perPage, $page);

        return ApiSuccessResponse::make(
            data: EventResource::collection($paginator),
            paginator: ApiPaginator::from($paginator)
        );
    }

    /**
     * Получить элемент по ID
     * @param EventRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(EventRequest $request, int $id): JsonResponse
    {
        $item = collect($this->data)->firstWhere('id', $id);

        if (!$item) {
            throw new ItemNotFoundException("Event model with ID {$id} not found");
        }

        return ApiSuccessResponse::make($item);
    }

}
