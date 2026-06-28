<?php

namespace App\Http\Controllers;

use App\Mock\TaskRepository;
use App\Services\MockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // MOCK API — from JSON, no database
        $userId = $request->user()->id;

        $myProjects = \App\Services\MockService::load('project_members')
            ->where('user_id', $userId)->count();
        $myTasks = TaskRepository::forUser($userId)->count();
        $myDone = TaskRepository::forUser($userId)->where('status', 'selesai')->count();

        $user = $request->user();

        return view('profile.edit', compact('user', 'myProjects', 'myTasks', 'myDone'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        // MOCK API — no DB
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100'],
            'nim' => ['nullable', 'string', 'max:20'],
            'semester' => ['nullable', 'integer', 'min:1', 'max:14'],
            'kelas' => ['nullable', 'string', 'max:5'],
        ]);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // MOCK API — no DB
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'string'],
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
