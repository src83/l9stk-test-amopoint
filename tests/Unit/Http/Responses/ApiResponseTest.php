<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Responses;

use App\Http\Responses\ApiResponse;
use InvalidArgumentException;
use Tests\TestCase;

final class ApiResponseTest extends TestCase
{
    /** @test */
    public function it_returns_success_response_with_default_structure(): void
    {
        $response = ApiResponse::success();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame([
            'success'   => true,
            'http_code' => 200,
            'http_text' => 'OK',
            'message'   => null,
            'meta'      => null,
            'data'      => null,
        ], $response->getData(true));
    }

    /** @test */
    public function it_returns_success_response_with_data(): void
    {
        $response = ApiResponse::success(['id' => 1]);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame([
            'success'   => true,
            'http_code' => 200,
            'http_text' => 'OK',
            'message'   => null,
            'meta'      => null,
            'data'      => ['id' => 1],
        ], $response->getData(true));
    }

    /** @test */
    public function it_returns_success_response_with_gui_message(): void
    {
        $response = ApiResponse::success(
            data: ['id' => 1],
            httpCode: 201,
            guiMessage: 'Record created',
        );

        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame([
            'success'   => true,
            'http_code' => 201,
            'http_text' => 'Created',
            'message'   => ['gui' => 'Record created'],
            'meta'      => null,
            'data'      => ['id' => 1],
        ], $response->getData(true));
    }

    /** @test */
    public function it_returns_error_response_with_default_structure(): void
    {
        $response = ApiResponse::error(404);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame([
            'success'   => false,
            'http_code' => 404,
            'http_text' => 'Not Found',
            'message'   => null,
            'details'   => null,
        ], $response->getData(true));
    }

    /** @test */
    public function it_returns_error_response_with_sys_message(): void
    {
        $response = ApiResponse::error(404, 'Event not found');

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame([
            'success'   => false,
            'http_code' => 404,
            'http_text' => 'Not Found',
            'message'   => ['sys' => 'Event not found'],
            'details'   => null,
        ], $response->getData(true));
    }

    /** @test */
    public function it_returns_error_response_with_details(): void
    {
        $response = ApiResponse::error(
            httpCode: 422,
            sysMessage: 'Validation failed',
            details: ['fields' => ['email' => ['Required']]],
        );

        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame([
            'success'   => false,
            'http_code' => 422,
            'http_text' => 'Unprocessable Content',
            'message'   => ['sys' => 'Validation failed'],
            'details'   => ['fields' => ['email' => ['Required']]],
        ], $response->getData(true));
    }

    /** @test */
    public function it_throws_exception_for_unknown_http_code(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown HTTP code');

        ApiResponse::success(null, null, 999);
    }
}
