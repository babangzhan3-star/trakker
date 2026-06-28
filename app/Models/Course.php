<?php

namespace App\Models;

use App\Services\MockService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

#[Fillable(['kode_mk', 'nama_mk', 'sks'])]
class Course extends Model
{
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // MOCK API: resolve from JSON instead of database
    public function resolveRouteBinding($value, $field = null)
    {
        $mock = MockService::find('courses', (int) $value);
        if (!$mock) {
            throw new ModelNotFoundException();
        }
        $instance = new static();
        $instance->id = $mock->id;
        $instance->kode_mk = $mock->kode_mk;
        $instance->nama_mk = $mock->nama_mk;
        $instance->sks = $mock->sks;
        $instance->created_at = MockService::toCarbon($mock->created_at ?? null);
        $instance->updated_at = MockService::toCarbon($mock->updated_at ?? null);
        $instance->projects_count = $mock->projects_count ?? 0;
        $instance->exists = true;
        return $instance;
    }
}
