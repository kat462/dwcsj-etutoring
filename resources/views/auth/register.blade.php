@extends('layouts.guest')

@section('content')

@section('content')
<div class="row justify-content-center align-items-center min-vh-100 py-4">
    <div class="col-12 col-md-8 col-lg-5">
        <div class="auth-card shadow-lg rounded-4 border-0 p-0 overflow-hidden bg-white">
            <div class="card-header-custom text-center py-4 px-4 bg-primary text-white">
                <h2 class="fw-bold mb-1"><i class="bi bi-person-plus me-2"></i>Create Account</h2>
                <p class="mb-0">Sign up to access peer tutoring</p>
            </div>
            <div class="card-body-custom p-4">
                <form method="POST" action="{{ route('register') }}" novalidate autocomplete="off">
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
                            placeholder="e.g., DWC001 or 2025-000123"
                        />
                        <label for="student_id">Student ID</label>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Name -->
                    <div class="form-floating mb-3">
                        <input 
                            id="name" 
                            type="text" 
                            class="form-control rounded-3 @error('name') is-invalid @enderror" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            placeholder="Enter your name"
                        />
                        <label for="name">Full Name</label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Email -->
                    <div class="form-floating mb-3">
                        <input 
                            id="email" 
                            type="email" 
                            class="form-control rounded-3 @error('email') is-invalid @enderror" 
                            name="email" 
                            value="{{ old('email') }}" 
                            placeholder="Enter your email"
                        />
                        <label for="email">Email Address</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Education Level -->
                    <div class="form-floating mb-3">
                        @php use App\Helpers\EducationLevel; @endphp
                        <select 
                            id="education_level" 
                            name="education_level" 
                            class="form-select rounded-3 @error('education_level') is-invalid @enderror" 
                            required
                        >
                            <option value="">Select level</option>
                            @foreach(EducationLevel::options() as $key => $label)
                                <option value="{{ $key }}" {{ old('education_level') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <label for="education_level">Education Level</label>
                        @error('education_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Role -->
                    <div class="form-floating mb-3">
                        <select 
                            id="role" 
                            name="role" 
                            class="form-select rounded-3"
                            required
                        >
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select role</option>
                            <option value="tutee" {{ old('role') == 'tutee' ? 'selected' : '' }}>Tutee &mdash; Book a tutor</option>
                            <option value="tutor" {{ old('role') == 'tutor' ? 'selected' : '' }}>Tutor &mdash; Help other students</option>
                        </select>
                        <label for="role">Role</label>
                    </div>
                    <!-- Password -->
                    <div class="form-floating mb-3 position-relative">
                        <input 
                            id="password" 
                            type="password" 
                            class="form-control rounded-3 @error('password') is-invalid @enderror" 
                            name="password" 
                            required 
                            placeholder="Create a password"
                        />
                        <label for="password">Password</label>
                        <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2" style="z-index:2;" onclick="togglePassword('password')" tabindex="-1"><i class="bi bi-eye"></i></button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Confirm Password -->
                    <div class="form-floating mb-3 position-relative">
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            class="form-control rounded-3" 
                            name="password_confirmation" 
                            required 
                            placeholder="Re-enter your password"
                        />
                        <label for="password_confirmation">Confirm Password</label>
                        <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2" style="z-index:2;" onclick="togglePassword('password_confirmation')" tabindex="-1"><i class="bi bi-eye"></i></button>
                    </div>
                    <button type="submit" class="btn-primary-custom w-100 py-2 fs-5 rounded-3 mt-2">
                        <i class="bi bi-person-plus me-2"></i>Register
                    </button>
                    <div class="text-center mt-4">
                        <p class="mb-0">Already have an account? 
                            <a href="{{ route('login') }}" class="text-link">
                                Sign in
                            </a>
                        </p>
                    </div>
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
