<?php

declare(strict_types=1);

namespace App\Modules\Example\Http\Requests\Api;

use App\Http\Requests\CommonRequest;

final class EventRequest extends CommonRequest
{
    /**
     * Get the validation rules that apply to the request
     * For 'GET' - params; For 'POST' - body;
     * @return string[]
     */
    public function rules(): array
    {
        $rules = [];

        // GET: index()
        if ($this->routeIs('api.events.list')) {
            $rules = ['page' => 'nullable|integer|min:1'];
        }

        // GET: show()
        if ($this->routeIs('api.events.show')) {
            $rules = ['id' => 'required|integer|between:0,5'];
        }

        return $rules;
    }

    /**
     * Validation of params from ROUTE - Priority
     * @return array
     */
    public function validationData(): array
    {
        return array_merge($this->all(), [
            'id' => $this->route('id'),
        ]);
    }
}
