@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
<h1 class="page-heading mb-1">📈 Laporan & Ringkasan</h1>
<p class="page-subtitle mb-4">Ringkasan progres semua proyek dan tugas</p>

{{-- Overview Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card-clean text-center">
            <div style="font-size:28px;font-weight:800;color:var(--slate-900);">{{ $totalProjects }}</div>
            <small style="font-size:11px;text-transform:uppercase;color:var(--slate-500);letter-spacing:.5px;">Total Proyek</small>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card-clean text-center">
            <div style="font-size:28px;font-weight:800;color:var(--slate-900);">{{ $totalTasks }}</div>
            <small style="font-size:11px;text-transform:uppercase;color:var(--slate-500);letter-spacing:.5px;">Total Tugas</small>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card-clean text-center">
            <div style="font-size:28px;font-weight:800;color:#059669;">{{ $doneTasks }}</div>
            <small style="font-size:11px;text-transform:uppercase;color:var(--slate-500);letter-spacing:.5px;">Tugas Selesai</small>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card-clean text-center">
            <div style="font-size:28px;font-weight:800;color:var(--primary);">{{ $completionRate }}%</div>
            <small style="font-size:11px;text-transform:uppercase;color:var(--slate-500);letter-spacing:.5px;">Completion Rate</small>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Progress Per Proyek --}}
    <div class="col-lg-7">
        <div class="card-clean">
            <h6 class="fw-bold mb-3" style="font-size:16px;">📊 Progress Per Proyek</h6>
            @forelse($projectsProgress as $proj)
            @php
                $pct = $proj->tasks_count > 0 ? round(($proj->tasks_done_count / $proj->tasks_count) * 100) : 0;
                $barColor = $pct == 100 ? '#10B981' : ($pct > 50 ? '#F59E0B' : '#EF4444');
            @endphp
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <a href="{{ route('projects.show', $proj) }}" class="text-decoration-none" style="font-size:14px;font-weight:600;color:var(--slate-800);">
                        {{ $proj->nama_proyek }}
                    </a>
                    <span style="font-size:13px;color:var(--slate-500);">{{ $proj->tasks_done_count }}/{{ $proj->tasks_count }}</span>
                </div>
                <div class="progress-custom" style="height:10px;">
                    <div class="progress-bar" style="width:{{ $pct }}%;background:{{ $barColor }};"></div>
                </div>
                <small class="text-muted">{{ $proj->course->nama_mk ?? '' }}</small>
            </div>
            @empty
            <p class="text-muted" style="font-size:14px;">Belum ada proyek.</p>
            @endforelse
        </div>
    </div>

    {{-- Anggota Paling Aktif --}}
    <div class="col-lg-5">
        <div class="card-clean">
            <h6 class="fw-bold mb-3" style="font-size:16px;">👥 Anggota Paling Aktif</h6>
            @forelse($topMembers as $i => $member)
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="font-size:16px;font-weight:700;color:var(--slate-400);width:24px;text-align:center;">{{ $i + 1 }}</div>
                <div style="width:36px;height:36px;border-radius:50%;background:var(--primary-light);color:var(--primary);
                            display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;flex-shrink:0;">
                    {{ strtoupper(substr($member->name, 0, 1)) }}
                </div>
                <div class="flex-grow-1">
                    <div style="font-weight:600;font-size:14px;">{{ $member->name }}</div>
                    <div class="d-flex align-items-center gap-1" style="font-size:12px;">
                        <span style="color:#059669;">{{ $member->done_count }} selesai</span>
                        <span style="color:var(--slate-500);">dari {{ $member->total_count }} tugas</span>
                    </div>
                </div>
                @php $mpct = $member->total_count > 0 ? round(($member->done_count / $member->total_count) * 100) : 0; @endphp
                <span style="font-size:14px;font-weight:700;color:{{ $mpct >= 70 ? '#059669' : ($mpct >= 40 ? '#F59E0B' : '#EF4444') }};">
                    {{ $mpct }}%
                </span>
            </div>
            @empty
            <p class="text-muted" style="font-size:14px;">Belum ada data anggota.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Rekap per Mata Kuliah & Dosen --}}
<div class="card-clean p-0 overflow-hidden mt-4">
    <div class="p-3 border-bottom" style="border-color:var(--slate-100)!important;">
        <h6 class="fw-bold mb-0" style="font-size:16px;">📚 Rekap Per Mata Kuliah & Dosen</h6>
    </div>
    @if($recapPerCourse->count() > 0)
    <table class="table-modern mb-0">
        <thead>
            <tr>
                <th>Mata Kuliah</th>
                <th>Dosen</th>
                <th>Proyek</th>
                <th>Tugas</th>
                <th>Selesai</th>
                <th>Progress</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recapPerCourse as $recap)
            @php
                $rpct = $recap->tasks_count > 0 ? round(($recap->tasks_done_count / $recap->tasks_count) * 100) : 0;
            @endphp
            <tr>
                <td><strong>{{ $recap->course->nama_mk ?? '—' }}</strong></td>
                <td>{{ $recap->lecturer->nama ?? '—' }}</td>
                <td>{{ $recap->nama_proyek }}</td>
                <td>{{ $recap->tasks_count }}</td>
                <td><span style="color:#059669;font-weight:600;">{{ $recap->tasks_done_count }}</span></td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="progress-custom flex-grow-1" style="max-width:100px;">
                            <div class="progress-bar" style="width:{{ $rpct }}%;background:{{ $rpct == 100 ? '#10B981' : ($rpct > 50 ? '#F59E0B' : '#EF4444') }};"></div>
                        </div>
                        <small style="font-weight:600;">{{ $rpct }}%</small>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="text-center py-4 text-muted">Belum ada data.</div>
    @endif
</div>
@endsection
