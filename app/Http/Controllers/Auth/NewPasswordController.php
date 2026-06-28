<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\MockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     */
    public function store(Request $request): RedirectResponse
    {
        // MOCK API
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'string', 'min:6'],
        ]);

        $user = MockService::load('users')->firstWhere('email', $request->email);

        if (!$user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __('passwords.user')]);
        }

        return redirect()->route('login')->with('status', __('passwords.reset'));
    }
}
