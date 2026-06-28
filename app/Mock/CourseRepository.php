<?php

namespace App\Mock;

use App\Services\MockService;

/**
 * MOCK API — Course repository. Reads from storage/mock/courses.json.
 */
class CourseRepository extends BaseRepository
{
    protected static string $key = 'courses';

    /**
     * Get all courses sorted by name.
     */
    public static function allSorted(): \Illuminate\Support\Collection
    {
        return static::all()->sortBy('nama_mk')->values();
    }
}
