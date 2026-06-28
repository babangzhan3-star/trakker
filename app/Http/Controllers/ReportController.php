<?php

namespace App\Http\Controllers;

use App\Mock\CourseRepository;
use App\Mock\LecturerRepository;
use App\Mock\ProjectRepository;
use App\Mock\TaskRepository;
use App\Mock\UserRepository;
use App\Services\MockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // MOCK API — from JSON, no database
        $userId = Auth::id();

        $myProjectIds = UserRepository::getUserProjectIds($userId);
        $myTasks = TaskRepository::forUserProjects($userId);

        // Overview
        $totalProjects = count($myProjectIds);
        $totalTasks = $myTasks->count();
        $doneTasks = $myTasks->where('status', 'selesai')->count();
        $completionRate = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;

        // Progress per proyek
        $projectsProgress = collect($myProjectIds)->map(function ($pid) {
            $p = ProjectRepository::findWithRelations($pid);
            if (!$p) return null;
            $p->deadline = MockService::toCarbon($p->deadline);
            $p->created_at = MockService::toCarbon($p->created_at);
            return $p;
        })->filter()->values();

        // Anggota paling aktif
        $memberTaskCounts = [];
        foreach ($myTasks as $t) {
            $uid = $t->assigned_to;
            if (!isset($memberTaskCounts[$uid])) {
                $memberTaskCounts[$uid] = ['total' => 0, 'done' => 0];
            }
            $memberTaskCounts[$uid]['total']++;
            if ($t->status === 'selesai') {
                $memberTaskCounts[$uid]['done']++;
            }
        }

        uasort($memberTaskCounts, fn($a, $b) => $b['done'] - $a['done']);
        $memberTaskCounts = array_slice($memberTaskCounts, 0, 5, true);

        $topMembers = collect();
        foreach ($memberTaskCounts as $uid => $counts) {
            $u = UserRepository::find($uid);
            if (!$u) continue;
            $u->total_count = $counts['total'];
            $u->done_count = $counts['done'];
            $topMembers->push($u);
        }

        // Rekap per proyek
        $recapPerCourse = $projectsProgress;

        return view('reports.index', compact(
            'totalProjects', 'totalTasks', 'doneTasks', 'completionRate',
            'projectsProgress', 'topMembers', 'recapPerCourse'
        ));
    }
}
