<?php

declare(strict_types=1);

namespace App\Modules\Example\DTO;

final readonly class EventDto
{
    public function __construct(
        public int $id,
        public string $location,
        public string $magnitude,
    ) {}
}
