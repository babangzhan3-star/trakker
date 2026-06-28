@extends('layouts.app')
@section('title', $project->nama_proyek)

@section('content')
{{-- Breadcrumb --}}
<a href="{{ route('projects.index') }}" class="text-decoration-none d-inline-block mb-3" style="font-size:14px;color:var(--slate-500);">
    <i class="bi-arrow-left me-1"></i> Kembali ke Daftar Proyek
</a>

{{-- Flash Error --}}
@if(session('error'))
    <div class="alert alert-danger border-0" style="background:#FEE2E2;color:#991B1B;border-radius:10px;font-size:14px;">
        <i class="bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
    </div>
@endif

{{-- HEADER PROYEK --}}
<div class="card-clean mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            @php
                $statusBadge = match($project->status) {
                    'aktif' => ['bg' => '#D1FAE5', 'color' => '#059669', 'text' => 'Aktif'],
                    'selesai' => ['bg' => '#DBEAFE', 'color' => '#2563EB', 'text' => 'Selesai'],
                    'ditunda' => ['bg' => '#F1F5F9', 'color' => '#64748B', 'text' => 'Ditunda'],
                };
            @endphp
            <h1 class="page-heading mb-2">{{ $project->nama_proyek }}
                <span class="badge-status ms-2" style="font-size:12px;background:{{ $statusBadge['bg'] }};color:{{ $statusBadge['color'] }};">
                    {{ $statusBadge['text'] }}
                </span>
            </h1>
            <div class="d-flex flex-wrap gap-3" style="font-size:14px;color:var(--slate-500);">
                <span><i class="bi-journal-bookmark-fill me-1"></i> {{ $project->course->nama_mk ?? '—' }}</span>
                <span><i class="bi-person me-1"></i> {{ $project->lecturer->nama ?? '—' }}
                    @if($project->lecturer && $project->lecturer->gelar), {{ $project->lecturer->gelar }}@endif
                </span>
                @if($project->semester)
                    <span>Semester {{ $project->semester }}</span>
                @endif
                @if($project->kelas)
                    <span>Kelas {{ $project->kelas }}</span>
                @endif
                <span style="color:{{ now()->startOfDay()->diffInDays($project->deadline, false) < 0 ? 'var(--danger)' : 'var(--slate-500)' }};">
                    <i class="bi-calendar3 me-1"></i> Deadline: {{ $project->deadline->format('d M Y') }}
                </span>
            </div>
            @if($project->deskripsi)
                <p class="mt-2 mb-0" style="font-size:14px;color:var(--slate-500);">{{ $project->deskripsi }}</p>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('projects.edit', $project) }}" class="btn-outline-custom text-decoration-none">
                <i class="bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="btn-primary-custom text-decoration-none">
                <i class="bi-plus-lg me-1"></i> Tambah Tugas
            </a>
            <div class="dropdown">
                <button class="btn-outline-custom" data-bs-toggle="dropdown">
                    <i class="bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" style="border-radius:10px;font-size:13px;">
                    <li>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST"
                              onsubmit="return confirm('Hapus proyek ini? Semua tugas dan data akan hilang.')">
                            @csrf @method('DELETE')
                            <button class="dropdown-item py-2 text-danger">
                                <i class="bi-trash me-2"></i> Hapus Proyek
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- ROW: Progress + Anggota --}}
<div class="row g-4 mb-4">
    {{-- Progress --}}
    <div class="col-lg-7">
        <div class="card-clean">
            <h6 class="fw-bold mb-3" style="font-size:16px;">📊 Progress Proyek</h6>

            <div class="d-flex justify-content-between mb-2">
                <span style="font-size:14px;">{{ $progressPercent }}% selesai</span>
                <span style="font-size:14px;color:var(--slate-500);">{{ $doneTasks }}/{{ $totalTasks }} tugas</span>
            </div>
            @php
                $barColor = $progressPercent > 70 ? '#10B981' : ($progressPercent > 40 ? '#F59E0B' : '#EF4444');
            @endphp
            <div class="progress-custom mb-3" style="height:12px;">
                <div class="progress-bar" style="width:{{ $progressPercent }}%;background:{{ $barColor }};"></div>
            </div>

            <div class="d-flex flex-wrap gap-3">
                <span style="font-size:13px;"><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#64748B;margin-right:6px;"></span> Belum: {{ $statusCounts['belum_dimulai'] }}</span>
                <span style="font-size:13px;"><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#F59E0B;margin-right:6px;"></span> Proses: {{ $statusCounts['sedang_dikerjakan'] }}</span>
                <span style="font-size:13px;"><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#2563EB;margin-right:6px;"></span> Review: {{ $statusCounts['menunggu_review'] }}</span>
                <span style="font-size:13px;"><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#059669;margin-right:6px;"></span> Selesai: {{ $statusCounts['selesai'] }}</span>
            </div>
        </div>
    </div>

    {{-- Anggota --}}
    <div class="col-lg-5">
        <div class="card-clean">
            <h6 class="fw-bold mb-3" style="font-size:16px;">👥 Anggota ({{ $project->members->count() }})</h6>

            @foreach($project->members as $member)
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-3">
                    @php
                        $colors = ['#EFF6FF', '#F0FDF4', '#FEF3C7', '#FCE7F3', '#EDE9FE'];
                        $textColors = ['#2563EB', '#059669', '#D97706', '#DB2777', '#7C3AED'];
                        $idx = $member->id % count($colors);
                    @endphp
                    <div style="width:40px;height:40px;border-radius:50%;background:{{ $colors[$idx] }};color:{{ $textColors[$idx] }};
                                display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;flex-shrink:0;">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:14px;">
                            {{ $member->name }}
                            @if($member->pivot->role === 'ketua') <span style="font-size:12px;">👑</span> @endif
                        </div>
                        <div style="font-size:12px;color:var(--slate-500);">
                            @php
                                $memberTasks = $tasks->where('assigned_to', $member->id);
                                $memberDone = $memberTasks->where('status', 'selesai')->count();
                                $memberTotal = $memberTasks->count();
                            @endphp
                            {{ $memberDone }}/{{ $memberTotal }} tugas selesai
                        </div>
                    </div>
                </div>

                @if($member->pivot->role !== 'ketua' && Auth::id() == $project->created_by)
                <form action="{{ route('projects.members.remove', [$project, $member]) }}" method="POST"
                      onsubmit="return confirm('Keluarkan {{ $member->name }} dari proyek?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm text-muted" style="font-size:18px;" title="Hapus anggota">
                        <i class="bi-x-lg"></i>
                    </button>
                </form>
                @endif
            </div>
            @endforeach

            {{-- Tambah Anggota (hanya untuk ketua) --}}
            @if(Auth::id() == $project->created_by && $availableUsers->count() > 0)
            <form action="{{ route('projects.members.add', $project) }}" method="POST" class="mt-3 pt-3 border-top">
                @csrf
                <label class="form-label fw-medium" style="font-size:13px;">Tambah Anggota</label>
                <div class="input-group">
                    <select name="user_id" class="form-select" style="border-radius:8px 0 0 8px;border-color:#CBD5E1;font-size:13px;">
                        <option value="">Pilih user...</option>
                        @foreach($availableUsers as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->nim }})</option>
                        @endforeach
                    </select>
                    <button class="btn-primary-custom" style="border-radius:0 8px 8px 0;font-size:13px;padding:8px 14px;">
                        <i class="bi-plus-lg"></i>
                    </button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>

