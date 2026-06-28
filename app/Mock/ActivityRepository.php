<?php

namespace App\Mock;

use App\Services\MockService;
use Illuminate\Support\Collection;

/**
 * MOCK API — Activity repository. Reads from storage/mock/activities.json.
 */
class ActivityRepository extends BaseRepository
{
    protected static string $key = 'activities';

    /**
     * Get activities for projects a user is member of.
     */
    public static function forUserProjects(int $userId): Collection
    {
        $projectIds = UserRepository::getUserProjectIds($userId);
        return static::all()
            ->filter(fn($a) => in_array($a->project_id, $projectIds))
            ->values();
    }

    /**
     * Get activities for a specific project.
     */
    public static function forProject(int $projectId): Collection
    {
        return static::all()->where('project_id', $projectId)->values();
    }

    /**
     * Resolve relations on an activity object (user, project).
     */
    public static function resolveRelations(object $activity): object
    {
        return MockService::relateActivity($activity);
    }
}
