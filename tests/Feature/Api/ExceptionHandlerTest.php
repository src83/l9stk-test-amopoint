<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Exceptions\ItemNotFoundException;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Tests\TestCase;

class ExceptionHandlerTest extends TestCase
{
    // 400: BadRequest
    public function test_bad_request_exception_returns_400_json(): void
    {
        Route::get('/api/__test/bad_request', static fn () => throw new BadRequestException('Bad input'));

        $this->getJson('/api/__test/bad_request')
            ->assertStatus(400)
            ->assertJson([
                'success'   => false,
                'http_code' => 400,
                'http_text' => 'Bad Request',
            ])
            ->assertJsonPath('message.sys', 'Bad input')
            ->assertJsonPath('details', null);
    }

    // 404: ItemNotFoundException
    public function test_item_not_found_exception_returns_404_json(): void
    {
        Route::get('/api/__test/item_not_found', static fn () => throw new ItemNotFoundException('Event not found'));

        $this->getJson('/api/__test/item_not_found')
            ->assertNotFound()
            ->assertJson([
                'success'   => false,
                'http_code' => 404,
            ])
            ->assertJsonPath('message.sys', 'Event not found');
    }

    // 404: несуществующий роут (NotFoundHttpException)
    public function test_unknown_route_returns_404_json(): void
    {
        $this->getJson('/api/__test/nonexistent_route')
            ->assertNotFound()
            ->assertJson([
                'success'   => false,
                'http_code' => 404,
                'http_text' => 'Not Found',
            ]);
    }

    // 405: MethodNotAllowed - JSON без Accept-заголовка (регрессия)
    public function test_method_not_allowed_returns_405_as_json_without_accept_header(): void
    {
        Route::get('/api/__test/get_only', static fn () => response()->json('ok'));

        // Намеренно без getJson - проверяем фикс: до него возвращался HTML
        $this->post('/api/__test/get_only')
            ->assertStatus(405)
            ->assertJsonPath('success', false)
            ->assertJsonPath('http_code', 405);
    }

    // 422: ValidationException с details.fields
    public function test_validation_exception_returns_422_with_fields(): void
    {
        Route::get('/api/__test/validation', static fn () => throw ValidationException::withMessages([
            'email' => ['The email field is required.'],
        ]));

        $this->getJson('/api/__test/validation')
            ->assertUnprocessable()
            ->assertJson([
                'success'   => false,
                'http_code' => 422,
            ])
            ->assertJsonPath('details.fields.email.0', 'The email field is required.');
    }

    // 500: Default (5XX)
    public function test_internal_server_error_returns_500_json(): void
    {
        Route::get('/api/__test/server_error', static fn () => throw new \RuntimeException('Unexpected error'));

        $this->getJson('/api/__test/server_error')
            ->assertStatus(500)
            ->assertJson([
                'success'   => false,
                'http_code' => 500,
                'http_text' => 'Internal Server Error',
            ])
            ->assertJsonPath('message.sys', 'Unexpected error');
    }
}
