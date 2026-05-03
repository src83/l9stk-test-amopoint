<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class CommonRequest extends FormRequest
{
    protected $stopOnFirstFailure = false;

    // Проверка авторизации / AuthorizationException / 403
    public function authorize(): bool
    {
        // Все запросы разрешены по умолчанию (можно переопределить в наследниках)
        return true;
    }

    abstract public function rules(): array;
}
