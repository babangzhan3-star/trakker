@extends('layouts.app')
@section('title', 'Edit Tugas')

@section('content')
<a href="{{ route('projects.show', $task->project_id) }}" class="text-decoration-none d-inline-block mb-3" style="font-size:14px;color:var(--slate-500);">
    <i class="bi-arrow-left me-1"></i> Kembali ke Proyek
</a>

<div class="card-clean mx-auto" style="max-width:600px;">
    <h1 class="page-heading mb-1">✏️ Edit Tugas</h1>
    <p class="page-subtitle mb-4">{{ $task->judul }}</p>

    <form action="{{ route('tasks.update', $task) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-medium" style="font-size:14px;">Judul Tugas <span class="text-danger">*</span></label>
            <input type="text" name="judul" class="form-control form-control-custom @error('judul') is-invalid @enderror"
                   value="{{ old('judul', $task->judul) }}" required>
            @error('judul') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium" style="font-size:14px;">Proyek <span class="text-danger">*</span></label>
            <select name="project_id" class="form-select form-control-custom @error('project_id') is-invalid @enderror" required>
                @foreach($projects as $proj)
                    <option value="{{ $proj->id }}" {{ old('project_id', $task->project_id) == $proj->id ? 'selected' : '' }}>
                        {{ $proj->nama_proyek }} ({{ $proj->course->nama_mk ?? '' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium" style="font-size:14px;">Penanggung Jawab <span class="text-danger">*</span></label>
            <select name="assigned_to" class="form-select form-control-custom @error('assigned_to') is-invalid @enderror" required>
                @foreach($members as $member)
                    <option value="{{ $member->id }}" {{ old('assigned_to', $task->assigned_to) == $member->id ? 'selected' : '' }}>
                        {{ $member->name }} {{ $member->pivot->role == 'ketua' ? '(Ketua)' : '' }}
                    </option>
                @endforeach
            </select>
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
                    border:1.5px solid {{ old('status', $task->status) == $val ? 'var(--primary)' : 'var(--slate-200)' }};
                    background:{{ old('status', $task->status) == $val ? 'var(--primary-light)' : '#fff' }};
                    color:{{ old('status', $task->status) == $val ? 'var(--primary)' : 'var(--slate-500)' }};">
                    <input type="radio" name="status" value="{{ $val }}" {{ old('status', $task->status) == $val ? 'checked' : '' }} hidden>
                    <i class="bi-{{ $s['icon'] }} me-1"></i> {{ $s['text'] }}
                </label>
                @endforeach
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium" style="font-size:14px;">Deadline <span class="text-danger">*</span></label>
            <input type="date" name="deadline" class="form-control form-control-custom @error('deadline') is-invalid @enderror"
                   value="{{ old('deadline', $task->deadline->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-4">
            <label class="form-label fw-medium" style="font-size:14px;">Deskripsi</label>
            <textarea name="deskripsi" class="form-control form-control-custom" rows="3"
                      placeholder="Detail tugas...">{{ old('deskripsi', $task->deskripsi) }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('projects.show', $task->project_id) }}" class="btn-outline-custom text-decoration-none flex-grow-1 text-center">Batal</a>
            <button type="submit" class="btn-primary-custom flex-grow-1">
                <i class="bi-check-lg me-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
