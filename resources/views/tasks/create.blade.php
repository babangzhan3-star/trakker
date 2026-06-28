@extends('layouts.app')
@section('title', 'Tambah Tugas')

@section('content')
<a href="{{ $selectedProject ? route('projects.show', $selectedProject) : route('tasks.index') }}" class="text-decoration-none d-inline-block mb-3" style="font-size:14px;color:var(--slate-500);">
    <i class="bi-arrow-left me-1"></i> Kembali
</a>

<div class="card-clean mx-auto" style="max-width:600px;">
    <h1 class="page-heading mb-1">✅ Tambah Tugas Baru</h1>
    <p class="page-subtitle mb-4">Buat tugas untuk anggota kelompok</p>

    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-medium" style="font-size:14px;">Judul Tugas <span class="text-danger">*</span></label>
            <input type="text" name="judul" class="form-control form-control-custom @error('judul') is-invalid @enderror"
                   value="{{ old('judul') }}" placeholder="e.g. Membuat ERD Database" required>
            @error('judul') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium" style="font-size:14px;">Proyek <span class="text-danger">*</span></label>
            <select name="project_id" id="projectSelect" class="form-select form-control-custom @error('project_id') is-invalid @enderror" required>
                <option value="">Pilih Proyek</option>
                @foreach($projects as $proj)
                    <option value="{{ $proj->id }}" {{ old('project_id', $selectedProject->id ?? '') == $proj->id ? 'selected' : '' }}>
                        {{ $proj->nama_proyek }} ({{ $proj->course->nama_mk ?? '' }})
                    </option>
                @endforeach
            </select>
            @error('project_id') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium" style="font-size:14px;">Penanggung Jawab <span class="text-danger">*</span></label>
            <select name="assigned_to" class="form-select form-control-custom @error('assigned_to') is-invalid @enderror"
                    @if($members->isEmpty()) disabled @endif required>
                <option value="">
                    {{ $members->isEmpty() ? 'Pilih proyek terlebih dahulu' : 'Pilih Anggota' }}
                </option>
                @foreach($members as $member)
                    <option value="{{ $member->id }}" {{ old('assigned_to') == $member->id ? 'selected' : '' }}>
                        {{ $member->name }} {{ $member->pivot->role == 'ketua' ? '(Ketua)' : '' }}
                    </option>
                @endforeach
            </select>
            @error('assigned_to') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
        </div>

        {{-- Status Pills --}}
        <div class="mb-3">
            <label class="form-label fw-medium" style="font-size:14px;">Status</label>
            <div class="d-flex flex-wrap gap-2">
                @php $statuses = [
                    'belum_dimulai' => ['icon' => 'circle', 'text' => 'Belum Dimulai'],
                    'sedang_dikerjakan' => ['icon' => 'arrow-repeat', 'text' => 'Sedang Dikerjakan'],
                    'menunggu_review' => ['icon' => 'eye', 'text' => 'Menunggu Review'],
                    'selesai' => ['icon' => 'check-circle', 'text' => 'Selesai'],
                ]; @endphp
                @foreach($statuses as $val => $s)
                <label style="cursor:pointer;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:500;
                    border:1.5px solid {{ old('status', 'belum_dimulai') == $val ? 'var(--primary)' : 'var(--slate-200)' }};
                    background:{{ old('status', 'belum_dimulai') == $val ? 'var(--primary-light)' : '#fff' }};
                    color:{{ old('status', 'belum_dimulai') == $val ? 'var(--primary)' : 'var(--slate-500)' }};">
                    <input type="radio" name="status" value="{{ $val }}" {{ old('status', 'belum_dimulai') == $val ? 'checked' : '' }} hidden>
                    <i class="bi-{{ $s['icon'] }} me-1"></i> {{ $s['text'] }}
                </label>
                @endforeach
            </div>
            @error('status') <div class="text-danger" style="font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium" style="font-size:14px;">Deadline <span class="text-danger">*</span></label>
            <input type="date" name="deadline" class="form-control form-control-custom @error('deadline') is-invalid @enderror"
                   value="{{ old('deadline') }}" required>
            @error('deadline') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-medium" style="font-size:14px;">Deskripsi</label>
            <textarea name="deskripsi" class="form-control form-control-custom" rows="3"
                      placeholder="Detail tugas yang perlu dikerjakan...">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ $selectedProject ? route('projects.show', $selectedProject) : route('tasks.index') }}"
               class="btn-outline-custom text-decoration-none flex-grow-1 text-center">Batal</a>
            <button type="submit" class="btn-primary-custom flex-grow-1">
                <i class="bi-check-lg me-1"></i> Simpan Tugas
            </button>
        </div>
    </form>
</div>
@endsection
