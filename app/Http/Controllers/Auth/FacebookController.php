<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class FacebookController extends Controller
{
    public function redirect(SocialAuthController $socialAuthController): RedirectResponse
    {
        return $socialAuthController->redirect('facebook');
    }

    public function callback(Request $request, SocialAuthController $socialAuthController): RedirectResponse
    {
        return $socialAuthController->callback('facebook', $request);
    }
}
