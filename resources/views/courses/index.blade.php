@extends('layouts.app')
@section('title', 'Mata Kuliah')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-heading mb-1">📚 Mata Kuliah</h1>
        <p class="page-subtitle">Kelola data mata kuliah yang sedang ditempuh</p>
    </div>
    <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalCourse">
        <i class="bi-plus-lg me-1"></i> Tambah Mata Kuliah
    </button>
</div>

{{-- Search --}}
<div class="card-clean p-3 mb-4">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0" style="border-radius:8px 0 0 8px;">
                    <i class="bi-search" style="color:var(--slate-400);"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari mata kuliah..."
                       value="{{ request('search') }}" style="border-radius:0 8px 8px 0;border-color:#CBD5E1;padding:10px 14px;">
            </div>
        </div>
    </form>
</div>

{{-- Tabel --}}
<div class="card-clean p-0 overflow-hidden">
    @if($courses->count() > 0)
        <table class="table-modern mb-0">
            <thead>
                <tr>
                    <th>Kode MK</th>
                    <th>Nama Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Proyek</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                <tr>
                    <td><span class="badge-status badge-review">{{ $course->kode_mk }}</span></td>
                    <td><strong>{{ $course->nama_mk }}</strong></td>
                    <td>
                        @if($course->sks)
                            <span class="badge-status badge-belum">{{ $course->sks }} SKS</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($course->projects_count > 0)
                            <a href="{{ route('projects.index', ['course' => $course->id]) }}" class="text-decoration-none">
                                {{ $course->projects_count }} proyek
                            </a>
                        @else
                            <span class="text-muted">0</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-custom btn-sm-custom me-1"
                                onclick="editCourse({{ $course->id }}, '{{ $course->kode_mk }}', '{{ $course->nama_mk }}', {{ $course->sks ?? 'null' }})">
                            <i class="bi-pencil"></i>
                        </button>
                        <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus mata kuliah {{ $course->nama_mk }}?')">
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
        <div class="text-center py-5">
            <i class="bi-journal-bookmark-fill" style="font-size:48px;color:var(--slate-300);"></i>
            <p class="mt-3 text-muted">Belum ada mata kuliah. Tambahkan mata kuliah pertama kamu!</p>
        </div>
    @endif
</div>

{{-- Pagination --}}
@if($courses->hasPages())
    <div class="mt-3">
        {{ $courses->links() }}
    </div>
@endif

{{-- MODAL: Tambah / Edit --}}
<div class="modal fade" id="modalCourse" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:14px;">
            <form id="formCourse" method="POST" action="{{ route('courses.store') }}">
                @csrf
                <div id="methodField"></div>

                <div class="modal-header border-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold" id="modalTitle">Tambah Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:14px;">Kode MK <span class="text-danger">*</span></label>
                        <input type="text" name="kode_mk" id="inputKodeMk" class="form-control form-control-custom"
                               placeholder="e.g. IF4301" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:14px;">Nama Mata Kuliah <span class="text-danger">*</span></label>
                        <input type="text" name="nama_mk" id="inputNamaMk" class="form-control form-control-custom"
                               placeholder="e.g. Pemrograman Web Lanjut" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:14px;">SKS</label>
                        <input type="number" name="sks" id="inputSks" class="form-control form-control-custom"
                               placeholder="e.g. 3" min="1" max="6" style="max-width:100px;">
                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn-outline-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-primary-custom" id="btnSubmit">
                        <i class="bi-check-lg me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modalEl = document.getElementById('modalCourse');
    const form = document.getElementById('formCourse');
    const modalTitle = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');
    const inputKodeMk = document.getElementById('inputKodeMk');
    const inputNamaMk = document.getElementById('inputNamaMk');
    const inputSks = document.getElementById('inputSks');

    // Reset modal saat dibuka untuk tambah baru
    modalEl.addEventListener('show.bs.modal', function(e) {
        // Hanya reset jika tombol "Tambah" yang trigger
        if (e.relatedTarget && e.relatedTarget.getAttribute('data-bs-target')) {
            form.action = '{{ route('courses.store') }}';
            methodField.innerHTML = '';
            modalTitle.textContent = 'Tambah Mata Kuliah';
            inputKodeMk.value = '';
            inputNamaMk.value = '';
            inputSks.value = '';
            inputKodeMk.removeAttribute('readonly');
        }
    });

    function editCourse(id, kode, nama, sks) {
        form.action = '/courses/' + id;
        methodField.innerHTML = '@method("PUT")';
        modalTitle.textContent = 'Edit Mata Kuliah';
        inputKodeMk.value = kode;
        inputNamaMk.value = nama;
        inputSks.value = sks || '';
        inputKodeMk.setAttribute('readonly', true);

        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
</script>
@endpush
