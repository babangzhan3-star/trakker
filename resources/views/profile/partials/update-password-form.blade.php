<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="mb-3">
        <label class="form-label fw-medium" style="font-size:14px;">Password Saat Ini</label>
        <input type="password" name="current_password" class="form-control form-control-custom @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
        @error('current_password', 'updatePassword') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-medium" style="font-size:14px;">Password Baru</label>
        <input type="password" name="password" class="form-control form-control-custom @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
        @error('password', 'updatePassword') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-medium" style="font-size:14px;">Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation" class="form-control form-control-custom @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
        @error('password_confirmation', 'updatePassword') <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn-primary-custom">
        <i class="bi-shield-check me-1"></i> Ubah Password
    </button>

    @if (session('status') === 'password-updated')
        <span class="ms-2" style="font-size:13px;color:#059669;">Password berhasil diubah!</span>
    @endif
</form>
