<?php

namespace App\Http\Controllers;

use App\Mock\CourseRepository;
use App\Mock\MockObject;
use App\Models\Course;
use App\Services\MockService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        // MOCK API — from JSON, no database
        $courses = CourseRepository::allSorted()->map(function ($c) {
            $obj = new MockObject($c);
            $obj->created_at = MockService::toCarbon($c->created_at ?? null);
            $obj->updated_at = MockService::toCarbon($c->updated_at ?? null);
            return $obj;
        });

        $courses = CourseRepository::paginate($courses, 10);

        return view('courses.index', compact('courses'));
    }

    public function store(Request $request)
    {
        // MOCK API — simulasi sukses, no DB
        $request->validate([
            'kode_mk' => 'required|string|max:10',
            'nama_mk' => 'required|string|max:100',
            'sks' => 'nullable|integer|min:1|max:6',
        ]);

        return redirect()->route('courses.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan!');
    }

    public function update(Request $request, Course $course)
    {
        // MOCK API — no DB
        $request->validate([
            'kode_mk' => 'required|string|max:10',
            'nama_mk' => 'required|string|max:100',
            'sks' => 'nullable|integer|min:1|max:6',
        ]);

        return redirect()->route('courses.index')
            ->with('success', 'Mata kuliah berhasil diperbarui!');
    }

    public function destroy(Course $course)
    {
        // MOCK API — no DB
        return redirect()->route('courses.index')
            ->with('success', 'Mata kuliah berhasil dihapus!');
    }
}
