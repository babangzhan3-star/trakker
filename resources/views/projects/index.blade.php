@extends('layouts.app')
@section('title', 'Proyek Saya')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-heading mb-1">📁 Proyek Saya</h1>
        <p class="page-subtitle">{{ $projects->total() }} proyek ditemukan</p>
    </div>
    <a href="{{ route('projects.create') }}" class="btn-primary-custom text-decoration-none">
        <i class="bi-plus-lg me-1"></i> Proyek Baru
    </a>
</div>

{{-- Filter --}}
<div class="card-clean p-3 mb-4">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0" style="border-radius:8px 0 0 8px;">
                    <i class="bi-search" style="color:var(--slate-400);"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0"
                       placeholder="Cari proyek atau mata kuliah..."
                       value="{{ request('search') }}" style="border-radius:0 8px 8px 0;border-color:#CBD5E1;padding:10px 14px;font-size:14px;">
            </div>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select" style="border-radius:8px;border-color:#CBD5E1;font-size:14px;padding:10px 14px;">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="ditunda" {{ request('status') == 'ditunda' ? 'selected' : '' }}>Ditunda</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn-outline-custom w-100">
                <i class="bi-funnel me-1"></i> Filter
            </button>
        </div>
        @if(request()->anyFilled(['search', 'status', 'course', 'lecturer']))
        <div class="col-md-2">
            <a href="{{ route('projects.index') }}" class="btn btn-sm text-muted">
                <i class="bi-x-lg me-1"></i> Reset
            </a>
        </div>
        @endif
    </form>
</div>

{{-- Grid Proyek --}}
@if($projects->count() > 0)
    <div class="row g-3">
        @foreach($projects as $proj)
        @php
            $total = $proj->tasks_count;
            $done = $proj->tasks_done_count;
            $pct = $total > 0 ? round(($done / $total) * 100) : 0;
            $barColor = $pct > 70 ? '#10B981' : ($pct > 40 ? '#F59E0B' : '#EF4444');
            $deadlineDiff = now()->startOfDay()->diffInDays($proj->deadline, false);
            $deadlineColor = $deadlineDiff < 0 ? 'var(--danger)' : ($deadlineDiff <= 3 ? 'var(--warning)' : 'var(--success)');
            $statusBadge = match($proj->status) {
                'aktif' => ['bg' => '#D1FAE5', 'color' => '#059669', 'text' => 'Aktif'],
                'selesai' => ['bg' => '#DBEAFE', 'color' => '#2563EB', 'text' => 'Selesai'],
                'ditunda' => ['bg' => '#F1F5F9', 'color' => '#64748B', 'text' => 'Ditunda'],
            };
        @endphp
        <div class="col-lg-6">
            <a href="{{ route('projects.show', $proj) }}" class="text-decoration-none">
                <div class="card-clean position-relative" style="transition:all 0.2s;cursor:pointer;">
                    {{-- Status Badge --}}
                    <span class="badge-status position-absolute" style="top:16px;right:16px;
                        background:{{ $statusBadge['bg'] }};color:{{ $statusBadge['color'] }};">
                        {{ $statusBadge['text'] }}
                    </span>

                    <h5 class="fw-bold mb-1" style="font-size:18px;color:var(--slate-800);">
                        {{ $proj->nama_proyek }}
                    </h5>
                    <p class="mb-3" style="font-size:14px;color:var(--slate-500);">
                        {{ $proj->course->nama_mk ?? '—' }}
                    </p>

                    <div class="d-flex gap-3 mb-3" style="font-size:13px;color:var(--slate-500);">
                        <span><i class="bi-person me-1"></i> {{ $proj->lecturer->nama ?? '—' }}</span>
                        <span><i class="bi-people me-1"></i> {{ $proj->members_count }} anggota</span>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small style="font-size:12px;color:var(--slate-500);">{{ $done }}/{{ $total }} tugas selesai</small>
                        <small style="font-size:12px;font-weight:600;color:{{ $barColor }};">{{ $pct }}%</small>
                    </div>
                    <div class="progress-custom mb-2">
                        <div class="progress-bar" style="width:{{ $pct }}%;background:{{ $barColor }};"></div>
                    </div>

                    <div style="font-size:12px;color:{{ $deadlineColor }};">
                        <i class="bi-calendar3 me-1"></i>
                        Deadline: {{ $proj->deadline->format('d M Y') }}
                        @if($proj->status == 'aktif')
                            @if($deadlineDiff < 0)
                                · 🔴 Terlambat {{ abs($deadlineDiff) }} hari
                            @elseif($deadlineDiff <= 3)
                                · 🟡 {{ $deadlineDiff }} hari lagi
                            @else
                                · 🟢 {{ $deadlineDiff }} hari lagi
                            @endif
                        @endif
                    </div>

                    {{-- Hover overlay --}}
                    <div class="position-absolute bottom-0 end-0 m-3" style="font-size:13px;color:var(--primary);font-weight:500;">
                        Lihat Detail <i class="bi-arrow-right ms-1"></i>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
@else
    <div class="card-clean text-center py-5">
        <i class="bi-folder2" style="font-size:48px;color:var(--slate-300);"></i>
        <p class="mt-3 text-muted">Belum ada proyek.</p>
        <a href="{{ route('projects.create') }}" class="btn-primary-custom text-decoration-none">
            <i class="bi-plus-lg me-1"></i> Buat Proyek Pertama
        </a>
    </div>
@endif

{{-- Pagination --}}
@if($projects->hasPages())
    <div class="mt-3">
        {{ $projects->links() }}
    </div>
@endif
@endsection
