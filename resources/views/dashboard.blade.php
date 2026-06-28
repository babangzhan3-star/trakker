@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
{{-- Top Bar --}}
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-heading mb-1">
            Selamat datang, {{ explode(' ', Auth::user()->name)[0] }}! 👋
        </h1>
        <p class="page-subtitle">
            @if(Auth::user()->semester)
                Semester {{ Auth::user()->semester }} •
            @endif
            {{ $activeProjects->count() }} Proyek Aktif
        </p>
    </div>
</div>

<div class="row g-4">
    {{-- Kiri (60%) --}}
    <div class="col-lg-7">
        {{-- Tugas Minggu Ini --}}
        <div class="card-clean">
            <h6 class="fw-bold mb-3" style="font-size:16px;">🔥 Tugas yang Harus Diselesaikan</h6>

            @if($urgentTasks->count() > 0)
                @foreach($urgentTasks as $task)
                <div class="d-flex align-items-center py-2 border-bottom" style="border-color:var(--slate-100)!important;">
                    @php
                        $daysLeft = now()->startOfDay()->diffInDays($task->deadline, false);
                        $dotColor = $daysLeft < 0 ? 'var(--danger)' : ($daysLeft <= 2 ? 'var(--warning)' : 'var(--success)');
                    @endphp
                    <span style="width:8px;height:8px;border-radius:50%;background:{{ $dotColor }};flex-shrink:0;margin-right:12px;"></span>
                    <div class="flex-grow-1">
                        <span style="font-weight:600;font-size:14px;">{{ $task->judul }}</span>
                        <span class="text-muted ms-2" style="font-size:12px;">— {{ $task->project->nama_proyek ?? '' }}</span>
                    </div>
                    <small class="text-muted ms-2" style="white-space:nowrap;">
                        @if($daysLeft < 0) 🔴 Terlambat {{ abs($daysLeft) }} hr
                        @elseif($daysLeft == 0) 🟡 Hari ini
                        @elseif($daysLeft == 1) 🟡 Besok
                        @else 🟢 {{ $daysLeft }} hari lagi
                        @endif
                    </small>
                </div>
                @endforeach
            @else
                <p class="text-muted" style="font-size:14px;">Tidak ada tugas mendesak minggu ini. 🎉</p>
            @endif
        </div>

        {{-- Proyek Aktif --}}
        <div class="card-clean">
            <h6 class="fw-bold mb-3" style="font-size:16px;">📋 Proyek Aktif</h6>

            @if($activeProjects->count() > 0)
                @foreach($activeProjects as $proj)
                @php
                    $total = $proj->tasks_count;
                    $done = $proj->tasks_done_count;
                    $pct = $total > 0 ? round(($done / $total) * 100) : 0;
                    $barColor = $pct > 70 ? '#10B981' : ($pct > 40 ? '#F59E0B' : '#EF4444');
                @endphp
                <a href="{{ route('projects.show', $proj->id) }}" class="text-decoration-none d-block mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span style="font-weight:600;font-size:14px;color:var(--slate-800);">{{ $proj->nama_proyek }}</span>
                        <small class="text-muted">{{ $done }}/{{ $total }}</small>
                    </div>
                    <div class="progress-custom">
                        <div class="progress-bar" style="width:{{ $pct }}%;background:{{ $barColor }};"></div>
                    </div>
                    <small class="text-muted" style="font-size:12px;">{{ $proj->course->nama_mk ?? '' }} • Deadline {{ $proj->deadline->format('d M Y') }}</small>
                </a>
                @endforeach
            @else
                <p class="text-muted" style="font-size:14px;">Belum ada proyek aktif.</p>
            @endif
        </div>
    </div>

    {{-- Kanan (40%) --}}
    <div class="col-lg-5">
        {{-- Aktivitas Terbaru --}}
        <div class="card-clean">
            <h6 class="fw-bold mb-3" style="font-size:16px;">🕐 Aktivitas Terbaru</h6>

            @if($activities->count() > 0)
                @foreach($activities as $act)
                <div class="d-flex mb-3" style="font-size:13px;">
                    <div class="position-relative me-3">
                        <div style="width:8px;height:8px;border-radius:50%;background:var(--primary);margin-top:6px;"></div>
                    </div>
                    <div>
                        <strong>{{ $act->user->name }}</strong>
                        <span class="text-muted">{{ $act->deskripsi }}</span>
                        @if($act->project)
                            <br><small class="text-muted">di <strong>{{ $act->project->nama_proyek }}</strong></small>
                        @endif
                        <br><small style="color:var(--slate-400);">{{ $act->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                @endforeach
            @else
                <p class="text-muted" style="font-size:14px;">Belum ada aktivitas.</p>
            @endif
        </div>

        {{-- Quick Stats --}}
        <div class="card-clean">
            <h6 class="fw-bold mb-3" style="font-size:16px;">📊 Ringkasan</h6>
            <div class="d-flex justify-content-around text-center">
                <div>
                    <div style="font-size:24px;font-weight:700;color:var(--slate-900);">{{ $activeProjects->count() }}</div>
                    <small style="font-size:11px;text-transform:uppercase;color:var(--slate-500);letter-spacing:.5px;">Proyek</small>
                </div>
                <div>
                    <div style="font-size:24px;font-weight:700;color:var(--slate-900);">{{ $totalTasks }}</div>
                    <small style="font-size:11px;text-transform:uppercase;color:var(--slate-500);letter-spacing:.5px;">Tugas</small>
                </div>
                <div>
                    <div style="font-size:24px;font-weight:700;color:var(--slate-900);">{{ $completion }}%</div>
                    <small style="font-size:11px;text-transform:uppercase;color:var(--slate-500);letter-spacing:.5px;">Selesai</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
