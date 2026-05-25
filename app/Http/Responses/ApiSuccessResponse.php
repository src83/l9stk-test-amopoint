<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Enums\Api\MessageKeyEnum;
use App\Support\Api\Pagination\ApiPaginator;
use App\Support\Api\Resolvers\MessageResolver;
use Symfony\Component\HttpFoundation\Response;

final class ApiSuccessResponse extends ApiResponse
{
    /**
     * @use ApiSuccessResponse::make();
     * @use ApiSuccessResponse::make($data);
     * @use ApiSuccessResponse::make($data, null, Response::HTTP_CREATED);
     * @use ApiSuccessResponse::make($data, null, Response::HTTP_CREATED, MessageKeyEnum::CREATED);
     * @use ApiSuccessResponse::make($data, null, Response::HTTP_CREATED, MessageKeyEnum::CREATED, 'Created successfully');
     * @use ApiSuccessResponse::make($data, null, Response::HTTP_CREATED, null, 'Created successfully');
     * @use ApiSuccessResponse::make(null, null, Response::HTTP_OK, MessageKeyEnum::UPDATED);
     * @use ApiSuccessResponse::make(null, null, Response::HTTP_OK, MessageKeyEnum::UPDATED, 'Model updated successfully');
     * @use ApiSuccessResponse::make(null, null, Response::HTTP_OK, null, 'Model updated successfully');
     *
     * @param mixed|null $data
     * @param ApiPaginator|null $paginator
     * @param int $httpCode
     * @param string|MessageKeyEnum|null $messageKey
     * @param string|null $guiMessage
     * @return ApiResponse
     */
    public static function make(
        mixed $data = null,
        ?ApiPaginator $paginator = null,
        int $httpCode = Response::HTTP_OK,
        string|MessageKeyEnum|null $messageKey = null,
        ?string $guiMessage = null,
    ): ApiResponse {

        if ($messageKey !== null) {
            $resolved = MessageResolver::resolve($messageKey, $guiMessage);
            $messageKey = $resolved->messageKey;
            $guiMessage = $resolved->guiMessage;
        }

        return parent::success(
            data: $data,
            paginator: $paginator,
            httpCode: $httpCode,
            messageKey: $messageKey,
            guiMessage: $guiMessage,
        );
    }
}
