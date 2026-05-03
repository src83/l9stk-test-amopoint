<?php

declare(strict_types=1);

namespace App\Modules\Example\Http\Resources\Api;

use App\Http\Resources\Api\BaseResource;

/**
 * Example API resource.
 *
 * Used for documentation and demo purposes.
 */
final class EventResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'location' => $this->resource->location,
            'magnitude' => $this->resource->magnitude,
        ];
    }
}
