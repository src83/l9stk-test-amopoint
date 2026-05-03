<?php

namespace App\Http\Controllers\Cabinet\User\Settings;

use App\Actions\Cabinet\User\LogoutOtherDevicesAction;
use App\Actions\Cabinet\User\Settings\UpdatePasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\User\UpdatePasswordRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class UserPasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        public UpdatePasswordAction $updatePasswordAction,
        public LogoutOtherDevicesAction $logoutOtherDevicesAction
    ) {}

    public function index(): Renderable
    {
        return view('cabinet.settings');
    }

    /**
     * @throws AuthenticationException
     */
    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = $request->user();
        $logout = $request->boolean('logout_other_sessions');

        if ($logout) {
            $this->logoutOtherDevicesAction->execute($data['current_password']);
        }

        $this->updatePasswordAction->execute($user, $data['password']);

        $message = $logout
            ? 'Password updated and other sessions logged out.'
            : 'Password updated.';

        return back()->with('success', $message);
    }
}
