<?php

namespace App\Http\Controllers;

use App\Mock\ActivityRepository;
use App\Mock\CourseRepository;
use App\Mock\LecturerRepository;
use App\Mock\MockObject;
use App\Mock\ProjectRepository;
use App\Mock\TaskRepository;
use App\Mock\UserRepository;
use App\Models\Project;
use App\Models\User;
use App\Services\MockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        // MOCK API — from JSON, no database
        $userId = Auth::id();

        // Ambil proyek user
        $allProjects = ProjectRepository::forUser($userId);

        // Filter by course/lecturer — skip member filter
        if ($request->has('course') || $request->has('lecturer')) {
            $allProjects = ProjectRepository::all();
        }

        if ($request->filled('course')) {
            $allProjects = $allProjects->where('course_id', (int) $request->course);
        }
        if ($request->filled('lecturer')) {
            $allProjects = $allProjects->where('lecturer_id', (int) $request->lecturer);
        }
        if ($request->filled('status')) {
            $allProjects = $allProjects->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $allProjects = $allProjects->filter(function ($p) use ($search) {
                $course = CourseRepository::find($p->course_id ?? 0);
                return str_contains(strtolower($p->nama_proyek ?? ''), strtolower($search))
                    || ($course && str_contains(strtolower($course->nama_mk ?? ''), strtolower($search)));
            })->values();
        }

        // Sort by latest
        $allProjects = $allProjects->sortByDesc(function ($p) {
            return $p->created_at ?? '';
        })->values();

        // Resolve relations and wrap in MockObject
        $projectObjects = $allProjects->map(function ($p) {
            $p = ProjectRepository::resolveRelations($p);
            $obj = new MockObject($p);
            $obj->deadline = MockService::toCarbon($p->deadline);
            $obj->created_at = MockService::toCarbon($p->created_at);
            $obj->updated_at = MockService::toCarbon($p->updated_at);
            return $obj;
        });

        $projects = ProjectRepository::paginate($projectObjects, 10);

        // Data untuk filter dropdown
        $courses = CourseRepository::all();
        $lecturers = LecturerRepository::allSorted();

        return view('projects.index', compact('projects', 'courses', 'lecturers'));
    }

    public function create()
    {
        // MOCK API
        $courses = CourseRepository::all();
        $lecturers = LecturerRepository::allSorted();

        return view('projects.create', compact('courses', 'lecturers'));
    }

    public function store(Request $request)
    {
        // MOCK API — no DB
        $request->validate([
            'nama_proyek' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'course_id' => 'required|integer',
            'lecturer_id' => 'required|integer',
            'semester' => 'nullable|integer|min:1|max:14',
            'kelas' => 'nullable|string|max:5',
            'deadline' => 'required|date|after:today',
        ]);

        return redirect()->route('projects.show', 1)
            ->with('success', 'Proyek berhasil dibuat! Sekarang tambahkan anggota kelompok.');
    }

    public function show(Project $project)
    {
        // MOCK API — data from repository
        $projectData = ProjectRepository::findWithRelations($project->id);
        if (!$projectData) abort(404);

        $project = new MockObject($projectData);
        $project->deadline = MockService::toCarbon($projectData->deadline);
        $project->created_at = MockService::toCarbon($projectData->created_at);
        $project->updated_at = MockService::toCarbon($projectData->updated_at);
        // Wrap members in MockObject for route()
        $project->members = collect($projectData->members)->map(fn($m) => new MockObject($m));

        // Tasks for this project
        $tasks = TaskRepository::forProject($project->id)->map(function ($t) {
            $t = TaskRepository::resolveRelations($t);
            $obj = new MockObject($t);
            $obj->deadline = MockService::toCarbon($t->deadline);
            $obj->created_at = MockService::toCarbon($t->created_at);
            $obj->updated_at = MockService::toCarbon($t->updated_at);
            return $obj;
        });

        $totalTasks = $tasks->count();
        $doneTasks = $tasks->where('status', 'selesai')->count();
        $progressPercent = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;

        $statusCounts = [
            'belum_dimulai' => $tasks->where('status', 'belum_dimulai')->count(),
            'sedang_dikerjakan' => $tasks->where('status', 'sedang_dikerjakan')->count(),
            'menunggu_review' => $tasks->where('status', 'menunggu_review')->count(),
            'selesai' => $doneTasks,
        ];

        // Activities
        $activities = ActivityRepository::forProject($project->id)
            ->sortByDesc(function ($a) { return $a->created_at ?? ''; })
            ->take(5)
            ->values()
            ->map(function ($a) {
                $a = ActivityRepository::resolveRelations($a);
                $obj = new MockObject($a);
                $obj->created_at = MockService::toCarbon($a->created_at);
                return $obj;
            });

        // Available users
        $memberIds = collect($project->members)->pluck('id')->toArray();
        $availableUsers = UserRepository::exceptIds($memberIds)->map(fn($u) => new MockObject($u));

        return view('projects.show', compact(
            'project', 'tasks', 'totalTasks', 'doneTasks',
            'progressPercent', 'statusCounts', 'activities', 'availableUsers'
        ));
    }

    public function edit(Project $project)
    {
        // MOCK API
        $project = ProjectRepository::findWithRelations($project->id);
        if (!$project) abort(404);

        $project->deadline = MockService::toCarbon($project->deadline);

        $courses = CourseRepository::all();
        $lecturers = LecturerRepository::allSorted();

        return view('projects.edit', compact('project', 'courses', 'lecturers'));
    }

    public function update(Request $request, Project $project)
    {
        // MOCK API — no DB
        $request->validate([
            'nama_proyek' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'course_id' => 'required|integer',
            'lecturer_id' => 'required|integer',
            'semester' => 'nullable|integer|min:1|max:14',
            'kelas' => 'nullable|string|max:5',
            'deadline' => 'required|date',
            'status' => 'required|in:aktif,selesai,ditunda',
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Proyek berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        // MOCK API — no DB
        return redirect()->route('projects.index')
            ->with('success', 'Proyek "' . $project->nama_proyek . '" berhasil dihapus!');
    }

    // ─── ANGGOTA ──────────────────────────────────────────

    public function addMember(Request $request, Project $project)
    {
        // MOCK API — no DB
        $request->validate(['user_id' => 'required|integer']);

        $user = UserRepository::find((int) $request->user_id);

        return back()->with('success', ($user->name ?? 'User') . ' berhasil ditambahkan!');
    }

    public function removeMember(Project $project, User $user)
    {
        // MOCK API — no DB
        return back()->with('success', $user->name . ' berhasil dikeluarkan dari proyek.');
    }
}
