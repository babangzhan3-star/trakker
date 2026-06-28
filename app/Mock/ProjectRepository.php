<?php

namespace App\Mock;

use App\Services\MockService;
use Illuminate\Support\Collection;

/**
 * MOCK API — Project repository. Reads from storage/mock/projects.json.
 */
class ProjectRepository extends BaseRepository
{
    protected static string $key = 'projects';

    /**
     * Get projects that a user is member of, with relations resolved.
     */
    public static function forUser(int $userId): Collection
    {
        $projectIds = UserRepository::getUserProjectIds($userId);
        return static::all()
            ->filter(fn($p) => in_array($p->id, $projectIds))
            ->values();
    }

    /**
     * Resolve relations on a project object (course, lecturer, members, tasks stats).
     */
    public static function resolveRelations(object $project): object
    {
        return MockService::relateProject($project);
    }

    /**
     * Resolve and return a single project with relations.
     */
    public static function findWithRelations(int $id): ?object
    {
        $project = static::find($id);
        if (!$project) return null;
        return static::resolveRelations($project);
    }
}
