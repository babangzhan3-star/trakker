<?php

namespace App\Mock;

use App\Services\MockService;
use Illuminate\Support\Collection;

/**
 * MOCK API — Task repository. Reads from storage/mock/tasks.json.
 */
class TaskRepository extends BaseRepository
{
    protected static string $key = 'tasks';

    /**
     * Get tasks belonging to projects a user is member of.
     */
    public static function forUserProjects(int $userId): Collection
    {
        $projectIds = UserRepository::getUserProjectIds($userId);
        return static::all()
            ->filter(fn($t) => in_array($t->project_id, $projectIds))
            ->values();
    }

    /**
     * Get tasks assigned to a specific user.
     */
    public static function forUser(int $userId): Collection
    {
        return static::all()->where('assigned_to', $userId)->values();
    }

    /**
     * Get tasks for a specific project.
     */
    public static function forProject(int $projectId): Collection
    {
        return static::all()->where('project_id', $projectId)->values();
    }

    /**
     * Resolve relations on a task object (project, assignedUser, creator).
     */
    public static function resolveRelations(object $task): object
    {
        return MockService::relateTask($task);
    }
}
