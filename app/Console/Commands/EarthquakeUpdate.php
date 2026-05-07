<?php

namespace App\Console\Commands;

use App\Modules\Example\Services\EarthquakeService;
use Illuminate\Console\Command;

class EarthquakeUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'earthquake:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Earthquake events updating from external API';


    /**
     * service instance.
     *
     * @var EarthquakeService
     */
    protected EarthquakeService $earthquakeService;

    /**
     * Create a new command instance.
     *
     * @param EarthquakeService $earthquakeService
     */
    public function __construct(EarthquakeService $earthquakeService)
    {
        parent::__construct();
        $this->earthquakeService = $earthquakeService;
    }

    /**
     * Execute the console command.
     * @throws \JsonException
     */
    public function handle(): void
    {
        $status = $this->earthquakeService->eventsUpdate();

        match ($status) {
            0 => $this->info('Skipped'),
            1 => $this->info('Success'),
            2 => $this->info('Response data is empty'),
      default => $this->info('Failure'),
        };

    }
}
