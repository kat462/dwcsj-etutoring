<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DWCSJ Peer Tutoring') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        
        <style>
            :root {
                --primary-color: #16a34a;
                --secondary-color: #eab308;
                --accent-color: #f97316;
            }
            
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            html {
                font-size: 17px;
            }
            @media (max-width: 768px) {
                html { font-size: 15.5px; }
            }
            body {
                font-family: 'Inter', sans-serif;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                background: linear-gradient(135deg, rgba(22, 163, 74, 0.05) 0%, rgba(234, 179, 8, 0.05) 100%);
                font-size: 1rem;
            }
            
            /* Header Styles */
            .site-header {
                background: linear-gradient(135deg, var(--primary-color) 0%, #15803d 100%);
                color: white;
                padding: 1rem 0;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            
            .site-header .logo-section {
                display: flex;
                align-items: center;
                gap: 1rem;
            }
            
            .logo-badge {
                width: 64px;
                height: 64px;
                border-radius: 50%;
                background: transparent; /* hide white circle */
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: none;
                border: none;
                backdrop-filter: blur(2px); /* subtle blur behind logo */
            }

            .site-header .logo-img {
                width: 50px;
                height: 50px;
                object-fit: contain;
                filter: drop-shadow(0 1px 1.5px rgba(0,0,0,0.25)); /* keep logo legible */
            }
            
            .site-header h1 {
                font-size: 2.1rem;
                font-weight: 700;
                margin: 0;
                line-height: 1.15;
                letter-spacing: 0.5px;
            }
            
            .site-header .subtitle {
                font-size: 1.1rem;
                opacity: 0.92;
                font-weight: 500;
                margin-top: 0.15rem;
                letter-spacing: 0.1px;
                color: #e0e7ef;
                text-shadow: 0 1px 2px rgba(0,0,0,0.08);
            }
            
            .header-nav {
                display: flex;
                gap: 1rem;
                align-items: center;
            }
            
            .header-nav a {
                color: white;
                text-decoration: none;
                padding: 0.5rem 1rem;
                border-radius: 0.375rem;
                transition: all 0.3s ease;
                font-weight: 500;
            }
            
            .header-nav a:hover {
                background: rgba(255, 255, 255, 0.1);
            }
            
            .header-nav .btn-register {
                background: var(--secondary-color);
                color: #1f2937;
            }
            
            .header-nav .btn-register:hover {
                background: #ca9a0d;
                transform: translateY(-2px);
            }
            
            /* Main Content */
            .main-content {
                flex: 1;
                display: flex;
                align-items: center;
                padding: 2rem 0;
                position: relative;
                overflow: hidden;
            }
            
            /* DWCSJ Seal Background */
            .main-content::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 600px;
                height: 600px;
                background-image: url('/images/DWCSJ_Seal.png');
                background-size: contain;
                background-repeat: no-repeat;
                background-position: center;
                opacity: 0.04; /* low opacity watermark */
                pointer-events: none;
                z-index: 0;
            }
            
            .content-wrapper {
                position: relative;
                z-index: 1;
            }
            
            /* Card Styles */
            .auth-card {
                background: white;
                border-radius: 1rem;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                border: 1px solid rgba(22, 163, 74, 0.1);
            }
            
            .card-header-custom {
                background: linear-gradient(135deg, var(--primary-color) 0%, #15803d 100%);
                color: white;
                padding: 2rem;
                text-align: center;
            }
            
            .card-header-custom h2 {
                font-size: 1.75rem;
                font-weight: 700;
                margin: 0;
            }
            
            .card-header-custom p {
                margin: 0.5rem 0 0 0;
                opacity: 0.9;
                font-size: 0.95rem;
            }
            
            .card-body-custom {
                padding: 2.5rem;
            }
            
            /* Form Styles */
            .form-label {
                font-weight: 600;
                color: #374151;
                margin-bottom: 0.5rem;
            }
            
            .form-control,
            .form-select {
                border: 2px solid #e5e7eb;
                border-radius: 0.5rem;
                padding: 0.85rem 1.1rem;
                font-size: 1.05rem;
                transition: all 0.3s ease;
            }
            
            .form-control:focus,
            .form-select:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
            }
            
            .btn-primary-custom {
                background: linear-gradient(135deg, var(--primary-color) 0%, #15803d 100%);
                border: none;
                color: white;
                padding: 0.95rem 2.1rem;
                border-radius: 0.5rem;
                font-weight: 600;
                font-size: 1.08rem;
                transition: all 0.3s ease;
                width: 100%;
            }
            
            .btn-primary-custom:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 20px rgba(22, 163, 74, 0.3);
            }
            
            .btn-secondary-custom {
                background: transparent;
                border: 2px solid var(--primary-color);
                color: var(--primary-color);
                padding: 0.95rem 2.1rem;
                border-radius: 0.5rem;
                font-weight: 600;
                font-size: 1.08rem;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-block;
                text-align: center;
            }
            
            .btn-secondary-custom:hover {
                background: var(--primary-color);
                color: white;
            }
            
            .text-link {
                color: var(--primary-color);
                text-decoration: none;
                font-weight: 500;
            }
            
            .text-link:hover {
                color: #15803d;
                text-decoration: underline;
            }
            
            /* Footer Styles */
            .site-footer {
                background: #1f2937;
                color: white;
                padding: 2rem 0 1rem 0;
                margin-top: auto;
            }
            
            .footer-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 1rem;
                margin-bottom: 1rem;
            }
            
            .footer-links {
                display: flex;
                gap: 1.5rem;
            }
            
            .footer-links a {
                color: rgba(255, 255, 255, 0.8);
                text-decoration: none;
                transition: color 0.3s ease;
            }
            
            .footer-links a:hover {
                color: var(--secondary-color);
            }
            
            .footer-bottom {
                text-align: center;
                padding-top: 1rem;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                color: rgba(255, 255, 255, 0.6);
                font-size: 0.875rem;
            }
            
            .social-links {
                display: flex;
                gap: 1rem;
            }
            
            .social-links a {
                color: white;
                font-size: 1.25rem;
                transition: all 0.3s ease;
            }
            
            .social-links a:hover {
                color: var(--secondary-color);
                transform: translateY(-3px);
            }
            
            /* Alert Styles */
            .alert {
                border-radius: 0.5rem;
                border: none;
                padding: 1rem 1.25rem;
            }
            
            .alert-success {
                background: #d1fae5;
                color: #065f46;
            }
            
            .alert-danger {
                background: #fee2e2;
                color: #991b1b;
            }
            
            /* Responsive */
            @media (max-width: 768px) {
                .site-header h1 {
                    font-size: 1.25rem;
                }
                
                .logo-badge { width: 56px; height: 56px; }
                .site-header .logo-img { width: 44px; height: 44px; }
                
                .header-nav {
                    flex-direction: column;
                    gap: 0.5rem;
                }
                
                .card-body-custom {
                    padding: 1.5rem;
                }
                
                .footer-content {
                    flex-direction: column;
                    text-align: center;
                }
                
                .footer-links {
                    flex-direction: column;
                    gap: 0.5rem;
                }
            }
        </style>
    </head>
    <body>
        <!-- Header -->
        <header class="site-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="logo-section">
                            <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none text-white">
                                <div class="logo-badge">
                                    <img src="{{ asset('images/DWCSJ_Seal.png') }}" alt="DWCSJ Logo" class="logo-img">
                                </div>
                                <div>
                                    <h1 class="mb-0">DWCSJ Peer Tutoring</h1>
                                    <p class="subtitle mb-0">Divine Word College of San Jose</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="header-nav justify-content-md-end">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Log in
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn-register">
                                            <i class="bi bi-person-plus me-2"></i>Register
                                        </a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="container content-wrapper">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="site-footer">
            <div class="container">
                <div class="footer-content">
                    <div>
                        <h5 class="mb-2">DWCSJ Peer Tutoring System</h5>
                        <p class="mb-0" style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">
                            Empowering students through collaborative learning
                        </p>
                    </div>
                    <div class="footer-links">
                        <a href="#about" data-bs-toggle="modal" data-bs-target="#aboutModal">About Us</a>
                        <a href="#">Contact</a>
                        <a href="#">Help</a>
                        <a href="#">Privacy Policy</a>
                                <!-- About Us Modal -->
                                <div class="modal fade" id="aboutModal" tabindex="-1" aria-labelledby="aboutModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content" style="padding: 2rem 2.5rem;">
                                            <div class="modal-header flex-column align-items-center border-0 pb-0">
                                                <h5 class="modal-title w-100 text-center fw-bold" id="aboutModalLabel" style="color: var(--primary-color);">About Us</h5>
                                                <div style="width: 60px; height: 4px; background: linear-gradient(90deg, var(--primary-color) 0%, #15803d 100%); border-radius: 2px; margin: 0.5rem auto 0.5rem auto;"></div>
                                                <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body pt-0">
                                                <div class="px-2 px-md-4">
                                                <h6 class="fw-bold mb-2" style="color: var(--primary-color);">About This Project</h6>
                                                <p class="mb-4" style="color: #6b7280;">The DWCSJ Peer Tutoring System is a capstone project developed by BSIT students of Divine Word College of San Jose. It aims to empower students through collaborative learning, providing a platform for peer-to-peer academic support and growth.</p>
                                                <h6 class="fw-bold mb-3" style="color: var(--primary-color);">Meet the Developers</h6>
                                                <div class="row g-4 mb-2">
                                                    <div class="col-md-4 text-center">
                                                        <img src="{{ asset('images/developers/kathleen.png') }}" alt="Kathleen" class="rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid var(--primary-color);">
                                                        <div class="fw-semibold">Kathleen</div>
                                                        <div class="text-muted small">Full Stack Developer</div>
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <img src="{{ asset('images/developers/genaline.png') }}" alt="Gen" class="rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid var(--secondary-color);">
                                                        <div class="fw-semibold">Gen</div>
                                                        <div class="text-muted small">Documentation &amp; ERD</div>
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <img src="{{ asset('images/developers/juvylaine.png') }}" alt="Juvy" class="rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid var(--accent-color);">
                                                        <div class="fw-semibold">Juvy</div>
                                                        <div class="text-muted small">Database Design &amp; System Flowcharts</div>
                                                    </div>
                                                </div>
                                                <div class="mt-4">
                                                    <div class="fw-bold mb-1">Advisers</div>
                                                    <div class="fw-bold mb-1" style="color: var(--primary-color);">Advisers</div>
                                                    <div class="text-muted small" style="color: #6b7280 !important;">Ma'am Lorena P. Florita<br>Sir Peter B. Serqui&ntilde;a</div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    </div>
                    <div class="social-links">
                        <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" title="Twitter"><i class="bi bi-twitter"></i></a>
                        <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p class="mb-0">&copy; {{ date('Y') }} Divine Word College of San Jose. All rights reserved.<br>
                        Built by <a href="https://github.com/kat462/peer-tutoring-system#credits" target="_blank" style="text-decoration: underline; color: #fff; font-weight: 500;">DWCSJ BSIT Capstone Team</a>
                    </p>
                </div>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
