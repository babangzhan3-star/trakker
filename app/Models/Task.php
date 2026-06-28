<?php

namespace App\Models;

use App\Services\MockService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

#[Fillable([
    'project_id', 'judul', 'deskripsi', 'assigned_to',
    'created_by', 'status', 'deadline'
])]
class Task extends Model
{
    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    // MOCK API: resolve from JSON instead of database
    public function resolveRouteBinding($value, $field = null)
    {
        $mock = MockService::find('tasks', (int) $value);
        if (!$mock) {
            throw new ModelNotFoundException();
        }
        $mock = MockService::relateTask($mock);

        $instance = new static();
        $instance->id = $mock->id;
        $instance->project_id = $mock->project_id;
        $instance->judul = $mock->judul;
        $instance->deskripsi = $mock->deskripsi;
        $instance->assigned_to = $mock->assigned_to;
        $instance->created_by = $mock->created_by;
        $instance->status = $mock->status;
        $instance->deadline = $mock->deadline;
        $instance->created_at = $mock->created_at;
        $instance->updated_at = $mock->updated_at;
        $instance->exists = true;

        $instance->setRelation('assignedUser', $mock->assignedUser ? (new User())->resolveRouteBinding($mock->assignedUser->id) : null);
        $instance->setRelation('creator', $mock->creator ? (new User())->resolveRouteBinding($mock->creator->id) : null);

        return $instance;
    }
}
