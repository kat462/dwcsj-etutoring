@extends('layouts.guest')

@section('content')

<div class="row justify-content-center align-items-center min-vh-100 py-4">
    <div class="col-12 col-md-8 col-lg-5">
        <div class="auth-card shadow-lg rounded-4 border-0 p-0 overflow-hidden bg-white">
            <div class="card-header-custom text-center py-4 px-4 bg-primary text-white">
                <h2 class="fw-bold mb-1"><i class="bi bi-key me-2"></i>Forgot your password?</h2>
                <p class="mb-0">Enter your email address and we'll send you a password reset link.</p>
            </div>
            <div class="card-body-custom p-4">
                @if (session('status'))
                    <div class="alert alert-success mb-4">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('password.email') }}" novalidate autocomplete="off">
                    @csrf
                    <div class="form-floating mb-3">
                        <input id="email" type="email" class="form-control rounded-3 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="your.email@example.com" />
                        <label for="email">Email Address</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn-primary-custom w-100 py-2 fs-5 rounded-3 mt-2">
                        <i class="bi bi-envelope me-2"></i>Email Password Reset Link
                    </button>
                </form>
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-link"><i class="bi bi-arrow-left me-1"></i>Back to login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
