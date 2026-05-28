<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Modules\Example\Models\EarthquakeEvent;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EventsControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        EarthquakeEvent::query()->delete();
    }

    private function createEvent(array $overrides = []): EarthquakeEvent
    {
        return EarthquakeEvent::create(array_merge([
            'event_id'     => uniqid('TEST-', more_entropy: true),
            'event_moment' => now(),
        ], $overrides));
    }

    // --- GET /api/events ---

    public function test_index_returns_empty_response_when_no_events(): void
    {
        $this->getJson('/api/events')
            ->assertOk()
            ->assertJson([
                'success'   => true,
                'http_code' => 200,
                'data'      => [],
                'meta'      => null,
            ]);
    }

    public function test_index_returns_paginated_list_when_events_exist(): void
    {
        $this->createEvent(['location' => 'Istanbul']);

        $this->getJson('/api/events')
            ->assertOk()
            ->assertJsonStructure([
                'success', 'http_code', 'http_text',
                'meta' => [
                    'paginator' => ['page', 'per_page', 'total_item', 'total_page', 'last_item', 'has_next_page'],
                ],
                'data' => [
                    '*' => ['id', 'location', 'magnitude'],
                ],
            ])
            ->assertJsonPath('meta.paginator.total_item', 1)
            ->assertJsonPath('data.0.location', 'Istanbul');
    }

    public function test_index_rejects_non_integer_page_param(): void
    {
        $this->getJson('/api/events?page=abc')
            ->assertUnprocessable()
            ->assertJson([
                'success'   => false,
                'http_code' => 422,
            ])
            ->assertJsonStructure(['details' => ['fields' => ['page']]]);
    }

    // --- GET /api/events/{id} ---

    public function test_show_returns_event_by_id(): void
    {
        $event = $this->createEvent(['location' => 'Ankara']);

        $this->getJson("/api/events/{$event->id}")
            ->assertOk()
            ->assertJson(['success' => true])
            ->assertJsonPath('data.id', $event->id)
            ->assertJsonPath('data.location', 'Ankara');
    }

    public function test_show_returns_404_as_json_for_nonexistent_event(): void
    {
        $this->getJson('/api/events/99999')
            ->assertNotFound()
            ->assertJson([
                'success'   => false,
                'http_code' => 404,
            ]);
    }

    public function test_show_returns_409_for_business_conflict(): void
    {
        $this->getJson('/api/events/13')
            ->assertStatus(409)
            ->assertJson([
                'success'   => false,
                'http_code' => 409,
            ]);
    }

    // --- POST /api/events (метод не поддерживается) ---

    public function test_post_to_events_returns_405_as_json_without_accept_header(): void
    {
        // Намеренно без postJson - проверяем фикс: до него возвращался HTML
        $this->post('/api/events')
            ->assertStatus(405)
            ->assertJsonPath('success', false)
            ->assertJsonPath('http_code', 405);
    }
}
