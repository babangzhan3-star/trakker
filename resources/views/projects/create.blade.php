@extends('layouts.app')
@section('title', 'Buat Proyek')

@section('content')
<a href="{{ route('projects.index') }}" class="text-decoration-none d-inline-block mb-3" style="font-size:14px;color:var(--slate-500);">
    <i class="bi-arrow-left me-1"></i> Kembali
</a>

<div class="card-clean mx-auto" style="max-width:600px;">
    <h1 class="page-heading mb-1">📁 Buat Proyek Baru</h1>
    <p class="page-subtitle mb-4">Isi detail proyek tugas kelompokmu</p>

    <form action="{{ route('projects.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-medium" style="font-size:14px;">Nama Proyek <span class="text-danger">*</span></label>
            <input type="text" name="nama_proyek" class="form-control form-control-custom @error('nama_proyek') is-invalid @enderror"
                   value="{{ old('nama_proyek') }}" placeholder="e.g. Aplikasi TaskFlow" required>
            @error('nama_proyek') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium" style="font-size:14px;">Mata Kuliah <span class="text-danger">*</span></label>
            <select name="course_id" class="form-select form-control-custom @error('course_id') is-invalid @enderror" required>
                <option value="">Pilih Mata Kuliah</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                        {{ $course->kode_mk }} — {{ $course->nama_mk }}
                    </option>
                @endforeach
            </select>
            <a href="{{ route('courses.index') }}" class="text-decoration-none" style="font-size:12px;" target="_blank">
                <i class="bi-plus-circle me-1"></i> Tambah Mata Kuliah Baru
            </a>
            @error('course_id') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium" style="font-size:14px;">Dosen Pengampu <span class="text-danger">*</span></label>
            <select name="lecturer_id" class="form-select form-control-custom @error('lecturer_id') is-invalid @enderror" required>
                <option value="">Pilih Dosen</option>
                @foreach($lecturers as $lecturer)
                    <option value="{{ $lecturer->id }}" {{ old('lecturer_id') == $lecturer->id ? 'selected' : '' }}>
                        {{ $lecturer->nama }}@if($lecturer->gelar), {{ $lecturer->gelar }}@endif
                    </option>
                @endforeach
            </select>
            <a href="{{ route('lecturers.index') }}" class="text-decoration-none" style="font-size:12px;" target="_blank">
                <i class="bi-plus-circle me-1"></i> Tambah Dosen Baru
            </a>
            @error('lecturer_id') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-medium" style="font-size:14px;">Semester</label>
                <select name="semester" class="form-select form-control-custom">
                    <option value="">Pilih Semester</option>
                    @for($i = 1; $i <= 14; $i++)
                        <option value="{{ $i }}" {{ old('semester', Auth::user()->semester) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-medium" style="font-size:14px;">Kelas</label>
                <select name="kelas" class="form-select form-control-custom">
                    <option value="">Pilih Kelas</option>
                    @foreach(['A', 'B', 'C', 'D', 'E'] as $k)
                        <option value="{{ $k }}" {{ old('kelas', Auth::user()->kelas) == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium" style="font-size:14px;">Deadline <span class="text-danger">*</span></label>
            <input type="date" name="deadline" class="form-control form-control-custom @error('deadline') is-invalid @enderror"
                   value="{{ old('deadline') }}" required>
            @error('deadline') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-medium" style="font-size:14px;">Deskripsi</label>
            <textarea name="deskripsi" class="form-control form-control-custom @error('deskripsi') is-invalid @enderror"
                      rows="3" placeholder="Deskripsi singkat proyek...">{{ old('deskripsi') }}</textarea>
            @error('deskripsi') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('projects.index') }}" class="btn-outline-custom text-decoration-none flex-grow-1 text-center">Batal</a>
            <button type="submit" class="btn-primary-custom flex-grow-1">
                <i class="bi-check-lg me-1"></i> Simpan Proyek
            </button>
        </div>
    </form>
</div>
@endsection
