<?php

declare(strict_types=1);

namespace App\Modules\Example\DTO;

use DateTimeImmutable;

final readonly class EarthquakeEventDTO
{
    public function __construct(

        public string $eventId,
        public ?float $latitude,
        public ?float $longitude,

        public ?float $depth,
        public ?float $magnitude,
        public ?float $rms,
        public ?string $type,

        public ?string $location,
        public ?string $country,
        public ?string $province,
        public ?string $district,
        public ?string $neighborhood,

        public DateTimeImmutable $eventMoment,
        public bool $isEventUpdate,
        public ?DateTimeImmutable $lastUpdateDate = null,
    ) {}

    public function toArray(): array
    {
        return [
            'event_id' => $this->eventId,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,

            'depth' => $this->depth,
            'magnitude' => $this->magnitude,
            'rms' => $this->rms,
            'type' => $this->type,

            'location' => $this->location,
            'country' => $this->country,
            'province' => $this->province,
            'district' => $this->district,
            'neighborhood' => $this->neighborhood,

            'event_moment' => $this->eventMoment,
            'is_event_update' => $this->isEventUpdate,
            'last_update_date' => $this->lastUpdateDate,
        ];
    }
}
