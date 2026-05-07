<?php

declare(strict_types=1);

namespace App\Modules\Example\DTO;

use DateTimeImmutable;

final readonly class EarthquakeEventDTO
{
    public function __construct(

        public string $eventId,
        public ?float $latitude = null,
        public ?float $longitude = null,

        public ?float $depth = null,
        public ?float $magnitude = null,
        public ?float $rms = null,
        public ?string $type = null,

        public ?string $location = null,
        public ?string $country = null,
        public ?string $province = null,
        public ?string $district = null,
        public ?string $neighborhood = null,

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
