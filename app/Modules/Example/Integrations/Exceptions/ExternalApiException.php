<?php

namespace App\Modules\Example\Integrations\Exceptions;

use Exception;

class ExternalApiException extends Exception
{
    public function __construct(
        string $message = '',
        int $code = 0,
    ) {
        parent::__construct($message, $code);
    }
}
