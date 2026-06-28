<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * MOCK API — Helper untuk membaca data dari file JSON di storage/mock/
 *
 * Semua data berasal dari file JSON, bukan database.
 */
class MockService
{
    protected static array $cache = [];

    /**
     * Load data dari file JSON sebagai Collection.
     */
    public static function load(string $key): Collection
    {
        if (!isset(static::$cache[$key])) {
            $path = storage_path("mock/{$key}.json");
            if (!file_exists($path)) {
                return collect([]);
            }
            $data = json_decode(file_get_contents($path));
            if (!is_array($data)) {
                return collect([]);
            }
            static::$cache[$key] = collect($data);
        }
        return static::$cache[$key];
    }

    /**
     * Cari item berdasarkan ID.
     */
    public static function find(string $key, int $id): ?object
    {
        return static::load($key)->firstWhere('id', $id);
    }

    /**
     * Cari item berdasarkan ID atau throw exception.
     */
    public static function findOrFail(string $key, int $id): object
    {
        $item = static::find($key, $id);
        if (!$item) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
        }
        return $item;
    }

    /**
     * Simulasi pagination pada Collection.
     */
    public static function paginate(Collection $items, int $perPage = 10, ?string $pageName = 'page'): LengthAwarePaginator
    {
        $page = Paginator::resolveCurrentPage($pageName);
        $total = $items->count();
        $results = $items->forPage($page, $perPage)->values();
        return new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }

    /**
     * Filter collection berdasarkan field-value.
     */
    public static function filter(Collection $items, array $filters): Collection
    {
        foreach ($filters as $field => $value) {
            if ($value !== null && $value !== '' && $value !== false) {
                $items = $items->where($field, $value);
            }
        }
        return $items;
    }

    /**
     * Search collection pada field tertentu.
     */
    public static function search(Collection $items, ?string $query, array $fields): Collection
    {
        if (empty($query)) {
            return $items;
        }
        $q = strtolower($query);
        return $items->filter(function ($item) use ($q, $fields) {
            foreach ($fields as $field) {
                $value = data_get($item, $field);
                if ($value && str_contains(strtolower($value), $q)) {
                    return true;
                }
            }
            return false;
        })->values();
    }

    /**
     * Konversi string tanggal ke Carbon.
     */
    public static function toCarbon(?string $date): ?Carbon
    {
        if (empty($date)) return null;
        return Carbon::parse($date);
    }

    /**
     * Relasikan project dengan course, lecturer, creator.
     * Modifikasi objek project secara langsung.
     */
    public static function relateProject(object $project): object
    {
        $project->course = static::find('courses', $project->course_id ?? 0);
        $project->lecturer = static::find('lecturers', $project->lecturer_id ?? 0);
        $project->creator = static::find('users', $project->created_by ?? 0);
        $project->deadline = static::toCarbon($project->deadline ?? null);
        $project->created_at = static::toCarbon($project->created_at ?? null);
        $project->updated_at = static::toCarbon($project->updated_at ?? null);

        // Members
        $allMembers = static::load('project_members')
            ->where('project_id', $project->id)
            ->values();
        $users = static::load('users');
        $project->members = $allMembers->map(function ($pm) use ($users) {
            $user = $users->firstWhere('id', $pm->user_id);
            if (!$user) return null;
            $clone = clone $user;
            $clone->pivot = (object) [
                'role' => $pm->role,
                'kontribusi_persen' => $pm->kontribusi_persen,
                'project_id' => $pm->project_id,
                'user_id' => $pm->user_id,
            ];
            return $clone;
        })->filter()->values();

        $project->members_count = $project->members->count();

        // Tasks
        $allTasks = static::load('tasks');
        $projectTasks = $allTasks->where('project_id', $project->id)->values();
        $project->tasks_count = $projectTasks->count();
        $project->tasks_done_count = $projectTasks->where('status', 'selesai')->count();

        // Relasikan tasks
        $projectTasks = $projectTasks->map(function ($t) {
            return static::relateTask($t);
        })->values();
        // Jangan setRelation — kita simpan sebagai properti langsung
        // Tapi biarkan tasks di-load terpisah oleh controller

        return $project;
    }

    /**
     * Relasikan task dengan project, assignedUser, creator.
     */
    public static function relateTask(object $task): object
    {
        $task->project = static::find('projects', $task->project_id ?? 0);
        $task->assignedUser = static::find('users', $task->assigned_to ?? 0);
        $task->creator = static::find('users', $task->created_by ?? 0);
        $task->deadline = static::toCarbon($task->deadline ?? null);
        $task->created_at = static::toCarbon($task->created_at ?? null);
        $task->updated_at = static::toCarbon($task->updated_at ?? null);
        return $task;
    }

    /**
     * Relasikan activity dengan user, project.
     */
    public static function relateActivity(object $activity): object
    {
        $activity->user = static::find('users', $activity->user_id ?? 0);
        $activity->project = static::find('projects', $activity->project_id ?? 0);
        $activity->created_at = static::toCarbon($activity->created_at ?? null);
        return $activity;
    }

    /**
     * Simulasi query: ambil project yang diikuti user (by member).
     */
    public static function projectsForUser(int $userId): Collection
    {
        $memberProjectIds = static::load('project_members')
            ->where('user_id', $userId)
            ->pluck('project_id')
            ->unique()
            ->values()
            ->toArray();

        return static::load('projects')
            ->filter(function ($p) use ($memberProjectIds) {
                return in_array($p->id, $memberProjectIds);
            })
            ->values()
            ->map(function ($p) {
                return static::relateProject($p);
            });
    }

    /**
     * Convert raw stdClass dates to Carbon for user object.
     */
    public static function relateUser(object $user): object
    {
        $user->created_at = static::toCarbon($user->created_at ?? null);
        $user->updated_at = static::toCarbon($user->updated_at ?? null);
        $user->email_verified_at = static::toCarbon($user->email_verified_at ?? null);
        return $user;
    }
}
