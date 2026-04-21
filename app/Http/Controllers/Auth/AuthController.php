<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function linkedinCallback(Request $request, SocialAuthController $socialAuthController): RedirectResponse
    {
        return $socialAuthController->callback('linkedin', $request);
    }
}