<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="mb-3">
        <label class="form-label fw-medium" style="font-size:14px;">Nama</label>
        <input type="text" name="name" class="form-control form-control-custom @error('name') is-invalid @enderror"
               value="{{ old('name', $user->name) }}" required>
        @error('name') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-medium" style="font-size:14px;">Email</label>
        <input type="email" name="email" class="form-control form-control-custom @error('email') is-invalid @enderror"
               value="{{ old('email', $user->email) }}" required>
        @error('email') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-medium" style="font-size:14px;">NIM</label>
        <input type="text" name="nim" class="form-control form-control-custom @error('nim') is-invalid @enderror"
               value="{{ old('nim', $user->nim) }}">
        @error('nim') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="form-label fw-medium" style="font-size:14px;">Semester</label>
            <select name="semester" class="form-select form-control-custom">
                <option value="">—</option>
                @for($i = 1; $i <= 14; $i++)
                    <option value="{{ $i }}" {{ old('semester', $user->semester) == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-medium" style="font-size:14px;">Kelas</label>
            <select name="kelas" class="form-select form-control-custom">
                <option value="">—</option>
                @foreach(['A','B','C','D','E'] as $k)
                    <option value="{{ $k }}" {{ old('kelas', $user->kelas) == $k ? 'selected' : '' }}>{{ $k }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <button type="submit" class="btn-primary-custom">
        <i class="bi-check-lg me-1"></i> Simpan Perubahan
    </button>

    @if (session('status') === 'profile-updated')
        <span class="ms-2" style="font-size:13px;color:#059669;">
            <i class="bi-check-circle me-1"></i> Profil berhasil diperbarui!
        </span>
    @endif
</form>
