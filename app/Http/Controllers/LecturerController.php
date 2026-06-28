<?php

namespace App\Http\Controllers;

use App\Mock\LecturerRepository;
use App\Mock\MockObject;
use App\Models\Lecturer;
use App\Services\MockService;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    public function index()
    {
        // MOCK API — from JSON, no database
        $lecturers = LecturerRepository::allSorted()->map(function ($l) {
            $obj = new MockObject($l);
            $obj->created_at = MockService::toCarbon($l->created_at ?? null);
            $obj->updated_at = MockService::toCarbon($l->updated_at ?? null);
            return $obj;
        });

        $lecturers = LecturerRepository::paginate($lecturers, 12);

        return view('lecturers.index', compact('lecturers'));
    }

    public function store(Request $request)
    {
        // MOCK API — no DB
        $request->validate([
            'nama' => 'required|string|max:100',
            'gelar' => 'nullable|string|max:30',
            'nidn' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'no_telp' => 'nullable|string|max:15',
        ]);

        return redirect()->route('lecturers.index')
            ->with('success', 'Dosen berhasil ditambahkan!');
    }

    public function update(Request $request, Lecturer $lecturer)
    {
        // MOCK API — no DB
        $request->validate([
            'nama' => 'required|string|max:100',
            'gelar' => 'nullable|string|max:30',
            'nidn' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'no_telp' => 'nullable|string|max:15',
        ]);

        return redirect()->route('lecturers.index')
            ->with('success', 'Data dosen berhasil diperbarui!');
    }

    public function destroy(Lecturer $lecturer)
    {
        // MOCK API — no DB
        return redirect()->route('lecturers.index')
            ->with('success', 'Data dosen berhasil dihapus!');
    }
}
