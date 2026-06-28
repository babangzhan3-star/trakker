<?php

namespace App\Mock;

use Illuminate\Contracts\Routing\UrlRoutable;

/**
 * MOCK API — Lightweight object wrapper that implements UrlRoutable 
 * so route('name', $obj) works in Blade views (like Eloquent models).
 */
class MockObject implements UrlRoutable
{
    public function __construct(object $data)
    {
        foreach (get_object_vars($data) as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getRouteKey(): mixed
    {
        return $this->id ?? null;
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this;
    }

    public function resolveChildRouteBinding($childType, $value, $field)
    {
        return null;
    }
}
