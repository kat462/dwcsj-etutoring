
@extends('layouts.guest')

@section('content')
<div class="row justify-content-center align-items-center min-vh-100 py-4">
    <div class="col-12 col-md-8 col-lg-5">
        <div class="auth-card shadow-lg rounded-4 border-0 p-0 overflow-hidden bg-white">
            <div class="card-header-custom text-center py-4 px-4 bg-primary text-white">
                <h2 class="fw-bold mb-1"><i class="bi bi-shield-lock me-2"></i>Confirm Password</h2>
                <p class="mb-0">This is a secure area of the application. Please confirm your password before continuing.</p>
            </div>
            <div class="card-body-custom p-4">
                <form method="POST" action="{{ route('password.confirm') }}" novalidate autocomplete="off">
                    @csrf
                    <div class="form-floating mb-3 position-relative">
                        <input id="password" type="password" class="form-control rounded-3 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password" />
                        <label for="password">Password</label>
                        <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2" style="z-index:2;" onclick="togglePassword('password')" tabindex="-1"><i class="bi bi-eye"></i></button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn-primary-custom w-100 py-2 fs-5 rounded-3 mt-2">
                        <i class="bi bi-shield-lock me-2"></i>Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}
</script>
@endsection
