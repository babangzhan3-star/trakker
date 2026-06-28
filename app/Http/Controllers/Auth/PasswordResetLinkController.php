<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\MockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function store(Request $request): RedirectResponse
    {
        // MOCK API
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = MockService::load('users')->firstWhere('email', $request->email);

        if (!$user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __('passwords.user')]);
        }

        return back()->with('status', __('passwords.sent'));
    }
}
