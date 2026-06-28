@extends('layouts.app')
@section('title', 'Edit Proyek')

@section('content')
<a href="{{ route('projects.show', $project) }}" class="text-decoration-none d-inline-block mb-3" style="font-size:14px;color:var(--slate-500);">
    <i class="bi-arrow-left me-1"></i> Kembali ke Detail Proyek
</a>

<div class="card-clean mx-auto" style="max-width:600px;">
    <h1 class="page-heading mb-1">✏️ Edit Proyek</h1>
    <p class="page-subtitle mb-4">Perbarui detail proyek</p>

    <form action="{{ route('projects.update', $project) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-medium" style="font-size:14px;">Nama Proyek <span class="text-danger">*</span></label>
            <input type="text" name="nama_proyek" class="form-control form-control-custom @error('nama_proyek') is-invalid @enderror"
                   value="{{ old('nama_proyek', $project->nama_proyek) }}" required>
            @error('nama_proyek') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium" style="font-size:14px;">Mata Kuliah <span class="text-danger">*</span></label>
            <select name="course_id" class="form-select form-control-custom @error('course_id') is-invalid @enderror" required>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ old('course_id', $project->course_id) == $course->id ? 'selected' : '' }}>
                        {{ $course->kode_mk }} — {{ $course->nama_mk }}
                    </option>
                @endforeach
            </select>
            @error('course_id') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium" style="font-size:14px;">Dosen Pengampu <span class="text-danger">*</span></label>
            <select name="lecturer_id" class="form-select form-control-custom @error('lecturer_id') is-invalid @enderror" required>
                @foreach($lecturers as $lecturer)
                    <option value="{{ $lecturer->id }}" {{ old('lecturer_id', $project->lecturer_id) == $lecturer->id ? 'selected' : '' }}>
                        {{ $lecturer->nama }}@if($lecturer->gelar), {{ $lecturer->gelar }}@endif
                    </option>
                @endforeach
            </select>
            @error('lecturer_id') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label fw-medium" style="font-size:14px;">Semester</label>
                <select name="semester" class="form-select form-control-custom">
                    <option value="">—</option>
                    @for($i = 1; $i <= 14; $i++)
                        <option value="{{ $i }}" {{ old('semester', $project->semester) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-medium" style="font-size:14px;">Kelas</label>
                <select name="kelas" class="form-select form-control-custom">
                    <option value="">—</option>
                    @foreach(['A', 'B', 'C', 'D', 'E'] as $k)
                        <option value="{{ $k }}" {{ old('kelas', $project->kelas) == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-medium" style="font-size:14px;">Status</label>
                <select name="status" class="form-select form-control-custom">
                    <option value="aktif" {{ old('status', $project->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ old('status', $project->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditunda" {{ old('status', $project->status) == 'ditunda' ? 'selected' : '' }}>Ditunda</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium" style="font-size:14px;">Deadline <span class="text-danger">*</span></label>
            <input type="date" name="deadline" class="form-control form-control-custom @error('deadline') is-invalid @enderror"
                   value="{{ old('deadline', $project->deadline->format('Y-m-d')) }}" required>
            @error('deadline') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-medium" style="font-size:14px;">Deskripsi</label>
            <textarea name="deskripsi" class="form-control form-control-custom" rows="3"
                      placeholder="Deskripsi singkat proyek...">{{ old('deskripsi', $project->deskripsi) }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('projects.show', $project) }}" class="btn-outline-custom text-decoration-none flex-grow-1 text-center">Batal</a>
            <button type="submit" class="btn-primary-custom flex-grow-1">
                <i class="bi-check-lg me-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
