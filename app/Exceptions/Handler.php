<?php

namespace App\Exceptions;

use App\Exceptions\DTO\ApiErrorDTO;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
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
            return response()->json($errorData->toArray(), $errorData->httpCode);
        }

        return parent::render($request, $e);
    }


    /**
     * Единая обработка исключений для API / логика обработки и категоризация ошибок
     */
    protected function handleApiException(Request $request, Throwable $e): ApiErrorDTO
    {
        $isDebug  = config('app.debug') === true;

        // 404: NotFound — Не найдено
        if ($e instanceof NotFoundHttpException || $e instanceof NotFoundExceptionInterface) {
            $statusCode = HttpResponse::HTTP_NOT_FOUND;
            $statusText = Response::$statusTexts[$statusCode] ?? 'Not Found';
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
