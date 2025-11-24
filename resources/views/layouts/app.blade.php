<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DWCSJ Peer Tutoring') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        
        <!-- Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                --primary-color: #16a34a;
                --secondary-color: #eab308;
                --accent-color: #f97316;
                --dark-color: #1e293b;
                --light-bg: #f8fafc;
            }
            
            body {
                font-family: 'Inter', sans-serif;
                background-color: var(--light-bg);
            }
            
            .navbar-custom {
                background: linear-gradient(135deg, var(--primary-color) 0%, #15803d 100%);
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }

            .navbar-logo-badge {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background: transparent; /* hide white circle */
                display: inline-flex;
                align-items: center;
                justify-content: center;
                box-shadow: none;
                border: none;
                backdrop-filter: blur(2px);
                margin-right: 0.75rem;
            }

            .navbar-logo {
                width: 32px;
                height: 32px;
                object-fit: contain;
                filter: drop-shadow(0 1px 1.5px rgba(0,0,0,0.25));
            }
            
            .sidebar {
                background: white;
                min-height: calc(100vh - 56px);
                border-right: 1px solid #e2e8f0;
                padding: 1.5rem 0;
            }
            
            .sidebar-link {
                display: flex;
                align-items: center;
                padding: 0.65rem 1.25rem;
                color: #0f172a; /* stronger contrast for readability */
                text-decoration: none;
                transition: all 0.15s ease;
                border-left: 3px solid transparent;
                font-size: 0.95rem;
            }
            
            .sidebar-link:hover {
                background-color: #f1f5f9;
                color: var(--primary-color);
                border-left-color: var(--primary-color);
            }
            
            .sidebar-link.active {
                background-color: #eff6ff;
                color: var(--primary-color);
                border-left-color: var(--primary-color);
                font-weight: 600;
            }
            
            .sidebar-link i {
                margin-right: 0.5rem;
                font-size: 0.95rem; /* reduce icon size */
                width: 1.05rem;
                text-align: center;
                color: inherit;
            }

            /* Sidebar avatar and typography */
            .sidebar-avatar {
                width: 44px;
                height: 44px;
                object-fit: cover;
                border-radius: 8px;
                display: inline-block;
                box-shadow: 0 1px 2px rgba(0,0,0,0.06);
            }

            .sidebar-user-name {
                font-weight: 600;
                font-size: 0.95rem;
                color: #0f172a;
                line-height: 1.1;
            }

            .sidebar-user-role {
                font-size: 0.8rem;
                color: #64748b;
                margin-top: 0.1rem;
            }

            .sidebar-link {
                display: flex;
                align-items: center;
                gap: 0.6rem;
                padding: 0.65rem 1.25rem;
                color: #0f172a;
                text-decoration: none;
                transition: all 0.15s ease;
                border-left: 3px solid transparent;
                font-size: 0.95rem;
            }

            .sidebar-link-text {
                display: inline-block;
                vertical-align: middle;
            }
            
            .card-modern {
                border: none;
                border-radius: 12px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                transition: transform 0.2s, box-shadow 0.2s;
            }
            
            .card-modern:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
            
            .btn-primary-custom {
                background: var(--primary-color);
                border: none;
                padding: 0.6rem 1.5rem;
                border-radius: 8px;
                font-weight: 500;
                transition: all 0.2s;
            }
            
            .btn-primary-custom:hover {
                background: #1d4ed8;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            }
            
            .badge-custom {
                padding: 0.4rem 0.8rem;
                border-radius: 6px;
                font-weight: 500;
                font-size: 0.85rem;
            }
            
            .page-header {
                background: white;
                padding: 1rem; /* compacted */
                border-radius: 10px;
                margin-bottom: 1rem;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            }
            
                /* Main Content Watermark */
                .main-content-wrapper {
                    position: relative;
                    min-height: calc(100vh - 56px - 60px);
                }

                .main-content-wrapper::before {
                    content: '';
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: 600px; /* smaller watermark */
                    height: 600px;
                    background-image: url('/images/DWCSJ_Seal.png');
                    background-size: contain;
                    background-repeat: no-repeat;
                    background-position: center;
                    opacity: 0.02; /* reduced opacity for less visual weight */
                    pointer-events: none;
                    z-index: 0;
                }
            
                .content-layer {
                    position: relative;
                    z-index: 1;
                }
            
                /* Footer Styles */
                .app-footer {
                    background: #1f2937;
                    color: white;
                    padding: 1.5rem 0;
                    margin-top: auto;
                }
            
                .app-footer a {
                    color: rgba(255, 255, 255, 0.8);
                    text-decoration: none;
                    transition: color 0.3s ease;
                }
            
                .app-footer a:hover {
                    color: var(--secondary-color);
                }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
            <div class="container-fluid px-4">
                @auth
                    @php
                        $brandHref = auth()->user()->isAdmin()
                            ? route('admin.dashboard')
                            : (auth()->user()->isTutor() ? route('tutor.dashboard') : route('student.dashboard'));
                    @endphp
                @else
                    @php $brandHref = url('/'); @endphp
                @endauth

                <a class="navbar-brand d-flex align-items-center" href="{{ $brandHref }}">
                    <span class="navbar-logo-badge">
                        <img src="{{ asset('images/DWCSJ_Seal.png') }}" alt="DWCSJ" class="navbar-logo">
                    </span>
                    <span class="fw-bold">DWCSJ Peer Tutoring</span>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Mobile sidebar toggle (visible on small screens) -->
                <button id="mobileSidebarToggle" class="btn btn-outline-light d-md-none ms-2" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
                
                <!-- No account controls in header; all moved to sidebar -->
            </div>
        </nav>

        <div class="container-fluid main-content-wrapper">
            <div class="row">
                <!-- Sidebar -->
                @auth
                <div class="col-md-3 col-lg-2 px-0 sidebar d-none d-md-block">
                    <div class="position-sticky">
                        @include('components.sidebar')
                    </div>
                </div>
                @endauth

                    <!-- Mobile backdrop for sidebar overlay -->
                    <div id="sidebarBackdrop" class="sidebar-backdrop d-md-none"></div>

                <!-- Main Content -->
                    <main class="col-md-9 col-lg-10 px-md-4 py-3 content-layer">
                    @yield('content')
                </main>
            </div>
        </div>
        
            <!-- Footer -->
            <footer class="app-footer">
                <div class="container-fluid px-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="mb-0" style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">
                                &copy; {{ date('Y') }} Divine Word College of San Jose. All rights reserved.
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <a href="#" class="me-3">About</a>
                            <a href="#" class="me-3">Contact</a>
                            <a href="#" class="me-3">Help</a>
                            <a href="#">Privacy</a>
                        </div>
                    </div>
                </div>
            </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
