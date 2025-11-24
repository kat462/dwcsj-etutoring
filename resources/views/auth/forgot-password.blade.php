@extends('layouts.guest')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="auth-card">
            <div class="card-header-custom">
                <h2><i class="bi bi-key me-2"></i>Forgot your password?</h2>
                <p>Enter your email address and we'll send you a password reset link.</p>
            </div>
            <div class="card-body-custom">
                @if (session('status'))
                    <div class="alert alert-success mb-4">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="your.email@example.com" />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn-primary-custom w-100">
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
