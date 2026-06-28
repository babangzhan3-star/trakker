<?php

namespace App\Http\Controllers;

use App\Mock\ActivityRepository;
use App\Mock\ProjectRepository;
use App\Mock\TaskRepository;
use App\Services\MockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // MOCK API — semua data dari JSON
        $userId = Auth::id();

        // ─── Tugas Mendesak ───────────────────────────────
        $urgentTasks = TaskRepository::forUser($userId)
            ->filter(function ($t) {
                return in_array($t->status, ['belum_dimulai', 'sedang_dikerjakan'])
                    && MockService::toCarbon($t->deadline)->lte(now()->addDays(7));
            })
            ->sortBy('deadline')
            ->take(5)
            ->values()
            ->map(function ($t) {
                $t = TaskRepository::resolveRelations($t);
                $t->deadline = MockService::toCarbon($t->deadline);
                $t->created_at = MockService::toCarbon($t->created_at);
                return $t;
            });

        // ─── Proyek Aktif ─────────────────────────────────
        $activeProjects = ProjectRepository::forUser($userId)
            ->where('status', 'aktif')
            ->take(3)
            ->values()
            ->map(function ($p) {
                $p = ProjectRepository::resolveRelations($p);
                $p->deadline = MockService::toCarbon($p->deadline);

                $projectTasks = TaskRepository::forProject($p->id);
                $p->tasks_count = $projectTasks->count();
                $p->tasks_done_count = $projectTasks->where('status', 'selesai')->count();

                return $p;
            });

        // ─── Aktivitas Terbaru ────────────────────────────
        $activities = ActivityRepository::forUserProjects($userId)
            ->sortByDesc(function ($a) { return $a->created_at ?? ''; })
            ->take(5)
            ->values()
            ->map(function ($a) {
                $a = ActivityRepository::resolveRelations($a);
                $a->created_at = MockService::toCarbon($a->created_at);
                return $a;
            });

        // ─── Statistik ────────────────────────────────────
        $totalTasks = TaskRepository::forUser($userId)->count();
        $doneTasks = TaskRepository::forUser($userId)->where('status', 'selesai')->count();
        $completion = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;

        return view('dashboard', compact(
            'urgentTasks', 'activeProjects', 'activities',
            'totalTasks', 'doneTasks', 'completion'
        ));
    }
}
