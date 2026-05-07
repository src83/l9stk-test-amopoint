<?php

declare(strict_types=1);

namespace App\Modules\Example\Repositories;

use App\Modules\Example\DTO\EarthquakeEventDTO;
use App\Modules\Example\Models\EarthquakeEvent;
use Illuminate\Support\Collection;

class EarthquakeRepository
{
    public function save(Collection $events): int
    {
        if ($events->isEmpty()) {
            return 2;
        }

        $rows = $events->map(
            fn (EarthquakeEventDTO $event) => $this->mapToRow($event)
        )->all();

        $beforeCount = EarthquakeEvent::query()->count();

        EarthquakeEvent::query()->upsert(
            $rows,
            ['event_id'],
            [
                'latitude',
                'longitude',

                'depth',
                'magnitude',
                'rms',
                'type',

                'location',
                'country',
                'province',
                'district',
                'neighborhood',

                'event_moment',

                'is_event_update',
                'last_update_date',
                'updated_at',
            ]
        );

        $afterCount = EarthquakeEvent::query()->count();

        return ($afterCount - $beforeCount > 0) ? 1 : 0;
    }

    private function mapToRow(EarthquakeEventDTO $event): array
    {
        return [
            'event_id' => $event->eventId,
            'latitude' => $event->latitude,
            'longitude' => $event->longitude,

            'depth' => $event->depth,
            'magnitude' => $event->magnitude,
            'rms' => $event->rms,
            'type' => $event->type,

            'location' => $event->location,
            'country' => $event->country,
            'province' => $event->province,
            'district' => $event->district,
            'neighborhood' => $event->neighborhood,

            'event_moment' => $event->eventMoment->format('Y-m-d H:i:s'),
            'is_event_update' => $event->isEventUpdate,
            'last_update_date' => $event->lastUpdateDate?->format('Y-m-d H:i:s'),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
