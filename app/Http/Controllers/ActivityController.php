<?php

namespace App\Http\Controllers;

use App\Mock\ActivityRepository;
use App\Mock\ProjectRepository;
use App\Services\MockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        // MOCK API — from JSON, no database
        $userId = Auth::id();

        $activities = ActivityRepository::forUserProjects($userId);

        if ($request->filled('tipe')) {
            $activities = $activities->where('tipe', $request->tipe);
        }
        if ($request->filled('project_id')) {
            $activities = $activities->where('project_id', (int) $request->project_id);
        }

        $activities = $activities->sortByDesc(function ($a) {
            return $a->created_at ?? '';
        })->values();

        $activityObjects = $activities->map(function ($a) {
            $a = ActivityRepository::resolveRelations($a);
            $a->created_at = MockService::toCarbon($a->created_at);
            return $a;
        });

        $activities = ActivityRepository::paginate($activityObjects, 20);

        // Projects for filter
        $myProjectIds = \App\Mock\UserRepository::getUserProjectIds($userId);
        $projects = collect($myProjectIds)->map(function ($pid) {
            return ProjectRepository::find($pid);
        })->filter()->values()->sortBy('nama_proyek');

        return view('activities.index', compact('activities', 'projects'));
    }
}
