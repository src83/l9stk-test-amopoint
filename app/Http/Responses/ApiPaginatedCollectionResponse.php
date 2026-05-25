<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Enums\Api\MessageKeyEnum;
use App\Support\Api\Pagination\ApiPaginator;
use Illuminate\Pagination\LengthAwarePaginator;

final class ApiPaginatedCollectionResponse
{
    public static function fromPaginator(
        LengthAwarePaginator $paginator,
        int $httpCode = 200,
        string|MessageKeyEnum|null $messageKey = null,
        ?string $guiMessage = null,
    ): ApiResponse {
        return ApiSuccessResponse::make(
            data: $paginator->items(),
            paginator: ApiPaginator::from($paginator),
            httpCode: $httpCode,
            messageKey: $messageKey,
            guiMessage: $guiMessage,
        );
    }
}
