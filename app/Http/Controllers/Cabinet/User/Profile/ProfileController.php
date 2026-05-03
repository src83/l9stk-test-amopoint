<?php

namespace App\Http\Controllers\Cabinet\User\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    public function index(): Renderable
    {
        return view('cabinet.profile');
    }
}