{{-- DAFTAR TUGAS --}}
<div class="card-clean p-0 overflow-hidden">
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom" style="border-color:var(--slate-100)!important;">
        <h6 class="fw-bold mb-0" style="font-size:16px;">✅ Daftar Tugas ({{ $totalTasks }})</h6>
        <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="btn-primary-custom btn-sm-custom text-decoration-none" style="padding:6px 14px;">
            <i class="bi-plus-lg me-1"></i> Tambah
        </a>
    </div>

    @if($tasks->count() > 0)
    <table class="table-modern mb-0">
        <thead>
            <tr>
                <th>Tugas</th>
                <th>PIC</th>
                <th>Status</th>
                <th>Deadline</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr>
                <td>
                    <strong>{{ $task->judul }}</strong>
                    @if($task->deskripsi)
                        <br><small class="text-muted">{{ Str::limit($task->deskripsi, 60) }}</small>
                    @endif
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        @php $uIdx = $task->assignedUser->id ?? 0 % count($colors ?? [5]); @endphp
                        <div style="width:28px;height:28px;border-radius:50%;background:var(--primary-light);color:var(--primary);
                                    display:flex;align-items:center;justify-content:center;font-weight:600;font-size:11px;flex-shrink:0;">
                            {{ strtoupper(substr($task->assignedUser->name ?? '?', 0, 1)) }}
                        </div>
                        <span style="font-size:13px;">{{ $task->assignedUser->name ?? '—' }}</span>
                    </div>
                </td>
                <td>
                    @php
                        $statusClass = match($task->status) {
                            'belum_dimulai' => 'badge-belum',
                            'sedang_dikerjakan' => 'badge-proses',
                            'menunggu_review' => 'badge-review',
                            'selesai' => 'badge-selesai',
                        };
                        $statusText = match($task->status) {
                            'belum_dimulai' => 'Belum',
                            'sedang_dikerjakan' => 'Proses',
                            'menunggu_review' => 'Review',
                            'selesai' => 'Selesai',
                        };
                    @endphp
                    <span class="badge-status {{ $statusClass }}">{{ $statusText }}</span>
                </td>
                <td>
                    @php $td = now()->startOfDay()->diffInDays($task->deadline, false); @endphp
                    <span style="font-size:13px;color:{{ $td < 0 ? 'var(--danger)' : ($td <= 2 ? 'var(--warning)' : 'var(--slate-500)') }};">
                        {{ $task->deadline->format('d M') }}
                        @if($td < 0) 🔴 @elseif($td <= 2) 🟡 @endif
                    </span>
                </td>
                <td class="text-end">
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-custom btn-sm-custom me-1">
                        <i class="bi-pencil"></i>
                    </a>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Hapus tugas ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-custom btn-sm-custom text-danger">
                            <i class="bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="text-center py-4">
        <i class="bi-check2-square" style="font-size:36px;color:var(--slate-300);"></i>
        <p class="text-muted mt-2 mb-0" style="font-size:14px;">Belum ada tugas. Tambahkan tugas pertama!</p>
    </div>
    @endif
</div>

{{-- Aktivitas Proyek --}}
@if($activities->count() > 0)
<div class="card-clean mt-4">
    <h6 class="fw-bold mb-3" style="font-size:16px;">🕐 Aktivitas Proyek</h6>
    @foreach($activities as $act)
    <div class="d-flex mb-2" style="font-size:13px;">
        <div class="position-relative me-3">
            <div style="width:8px;height:8px;border-radius:50%;background:var(--primary);margin-top:5px;"></div>
        </div>
        <div>
            <strong>{{ $act->user->name }}</strong>
            <span class="text-muted">{{ $act->deskripsi }}</span>
            <br><small style="color:var(--slate-400);">{{ $act->created_at->diffForHumans() }}</small>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
