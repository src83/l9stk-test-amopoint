<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Support\Api\Pagination\ApiPaginator;
use Symfony\Component\HttpFoundation\Response;

final class ApiSuccessResponse extends ApiResponse
{
    /**
     * @use ApiSuccessResponse::make();
     * @use ApiSuccessResponse::make($data);
     * @use ApiSuccessResponse::make($data, null, Response::HTTP_CREATED);
     * @use ApiSuccessResponse::make($data, null, Response::HTTP_CREATED, 'Created successfully');
     *
     * @param mixed|null $data
     * @param ApiPaginator|null $paginator
     * @param int $httpCode
     * @param string|null $guiMessage
     * @return ApiResponse
     */
    public static function make(
        mixed $data = null,
        ?ApiPaginator $paginator = null,
        int $httpCode = Response::HTTP_OK,
        ?string $guiMessage = null,
    ): ApiResponse {

        return parent::success(
            data: $data,
            paginator: $paginator,
            httpCode: $httpCode,
            guiMessage: $guiMessage,
        );
    }
}
