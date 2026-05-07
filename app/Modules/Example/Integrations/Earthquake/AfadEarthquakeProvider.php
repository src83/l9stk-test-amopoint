<?php

declare(strict_types=1);

namespace App\Modules\Example\Integrations\Earthquake;

use App\Modules\Example\DTO\EarthquakeEventDTO;
use App\Modules\Example\Integrations\Exceptions\ExternalApiException;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class AfadEarthquakeProvider implements EarthquakeProviderInterface
{
    /**
     * https://servisnet.afad.gov.tr/apigateway/deprem/apiv2/event/filter?start=YYYY-MM-DD&end=YYYY-MM-DD
     *
     * @return Collection
     * @throws ExternalApiException
     * @throws \Exception
     */
    public function getEvents(): Collection
    {
        $now = new DateTimeImmutable('now', new DateTimeZone('UTC'));

        $start = $now->modify('-6 hours')->format('Y-m-d H:i:s');
        $end   = $now->format('Y-m-d H:i:s');
        $requestUrl = config('api.afad_source') . '?start='.$start.'&end='.$end;

        $response = Http::get($requestUrl);

        if ($response->failed()) {
            throw new ExternalApiException($response->body());
        }

        $events = $response->json();

        if (!is_array($events)) {
            throw new ExternalApiException('Invalid API response');
        }

        $eventsCollection = collect($events)->map(
            static fn (array $item) => new EarthquakeEventDTO(
                eventId: $item['eventID'],
                latitude: is_numeric($item['latitude']) ? (float)$item['latitude'] : null,
                longitude: is_numeric($item['longitude']) ? (float)$item['longitude'] : null,

                depth: is_numeric($item['depth']) ? (float)$item['depth'] : null,
                magnitude: is_numeric($item['magnitude']) ? (float)$item['magnitude'] : null,
                rms: is_numeric($item['rms']) ? (float)$item['rms'] : null,
                type: is_string($item['type']) ? $item['type'] : null,

                location: is_string($item['location']) ? $item['location'] : null,
                country: is_string($item['country']) ? $item['country'] : null,
                province: is_string($item['province']) ? $item['province'] : null,
                district: is_string($item['district']) ? $item['district'] : null,
                neighborhood: is_string($item['neighborhood']) ? $item['neighborhood'] : null,

                eventMoment: !empty($item['date'])
                    ? new DateTimeImmutable($item['date'], new DateTimeZone('UTC'))
                    : throw new ExternalApiException('Invalid date'),

                isEventUpdate: (bool)$item['isEventUpdate'],

                lastUpdateDate: isset($item['lastUpdateDate'])
                    ? new DateTimeImmutable($item['lastUpdateDate'], new DateTimeZone('UTC'))
                    : null,
            )
        );

        return $eventsCollection;
    }
}
