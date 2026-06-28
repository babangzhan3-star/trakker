<?php

namespace App\Http\Controllers;

use App\Mock\CourseRepository;
use App\Mock\ProjectRepository;
use App\Mock\TaskRepository;
use App\Mock\UserRepository;
use App\Models\Task;
use App\Services\MockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // MOCK API — from JSON, no database
        $userId = Auth::id();

        $tasks = TaskRepository::forUserProjects($userId);

        // Tab filter
        if ($request->filled('status')) {
            $tasks = $tasks->where('status', $request->status);
        }
        if ($request->filled('project_id')) {
            $tasks = $tasks->where('project_id', (int) $request->project_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $tasks = $tasks->filter(function ($t) use ($search) {
                return str_contains(strtolower($t->judul ?? ''), strtolower($search))
                    || str_contains(strtolower($t->deskripsi ?? ''), strtolower($search));
            })->values();
        }

        // Sort
        $sort = $request->get('sort', 'deadline');
        if ($sort === 'terbaru') {
            $tasks = $tasks->sortByDesc(function ($t) { return $t->created_at ?? ''; });
        } else {
            $tasks = $tasks->sortBy(function ($t) { return $t->deadline ?? ''; });
        }

        $tasks = $tasks->values();

        // Counts for tabs
        $countAll = $tasks->count();
        $countBelum = $tasks->where('status', 'belum_dimulai')->count();
        $countProses = $tasks->where('status', 'sedang_dikerjakan')->count();
        $countReview = $tasks->where('status', 'menunggu_review')->count();
        $countSelesai = $tasks->where('status', 'selesai')->count();

        // Resolve relations
        $taskObjects = $tasks->map(function ($t) {
            $t = TaskRepository::resolveRelations($t);
            $t->deadline = MockService::toCarbon($t->deadline);
            $t->created_at = MockService::toCarbon($t->created_at);
            $t->updated_at = MockService::toCarbon($t->updated_at);
            // Attach project with course
            if ($t->project) {
                $t->project->course = CourseRepository::find($t->project->course_id ?? 0);
            }
            return $t;
        });

        $tasks = TaskRepository::paginate($taskObjects, 15);

        // Projects for filter dropdown
        $projects = TaskRepository::forUserProjects($userId)
            ->pluck('project_id')
            ->unique()
            ->values()
            ->map(function ($pid) {
                return ProjectRepository::find($pid);
            })
            ->filter()
            ->values()
            ->sortBy('nama_proyek');

        return view('tasks.index', compact(
            'tasks', 'projects',
            'countAll', 'countBelum', 'countProses', 'countReview', 'countSelesai'
        ));
    }

    public function create(Request $request)
    {
        // MOCK API
        $userId = Auth::id();
        $myProjectIds = UserRepository::getUserProjectIds($userId);

        $projects = collect($myProjectIds)->map(function ($pid) {
            $p = ProjectRepository::findWithRelations($pid);
            if (!$p) return null;
            $p->course = CourseRepository::find($p->course_id ?? 0);
            return $p;
        })->filter()->values()->sortBy('nama_proyek');

        $selectedProject = null;
        $members = collect();

        if ($request->filled('project_id')) {
            $selectedProject = $projects->firstWhere('id', (int) $request->project_id);
            if ($selectedProject) {
                $members = $selectedProject->members;
            }
        }

        return view('tasks.create', compact('projects', 'selectedProject', 'members'));
    }

    public function store(Request $request)
    {
        // MOCK API — no DB
        $request->validate([
            'project_id' => 'required|integer',
            'judul' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'assigned_to' => 'required|integer',
            'status' => 'required|in:belum_dimulai,sedang_dikerjakan,menunggu_review,selesai',
            'deadline' => 'required|date',
        ]);

        return redirect()->route('projects.show', $request->project_id)
            ->with('success', 'Tugas berhasil ditambahkan!');
    }

    public function edit(Task $task)
    {
        // MOCK API — data from repository
        $taskData = TaskRepository::find($task->id);
        if (!$taskData) abort(404);
        $taskData = TaskRepository::resolveRelations($taskData);
        $taskData->deadline = MockService::toCarbon($taskData->deadline);

        // Resolve project with full relations (including members)
        if ($taskData->project) {
            $taskData->project = ProjectRepository::resolveRelations($taskData->project);
        }

        $userId = Auth::id();
        $myProjectIds = UserRepository::getUserProjectIds($userId);

        $projects = collect($myProjectIds)->map(function ($pid) {
            $p = ProjectRepository::findWithRelations($pid);
            if (!$p) return null;
            $p->course = CourseRepository::find($p->course_id ?? 0);
            return $p;
        })->filter()->values()->sortBy('nama_proyek');

        // Project members for the task's project
        $members = collect();
        if ($taskData->project) {
            $members = $taskData->project->members ?? collect();
        }

        $task = $taskData;
        return view('tasks.edit', compact('task', 'projects', 'members'));
    }

    public function update(Request $request, Task $task)
    {
        // MOCK API — no DB
        $request->validate([
            'project_id' => 'required|integer',
            'judul' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'assigned_to' => 'required|integer',
            'status' => 'required|in:belum_dimulai,sedang_dikerjakan,menunggu_review,selesai',
            'deadline' => 'required|date',
        ]);

        return redirect()->route('projects.show', $task->project_id)
            ->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy(Task $task)
    {
        // MOCK API — no DB
        return redirect()->route('projects.show', $task->project_id)
            ->with('success', 'Tugas "' . $task->judul . '" berhasil dihapus!');
    }
}
