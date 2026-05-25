<?php

namespace App\Http\Responses;

use App\Enums\Api\MessageKeyEnum;
use App\Support\Api\Resolvers\MessageResolver;

final class ApiErrorResponse extends ApiResponse
{
    /**
     * @use ApiErrorResponse::make(404, MessageKeyEnum::NOT_FOUND);
     * @use ApiErrorResponse::make(Response::HTTP_UNAUTHORIZED, MessageKeyEnum::UNAUTHORIZED);
     * @use ApiErrorResponse::make(
     *      httpCode: Response::HTTP_UNAUTHORIZED,
     *      messageKey: MessageKeyEnum::UNAUTHORIZED,
     *      sysMessage: 'Bad password'
     * );
     *
     * @param int $httpCode
     * @param string|MessageKeyEnum|null $messageKey
     * @param string|null $guiMessage
     * @param string|null $sysMessage
     * @param mixed|null $details
     * @return ApiResponse
     */
    public static function make(
        int $httpCode,
        string|MessageKeyEnum|null $messageKey = null,
        ?string $guiMessage = null,
        ?string $sysMessage = null,
        mixed $details = null
    ): ApiResponse {

        if ($messageKey !== null) {
            $resolved = MessageResolver::resolve($messageKey, $guiMessage);
            $messageKey = $resolved->messageKey;
            $guiMessage = $resolved->guiMessage;
        }

        return parent::error(
            httpCode: $httpCode,
            messageKey: $messageKey,
            guiMessage: $guiMessage,
            sysMessage: $sysMessage,
            details: $details,
        );
    }
}
