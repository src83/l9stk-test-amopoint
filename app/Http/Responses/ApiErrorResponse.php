<?php

declare(strict_types=1);

namespace App\Http\Responses;

final class ApiErrorResponse extends ApiResponse
{
    /**
     * @use ApiErrorResponse::make(404);
     * @use ApiErrorResponse::make(404, 'Item not found');
     * @use ApiErrorResponse::make(422, 'Validation failed', ['fields' => $errors]);
     *
     * @param int $httpCode
     * @param string|null $sysMessage
     * @param mixed|null $details
     * @return ApiResponse
     */
    public static function make(
        int $httpCode,
        ?string $sysMessage = null,
        mixed $details = null
    ): ApiResponse {

        return parent::error(
            httpCode: $httpCode,
            sysMessage: $sysMessage,
            details: $details,
        );
    }
}
