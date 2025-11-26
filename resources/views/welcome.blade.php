@extends('layouts.guest')

@section('content')
<div class="welcome-hero position-relative" style="background: linear-gradient(120deg, #f0fdf4 60%, #e0e7ff 100%); min-height: 100vh;">
    <div class="row align-items-center justify-content-center" style="min-height: 80vh;">
        <!-- Hero Content -->
        <div class="col-lg-6 mb-5 mb-lg-0 d-flex align-items-center" style="z-index:2;">
            <div class="hero-content w-100">
                <h1 class="display-4 fw-bold mb-4">
                    <span style="color: #111; font-size:3.2rem;">Welcome to</span><br>
                    <span style="color: var(--primary-color);">DWCSJ E-Tutoring System</span>
                </h1>
                <p class="lead mb-4" style="color: #6b7280; font-size: 1.25rem;">
                    Empowering students through collaborative learning. Connect with peer tutors,
                    share knowledge, and achieve academic excellence together.
                </p>
                
                <div class="d-flex flex-row justify-content-end gap-3 mb-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-lg btn-primary-custom">
                            <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-lg btn-primary-custom shadow-sm px-4 py-2 d-flex align-items-center justify-content-center gap-2" style="min-width:160px; transition:all 0.2s;">
                            <span class="d-flex align-items-center justify-content-center"><i class="bi bi-person-plus me-2"></i><span>Get Started</span></span>
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-lg btn-outline-secondary px-4 py-2 d-flex align-items-center justify-content-center gap-2" style="min-width:140px; border-width:2px; transition:all 0.2s;">
                            <span class="d-flex align-items-center justify-content-center"><i class="bi bi-box-arrow-in-right me-2"></i><span>Sign In</span></span>
                        </a>
                    @endauth
                </div>

                <!-- Quick Stats -->
                <div class="row mt-5 g-3 justify-content-center">
                    <div class="col-6 col-md-4">
                        <div class="stat-box shadow-sm border border-success-subtle bg-white">
                            <h3 class="fw-bold mb-1" style="color: var(--primary-color);">500+</h3>
                            <p class="text-muted small mb-0">Students</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="stat-box shadow-sm border border-warning-subtle bg-white">
                            <h3 class="fw-bold mb-1" style="color: var(--secondary-color);">50+</h3>
                            <p class="text-muted small mb-0">Tutors</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="stat-box shadow-sm border border-orange-200 bg-white">
                            <h3 class="fw-bold mb-1" style="color: var(--accent-color);">1000+</h3>
                            <p class="text-muted small mb-0">Sessions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hero Image / Features -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center" style="z-index:2;">
            <div class="features-grid mt-4 mt-lg-0">
                <!-- Feature 1 -->
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(135deg, var(--primary-color), #15803d);">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Find Expert Tutors</h5>
                    <p class="text-muted small mb-0">Connect with experienced peer tutors across various subjects and education levels.</p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(135deg, var(--secondary-color), #ca9a0d);">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Easy Scheduling</h5>
                    <p class="text-muted small mb-0">Book tutoring sessions at your convenience with flexible scheduling options.</p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(135deg, var(--accent-color), #ea580c);">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Rate & Review</h5>
                    <p class="text-muted small mb-0">Share your experience and help others find the best tutors through ratings and feedback.</p>
                </div>

                <!-- Feature 4 -->
                <div class="feature-card">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Track Progress</h5>
                    <p class="text-muted small mb-0">Monitor your learning journey and see your academic improvement over time.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.welcome-hero {
    padding: 4.5rem 0 3rem 0;
    min-height: 100vh;
    background: none;
}
.hero-content {
    animation: fadeInUp 0.8s ease;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
.stat-box {
    text-align: center;
    padding: 1.25rem 1rem;
    border-radius: 0.75rem;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    margin-bottom: 0.5rem;
    border: 1.5px solid #e0e7ef;
}
.features-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    width: 100%;
    max-width: 420px;
}
.feature-card {
    background: white;
    padding: 1.5rem 1.2rem;
    border-radius: 1rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 1px solid rgba(22, 163, 74, 0.1);
    min-height: 140px;
}
.feature-card:hover {
    transform: translateY(-5px) scale(1.03);
    box-shadow: 0 8px 25px rgba(0,0,0,0.13);
}
.feature-icon {
    width: 50px;
    height: 50px;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
}
@media (max-width: 992px) {
    .features-grid { grid-template-columns: 1fr; max-width: 100%; }
    .welcome-hero { padding: 2rem 0; }
}
</style>
@endsection
