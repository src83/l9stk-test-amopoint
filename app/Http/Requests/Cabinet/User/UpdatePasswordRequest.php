<?php

declare(strict_types=1);

namespace App\Http\Requests\Cabinet\User;

use App\Http\Requests\CommonRequest;
use Illuminate\Validation\Rules\Password;

final class UpdatePasswordRequest extends CommonRequest
{
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'different:current_password',
                Password::min(6), // TODO: Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
            'password_confirmation' => ['required', 'same:password'],
            'logout_other_sessions' => ['nullable', 'boolean'],
        ];
    }
}
