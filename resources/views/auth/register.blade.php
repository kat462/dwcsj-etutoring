@extends('layouts.guest')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="auth-card">
            <div class="card-header-custom">
                <h2><i class="bi bi-person-plus me-2"></i>Create Account</h2>
                <p>Join DWCSJ Peer Tutoring Community</p>
            </div>
            <div class="card-body-custom">
                <form method="POST" action="{{ route('register') }}">
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
                            autocomplete="username"
                            placeholder="e.g., DWC001 or 2025-000123"
                        />
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input 
                            id="name" 
                            type="text" 
                            class="form-control @error('name') is-invalid @enderror" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus 
                            autocomplete="name"
                            placeholder="Enter your full name"
                        />
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Address (optional) -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address (optional)</label>
                        <input 
                            id="email" 
                            type="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            name="email" 
                            value="{{ old('email') }}" 
                            autocomplete="email"
                            placeholder="your.email@example.com"
                        />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Education Level -->
                    <div class="mb-3">
                        <label for="education_level" class="form-label">Education Level</label>
                        <select 
                            id="education_level" 
                            name="education_level" 
                            class="form-select @error('education_level') is-invalid @enderror" 
                            required
                        >
                            <option value="">-- Select Education Level --</option>
                            <option value="kindergarten" {{ old('education_level') == 'kindergarten' ? 'selected' : '' }}>Kindergarten</option>
                            <option value="elementary" {{ old('education_level') == 'elementary' ? 'selected' : '' }}>Elementary</option>
                            <option value="junior_high" {{ old('education_level') == 'junior_high' ? 'selected' : '' }}>Junior High School</option>
                            <option value="senior_high" {{ old('education_level') == 'senior_high' ? 'selected' : '' }}>Senior High School</option>
                            <option value="college" {{ old('education_level') == 'college' ? 'selected' : '' }}>College</option>
                            <option value="other" {{ old('education_level') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('education_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Default role as tutee (hidden) -->
                    <input type="hidden" name="role" value="tutee" />

                    <!-- Password -->
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input 
                                id="password" 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                name="password" 
                                required 
                                autocomplete="new-password"
                                placeholder="Create a strong password"
                            />
                            <button type="button" class="btn btn-outline-secondary" tabindex="-1" onclick="togglePassword('password')">
                                <i class="bi bi-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4 position-relative">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                class="form-control" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                placeholder="Re-enter your password"
                            />
                            <button type="button" class="btn btn-outline-secondary" tabindex="-1" onclick="togglePassword('password_confirmation')">
                                <i class="bi bi-eye" id="togglePasswordIconConfirm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-person-plus me-2"></i>Create Account
                    </button>

                    <!-- Login Link -->
                    <div class="text-center mt-4">
                        <p class="mb-0">Already have an account? 
                            <a href="{{ route('login') }}" class="text-link">
                                Log in here
                            </a>
                        </p>
                    </div>
                </form>
                            <script>
                            function togglePassword(fieldId) {
                                const input = document.getElementById(fieldId);
                                const icon = fieldId === 'password' ? document.getElementById('togglePasswordIcon') : document.getElementById('togglePasswordIconConfirm');
                                if (input.type === 'password') {
                                    input.type = 'text';
                                    icon.classList.remove('bi-eye');
                                    icon.classList.add('bi-eye-slash');
                                } else {
                                    input.type = 'password';
                                    icon.classList.remove('bi-eye-slash');
                                    icon.classList.add('bi-eye');
                                }
                            }
                            </script>
            </div>
        </div>
    </div>
</div>
@endsection
