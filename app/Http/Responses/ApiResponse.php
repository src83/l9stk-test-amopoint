<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Support\Api\Pagination\ApiPaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use InvalidArgumentException;

/**
 * ApiResponse — абстрактная база, содержит логику форматирования, валидации кода, общие поля.
 * Наследники — ApiSuccessResponse и ApiErrorResponse реализуют конкретные фабричные методы.
 */
class ApiResponse extends JsonResponse
{
    /**
     * Унифицированный JSON-ответ об успехе в едином формате API.
     * Пример вызова:
     *     return ApiResponse::success($user);
     *     return ApiResponse::success($user, null, 201, 'auth_registration.user_created', 'Пользователь создан');
     *
     * Пример успешного ответа:
     * {
     *    "success": true,
     *    "http_code": 2XX,
     *    "http_text": "Success code description",
     *    "message": {...},          object | null
     *    "data": {...},     array | object | null
     * }
     *
     * @param mixed|null $data Основные данные ответа
     * @param ApiPaginator|null $paginator Метаданные пагинации
     * @param int $httpCode HTTP-код (по умолчанию 200)
     * @param string|null $guiMessage Локализованное сообщение для вывода в GUI
     * @return self
     */
    public static function success(
        mixed $data = null,
        ?ApiPaginator $paginator = null,
        int $httpCode = 200,
        ?string $guiMessage = null,
    ): self
    {
        $httpText = self::validateHttpCode($httpCode, __METHOD__);

        $response = [
            'success' => true,
            'http_code' => $httpCode,
            'http_text' => $httpText,
            'message' => null,
            'meta' => null,
            'data' => $data,
        ];

        if ($guiMessage !== null) {
            $response['message'] = [
                'gui' => $guiMessage,
            ];
        }

        if ($paginator !== null) {
            $response['meta']['paginator'] = $paginator->toArray();
        }

        return new self($response, $httpCode);
    }

    /**
     * Унифицированный JSON-ответ об ошибке в едином формате API.
     * Пример вызова:
     *     return ApiResponse::error(404);
     *     return ApiResponse::error(404, 'auth_login.user_not_found', 'Пользователь не найден');
     *     см. также в Handler@render
     *
     *  Пример ответа с ошибкой:
     *  {
     *     "success": false,
     *     "http_code": 4XX | 5XX,
     *     "http_text": "Error code description",
     *     "message": {...},  object | null
     *     "details": {...},  object | null
     *  }
     *
     * @param int $httpCode HTTP-код
     * @param string|null $sysMessage Кастомное сообщение из аргумента исключения (если не указано - возвращает null)
     * @param mixed|null $details Дополнительные данные (например, ошибки валидации)
     * @return self
     */
    public static function error(
        int $httpCode,
        ?string $sysMessage = null,
        mixed $details = null,
    ): self
    {
        $httpText = self::validateHttpCode($httpCode, __METHOD__);

        $response = [
            'success' => false,
            'http_code' => $httpCode,
            'http_text' => $httpText,
            'message' => null,
            'details' => $details,
        ];

        if ($sysMessage !== null) {
            $response['message'] = [
                'sys' => $sysMessage,
            ];
        }

        return new self($response, $httpCode);
    }

    /**
     * Проверяет корректность HTTP-кода.
     *
     * @param int $httpCode
     * @param string $method
     * @return string
     */
    private static function validateHttpCode(int $httpCode, string $method): string
    {
        $method = substr($method, strrpos($method, '\\') + 1).'()';

        // Исключения перехватываются Handler-секцией "Default (5XX)"
        if (!isset(Response::$statusTexts[$httpCode])) {
            throw new InvalidArgumentException("Unknown HTTP code: {$httpCode} in {$method}");
        }

        return Response::$statusTexts[$httpCode];
    }
}
