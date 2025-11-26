
@extends('layouts.guest')

@section('content')
<div class="row justify-content-center align-items-center min-vh-100 py-4">
    <div class="col-12 col-md-8 col-lg-5">
        <div class="auth-card shadow-lg rounded-4 border-0 p-0 overflow-hidden bg-white">
            <div class="card-header-custom text-center py-4 px-4 bg-primary text-white">
                <h2 class="fw-bold mb-1"><i class="bi bi-envelope-check me-2"></i>Verify Your Email</h2>
                <p class="mb-0">Thanks for signing up! Please verify your email address by clicking the link we just emailed to you. If you didn't receive the email, we will gladly send you another.</p>
            </div>
            <div class="card-body-custom p-4">
                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success mb-4">
                        A new verification link has been sent to the email address you provided during registration.
                    </div>
                @endif
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 mt-4">
                    <form method="POST" action="{{ route('verification.send') }}" class="w-100">
                        @csrf
                        <button type="submit" class="btn-primary-custom w-100 py-2 fs-5 rounded-3">
                            <i class="bi bi-arrow-repeat me-2"></i>Resend Verification Email
                        </button>
                    </form>
                    <form method="POST" action="{{ route('logout') }}" class="w-100">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary w-100 py-2 fs-5 rounded-3">
                            <i class="bi bi-box-arrow-right me-2"></i>Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
