<?php

declare(strict_types=1);

namespace App\Modules\Example\Http\Requests\Cabinet;

use App\Http\Requests\CommonRequest;

final class StoreEntityRequest extends CommonRequest
{
    public function rules(): array
    {
        return [
            'client_id' => 'required|string',
        ];
    }
}
