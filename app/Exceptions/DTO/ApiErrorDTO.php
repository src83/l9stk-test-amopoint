<?php

declare(strict_types=1);

namespace App\Exceptions\DTO;

final readonly class ApiErrorDTO {

    public function __construct(
        public int     $httpCode,
        public ?string $sysMessage = null,
        public mixed   $details = null,
    ) {}

    public function toArray(): array
    {
        return [
            'httpCode' => $this->httpCode,
            'sysMessage' => $this->sysMessage,
            'details' => $this->details,
        ];
    }
}
