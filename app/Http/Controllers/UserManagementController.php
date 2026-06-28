<?php

namespace App\Http\Controllers;

use App\Mock\UserRepository;
use App\Models\User;
use App\Services\MockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        // MOCK API — from JSON, no database
        $users = UserRepository::all()->map(function ($u) {
            // Hitung statistik
            $u->projects_count = \App\Services\MockService::load('project_members')
                ->where('user_id', $u->id)->count();

            $myProjectIds = UserRepository::getUserProjectIds($u->id);
            $u->tasks_done_count = \App\Services\MockService::load('tasks')
                ->filter(fn($t) => $t->assigned_to === $u->id
                    && in_array($t->project_id, $myProjectIds)
                    && $t->status === 'selesai')
                ->count();

            $u->created_at = MockService::toCarbon($u->created_at ?? null);
            $u->updated_at = MockService::toCarbon($u->updated_at ?? null);
            return $u;
        });

        if ($request->filled('search')) {
            $q = strtolower($request->search);
            $users = $users->filter(fn($u) =>
                str_contains(strtolower($u->name ?? ''), $q)
                || str_contains(strtolower($u->email ?? ''), $q)
                || str_contains(strtolower($u->nim ?? ''), $q)
            )->values();
        }

        $users = $users->sortBy('name')->values();
        $users = UserRepository::paginate($users, 15);

        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        // MOCK API — no DB
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'nim' => 'nullable|string|max:20',
            'semester' => 'nullable|integer|min:1|max:14',
            'kelas' => 'nullable|string|max:5',
            'password' => 'required|string|min:6|confirmed',
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        // MOCK API — no DB
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'nim' => 'nullable|string|max:20',
            'semester' => 'nullable|integer|min:1|max:14',
            'kelas' => 'nullable|string|max:5',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        // MOCK API — no DB
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Kamu tidak bisa menghapus akun sendiri.');
        }

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus!');
    }
}
