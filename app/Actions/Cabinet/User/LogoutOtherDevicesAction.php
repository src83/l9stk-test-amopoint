<?php

declare(strict_types=1);

namespace App\Actions\Cabinet\User;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class LogoutOtherDevicesAction
{
    /**
     * @throws AuthenticationException
     */
    public function execute(string $password): void
    {
        Auth::logoutOtherDevices($password);
    }
}
