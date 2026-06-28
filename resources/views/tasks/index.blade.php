@extends('layouts.app')
@section('title', 'Tugas Saya')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-heading mb-1">✅ Semua Tugas</h1>
        <p class="page-subtitle">{{ $countAll }} tugas ditemukan</p>
    </div>
    <a href="{{ route('tasks.create') }}" class="btn-primary-custom text-decoration-none">
        <i class="bi-plus-lg me-1"></i> Tugas Baru
    </a>
</div>

{{-- Tab Pills --}}
<div class="mb-4" style="background:var(--slate-100);padding:4px;border-radius:10px;display:inline-flex;flex-wrap:wrap;gap:2px;">
    <a href="{{ route('tasks.index') }}"
       class="text-decoration-none px-4 py-2 {{ !request('status') ? 'bg-white shadow-sm' : '' }}"
       style="border-radius:8px;font-size:14px;font-weight:500;color:{{ !request('status') ? 'var(--slate-900)' : 'var(--slate-500)' }};transition:all 0.15s;">
        Semua <span style="font-size:12px;opacity:0.6;">{{ $countAll }}</span>
    </a>
    <a href="{{ route('tasks.index', ['status' => 'belum_dimulai']) }}"
       class="text-decoration-none px-4 py-2 {{ request('status') == 'belum_dimulai' ? 'bg-white shadow-sm' : '' }}"
       style="border-radius:8px;font-size:14px;font-weight:500;color:{{ request('status') == 'belum_dimulai' ? 'var(--slate-900)' : 'var(--slate-500)' }};transition:all 0.15s;">
        Belum <span style="font-size:12px;opacity:0.6;">{{ $countBelum }}</span>
    </a>
    <a href="{{ route('tasks.index', ['status' => 'sedang_dikerjakan']) }}"
       class="text-decoration-none px-4 py-2 {{ request('status') == 'sedang_dikerjakan' ? 'bg-white shadow-sm' : '' }}"
       style="border-radius:8px;font-size:14px;font-weight:500;color:{{ request('status') == 'sedang_dikerjakan' ? 'var(--slate-900)' : 'var(--slate-500)' }};transition:all 0.15s;">
        Proses <span style="font-size:12px;opacity:0.6;">{{ $countProses }}</span>
    </a>
    <a href="{{ route('tasks.index', ['status' => 'menunggu_review']) }}"
       class="text-decoration-none px-4 py-2 {{ request('status') == 'menunggu_review' ? 'bg-white shadow-sm' : '' }}"
       style="border-radius:8px;font-size:14px;font-weight:500;color:{{ request('status') == 'menunggu_review' ? 'var(--slate-900)' : 'var(--slate-500)' }};transition:all 0.15s;">
        Review <span style="font-size:12px;opacity:0.6;">{{ $countReview }}</span>
    </a>
    <a href="{{ route('tasks.index', ['status' => 'selesai']) }}"
       class="text-decoration-none px-4 py-2 {{ request('status') == 'selesai' ? 'bg-white shadow-sm' : '' }}"
       style="border-radius:8px;font-size:14px;font-weight:500;color:{{ request('status') == 'selesai' ? 'var(--slate-900)' : 'var(--slate-500)' }};transition:all 0.15s;">
        Selesai <span style="font-size:12px;opacity:0.6;">{{ $countSelesai }}</span>
    </a>
</div>

{{-- Filter Bar --}}
<div class="card-clean p-3 mb-4">
    <form method="GET" class="row g-2 align-items-center">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0" style="border-radius:8px 0 0 8px;">
                    <i class="bi-search" style="color:var(--slate-400);"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari tugas..."
                       value="{{ request('search') }}" style="border-radius:0 8px 8px 0;border-color:#CBD5E1;padding:10px 14px;font-size:14px;">
            </div>
        </div>
        <div class="col-md-3">
            <select name="project_id" class="form-select" style="border-radius:8px;border-color:#CBD5E1;font-size:14px;padding:10px 14px;">
                <option value="">Semua Proyek</option>
                @foreach($projects as $proj)
                    <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>
                        {{ $proj->nama_proyek }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="sort" class="form-select" style="border-radius:8px;border-color:#CBD5E1;font-size:14px;padding:10px 14px;">
                <option value="deadline" {{ request('sort') == 'deadline' ? 'selected' : '' }}>Deadline</option>
                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn-outline-custom w-100">
                <i class="bi-funnel me-1"></i> Filter
            </button>
        </div>
    </form>
</div>

{{-- Task List (Card Style) --}}
@if($tasks->count() > 0)
    @foreach($tasks as $task)
    @php
        $statusClass = match($task->status) {
            'belum_dimulai' => ['dot' => '#94A3B8', 'badge' => 'badge-belum', 'text' => 'Belum'],
            'sedang_dikerjakan' => ['dot' => '#F59E0B', 'badge' => 'badge-proses', 'text' => 'Proses'],
            'menunggu_review' => ['dot' => '#2563EB', 'badge' => 'badge-review', 'text' => 'Review'],
            'selesai' => ['dot' => '#10B981', 'badge' => 'badge-selesai', 'text' => 'Selesai'],
        };
        $daysLeft = now()->startOfDay()->diffInDays($task->deadline, false);
    @endphp
    <a href="{{ route('projects.show', $task->project_id) }}" class="text-decoration-none d-block mb-2">
        <div class="card-clean" style="transition:all 0.15s;padding:16px 20px;">
            <div class="d-flex justify-content-between align-items-start">
                <div class="d-flex gap-3">
                    <span style="width:10px;height:10px;border-radius:50%;background:{{ $statusClass['dot'] }};margin-top:6px;flex-shrink:0;"></span>
                    <div>
                        <div style="font-size:16px;font-weight:600;color:var(--slate-800);">{{ $task->judul }}</div>
                        <div style="font-size:13px;color:var(--slate-500);margin-top:2px;">
                            {{ $task->project->course->nama_mk ?? '' }} —
                            <span style="color:var(--slate-700);">{{ $task->project->nama_proyek }}</span>
                            · PIC:
                            <strong style="color:var(--slate-700);">{{ $task->assignedUser->name ?? '—' }}</strong>
                        </div>
                        <div style="font-size:12px;margin-top:4px;color:{{ $daysLeft < 0 ? 'var(--danger)' : ($daysLeft <= 2 ? 'var(--warning)' : 'var(--slate-400)') }};">
                            <i class="bi-calendar3 me-1"></i>
                            Deadline: {{ $task->deadline->format('d M Y') }}
                            @if($task->status != 'selesai')
                                @if($daysLeft < 0) · 🔴 Terlambat {{ abs($daysLeft) }} hari
                                @elseif($daysLeft == 0) · 🟡 Hari ini
                                @elseif($daysLeft == 1) · 🟡 Besok
                                @else · 🟢 {{ $daysLeft }} hari lagi
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <span class="badge-status {{ $statusClass['badge'] }}">{{ $statusClass['text'] }}</span>
            </div>
        </div>
    </a>
    @endforeach
@else
    <div class="card-clean text-center py-5">
        <i class="bi-check2-square" style="font-size:48px;color:var(--slate-300);"></i>
        <p class="mt-3 text-muted">Tidak ada tugas di kategori ini.</p>
    </div>
@endif

@if($tasks->hasPages())
    <div class="mt-3">
        {{ $tasks->links() }}
    </div>
@endif
@endsection
