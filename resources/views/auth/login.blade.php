@extends('layouts.guest')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="auth-card">
            <div class="card-header-custom">
                <h2><i class="bi bi-box-arrow-in-right me-2"></i>Welcome Back</h2>
                <p>Sign in to continue to your dashboard</p>
            </div>
            <div class="card-body-custom">
                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <!-- Student ID -->
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Student ID</label>
                        <input 
                            id="student_id" 
                            type="text" 
                            class="form-control @error('student_id') is-invalid @enderror" 
                            name="student_id" 
                            value="{{ old('student_id') }}" 
                            required 
                            autofocus 
                            autocomplete="username"
                            placeholder="e.g., DWC001 or 2025-000123"
                        />
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            id="password" 
                            type="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="Enter your password"
                        />
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
                    <button type="submit" class="btn-primary-custom">
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
@endsection
