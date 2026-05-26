<?php

namespace App\Exceptions;

use App\Exceptions\DTO\ApiErrorDTO;
use App\Http\Responses\ApiErrorResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\LockedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     * Don't put: RuntimeException::class, LogicException::class
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
        UnprocessableEntityHttpException::class,
        BadRequestException::class,
        UnauthorizedException::class,
        MethodNotAllowedException::class,
        InvalidArgumentException::class,
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @return JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     *
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function render($request, Exception|Throwable $e)
    {
        // WEB only: стандартная HTML-страница ошибки
        // 419: CSRF - Page Expired
        if ($e instanceof TokenMismatchException) {
            return redirect()
                ->route('showLoginForm')
                ->with('session_expired', true)
                ->withInput($request->except('_token'));
        }

        // API only: Обрабатываем все исключения в едином JSON-формате
        if ($request->expectsJson()) {
            $errorData = $this->handleApiException($request, $e);
            return ApiErrorResponse::make(...$errorData->toArray());
        }

        return parent::render($request, $e);
    }


    /**
     * Единая обработка исключений для API / логика обработки и категоризация ошибок
     */
    protected function handleApiException(Request $request, Throwable $e): ApiErrorDTO
    {
        $isDebug = config('app.debug') === true;

        // 400: BadRequest - Некорректный запрос
        if ($e instanceof BadRequestException || $e instanceof BadRequestHttpException) {
            $statusCode = HttpResponse::HTTP_BAD_REQUEST;
            $statusText = Response::$statusTexts[$statusCode] ?? 'Bad Request';
            $sysMessage = $e->getMessage() ?: $statusText;
            return new ApiErrorDTO(httpCode: $statusCode, sysMessage: $sysMessage);
        }

        // 401: Authentication - Неаутентифицирован
        if ($e instanceof AuthenticationException) {
            $statusCode = HttpResponse::HTTP_UNAUTHORIZED;
            $statusText = Response::$statusTexts[$statusCode] ?? 'Unauthorized';
            $sysMessage = $e->getMessage() ?: $statusText;
            return new ApiErrorDTO(httpCode: $statusCode, sysMessage: $sysMessage);
        }

        // 403: Authorization - Доступ запрещён
        if ($e instanceof AuthorizationException ||
            $e instanceof AccessDeniedException || $e instanceof AccessDeniedHttpException ||
            $e instanceof UnauthorizedException || $e instanceof UnauthorizedHttpException) {
            $statusCode = HttpResponse::HTTP_FORBIDDEN;
            $statusText = Response::$statusTexts[$statusCode] ?? 'Forbidden';
            $sysMessage = $e->getMessage() ?: $statusText;
            return new ApiErrorDTO(httpCode: $statusCode, sysMessage: $sysMessage);
        }

        // 404: ItemNotFound - Запись не найдена
        if ($e instanceof ItemNotFoundException) {
            $statusCode = HttpResponse::HTTP_NOT_FOUND;
            $statusText = Response::$statusTexts[$statusCode] ?? 'Not Found';
            $sysMessage = $e->getMessage() ?: $statusText;
            return new ApiErrorDTO(httpCode: $statusCode, sysMessage: $sysMessage);
        }

        // 404: NotFound - Не найдено
        if ($e instanceof NotFoundHttpException || $e instanceof NotFoundExceptionInterface) {
            $statusCode = HttpResponse::HTTP_NOT_FOUND;
            $statusText = Response::$statusTexts[$statusCode] ?? 'Not Found';
            $sysMessage = $e->getMessage() ?: $statusText;
            return new ApiErrorDTO(httpCode: $statusCode, sysMessage: $sysMessage);
        }

        // 405: MethodNotAllowed - Метод не поддерживается
        if ($e instanceof MethodNotAllowedException || $e instanceof MethodNotAllowedHttpException) {
            $statusCode = HttpResponse::HTTP_METHOD_NOT_ALLOWED;
            $statusText = Response::$statusTexts[$statusCode] ?? 'Method Not Allowed';
            $sysMessage = $e->getMessage() ?: $statusText;
            $details = ($e instanceof MethodNotAllowedException)
                ? ['allowed_methods' => implode(', ', $e->getAllowedMethods())]
                : ['allowed_methods' => $e->getHeaders()['Allow'] ?? null];
            return new ApiErrorDTO(httpCode: $statusCode, sysMessage: $sysMessage, details: $details);
        }

        // 409: Conflict - Конфликт
        if ($e instanceof ConflictHttpException) {
            $statusCode = HttpResponse::HTTP_CONFLICT;
            $statusText = Response::$statusTexts[$statusCode] ?? 'Conflict';
            $sysMessage = $e->getMessage() ?: $statusText;
            return new ApiErrorDTO(httpCode: $statusCode, sysMessage: $sysMessage);
        }

        // 413: Request Entity Too Large - Превышен допустимый размер запроса
        if ($e instanceof PostTooLargeException) {
            $statusCode = HttpResponse::HTTP_REQUEST_ENTITY_TOO_LARGE;
            $statusText = Response::$statusTexts[$statusCode] ?? 'Content Too Large';
            $sysMessage = $e->getMessage() ?: $statusText;
            return new ApiErrorDTO(httpCode: $statusCode, sysMessage: $sysMessage);
        }

        // 422: Unprocessable content - Ошибка валидации
        if ($e instanceof ValidationException) {
            $statusCode = HttpResponse::HTTP_UNPROCESSABLE_ENTITY;
            $statusText = Response::$statusTexts[$statusCode] ?? 'Unprocessable Entity';
            $sysMessage = $e->getMessage() ?: $statusText;
            $details = ['fields' => $e->errors()];
            return new ApiErrorDTO(httpCode: $statusCode, sysMessage: $sysMessage, details: $details);
        }

        // 423: Locked - Ресурс заблокирован
        if ($e instanceof LockedHttpException) {
            $statusCode = HttpResponse::HTTP_LOCKED;
            $statusText = Response::$statusTexts[$statusCode] ?? 'Locked';
            $sysMessage = $e->getMessage() ?: $statusText;
            return new ApiErrorDTO(httpCode: $statusCode, sysMessage: $sysMessage);
        }

        // 4XX: Остальные HTTP-исключения
        if ($e instanceof HttpExceptionInterface) {
            $statusCode = $e->getStatusCode();
            $statusText = Response::$statusTexts[$statusCode] ?? 'HTTP Error';
            $sysMessage = $e->getMessage() ?: $statusText;
            return new ApiErrorDTO(httpCode: $statusCode, sysMessage: $sysMessage);
        }

        // Default (5XX)
        $statusCode = HttpResponse::HTTP_INTERNAL_SERVER_ERROR;
        $statusText = Response::$statusTexts[$statusCode] ?? 'Internal Server Error';
        $sysMessage = $e->getMessage() ?: $statusText;
        $details = $isDebug ? [
            'request' => [
                'time' => now()->toIso8601String(),
                'method' => $request->method(),
                'uri' => $request->path(),
                'params' => $request->except(['password', 'password_confirmation', 'token', 'secret', 'api_key']),
            ],
            'exception' => [
                'file' => $e->getFile(),
                'type' => get_class($e),
                'line' => $e->getLine(),
                'code' => $e->getCode(),
            ],
        ] : null;

        return new ApiErrorDTO(
            httpCode: $statusCode,
            sysMessage: $sysMessage,
            details: $details,
        );
    }
}
