<?php

declare(strict_types=1);

namespace App\Modules\Example\Services;

use App\Modules\Example\Integrations\Earthquake\EarthquakeProviderInterface;
use App\Modules\Example\Integrations\Exceptions\ExternalApiException;
use App\Modules\Example\Repositories\EarthquakeRepository;

readonly class EarthquakeService
{
    public function __construct(
        private EarthquakeProviderInterface $provider,
        private EarthquakeRepository $repository,
    ) {}

    /**
     * CLI: Console command "php artisan earthquake:update".
     *
     * @throws \JsonException
     */
    public function eventsUpdate(): int
    {
        try {
            $events = $this->provider->getEvents();
            if ($events->isEmpty()) {
                logger()->warning('No earthquake events returned');

                return 2;
            }

            return $this->repository->save($events); // [0|1|2]

        } catch (ExternalApiException $e) {
            logger()->error('External API error', [
                'message' => $e->getMessage(),
            ]);

            return 3;
        } catch (\Throwable $e) {
            logger()->error('Unexpected error', [
                'message' => $e->getMessage(),
            ]);

            return 3;
        }
    }
}
