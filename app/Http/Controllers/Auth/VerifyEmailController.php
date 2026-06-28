<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(\Illuminate\Foundation\Auth\EmailVerificationRequest $request): RedirectResponse
    {
        // MOCK API
        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
