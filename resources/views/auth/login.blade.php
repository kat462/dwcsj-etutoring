@extends('layouts.guest')

@section('content')

<div class="row justify-content-center align-items-center min-vh-100 py-4">
    <div class="col-12 col-md-8 col-lg-5">
        <div class="auth-card shadow-lg rounded-4 border-0 p-0 overflow-hidden bg-white">
            <div class="card-header-custom text-center py-4 px-4 bg-primary text-white">
                <h2 class="fw-bold mb-1"><i class="bi bi-box-arrow-in-right me-2"></i>Welcome Back</h2>
                <p class="mb-0">Sign in to continue to your dashboard</p>
            </div>
            <div class="card-body-custom p-4">
                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" novalidate autocomplete="off">
                    @csrf
                    <!-- Student ID -->
                    <div class="form-floating mb-3">
                        <input 
                            id="student_id" 
                            type="text" 
                            class="form-control rounded-3 @error('student_id') is-invalid @enderror" 
                            name="student_id" 
                            value="{{ old('student_id') }}" 
                            required 
                            autofocus 
                            autocomplete="username"
                            placeholder="e.g., DWC001 or 2025-000123"
                        />
                        <label for="student_id">Student ID</label>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-floating mb-3 position-relative">
                        <input 
                            id="password" 
                            type="password" 
                            class="form-control rounded-3 @error('password') is-invalid @enderror" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="Enter your password"
                        />
                        <label for="password">Password</label>
                        <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2" style="z-index:2;" onclick="togglePassword('password')" tabindex="-1"><i class="bi bi-eye"></i></button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-3 form-check">
                        <input 
                            type="checkbox" 
                            class="form-check-input" 
                            id="remember_me" 
                            name="remember"
                        />
                        <label class="form-check-label" for="remember_me">
                            Remember me
                        </label>
                    </div>

                    <!-- Forgot Password Link -->
                    @if (Route::has('password.request'))
                        <div class="mb-4">
                            <a href="{{ route('password.request') }}" class="text-link">
                                <i class="bi bi-key me-1"></i>Forgot your password?
                            </a>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary-custom w-100 py-2 fs-5 rounded-3 mt-2">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Log In
                    </button>

                    <!-- Register Link -->
                    @if (Route::has('register'))
                        <div class="text-center mt-4">
                            <p class="mb-0">Don't have an account? 
                                <a href="{{ route('register') }}" class="text-link">
                                    Sign up now
                                </a>
                            </p>
                        </div>
                    @endif
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
