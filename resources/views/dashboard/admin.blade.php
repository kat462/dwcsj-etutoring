@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="mb-0 d-flex align-items-center">
        <i class="bi bi-shield-check text-primary me-3" style="font-size: 2rem;"></i>
        <span>Admin Dashboard</span>
    </h1>
    <p class="text-muted mb-0 mt-2">Welcome back, {{ auth()->user()->name }}</p>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card card-modern text-white" style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Total Users</h6>
                        <h2 class="mb-0">{{ \App\Models\User::count() }}</h2>
                    </div>
                    <i class="bi bi-people" style="font-size: 3rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card card-modern text-white" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Tutors</h6>
                        <h2 class="mb-0">{{ \App\Models\User::where('role', 'tutor')->count() }}</h2>
                    </div>
                    <i class="bi bi-person-workspace" style="font-size: 3rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card card-modern text-white" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Students</h6>
                        <h2 class="mb-0">{{ \App\Models\User::where('role', 'tutee')->count() }}</h2>
                    </div>
                    <i class="bi bi-mortarboard" style="font-size: 3rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card card-modern text-white" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Subjects</h6>
                        <h2 class="mb-0">{{ \App\Models\Subject::count() }}</h2>
                    </div>
                    <i class="bi bi-book" style="font-size: 3rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-md-8">
        <div class="card card-modern">
            <div class="card-body">
                <h5 class="card-title mb-4"><i class="bi bi-clock-history text-primary me-2"></i>Recent Activity</h5>
                <p class="text-muted">System overview and statistics will appear here.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-modern">
            <div class="card-body">
                <h5 class="card-title mb-4"><i class="bi bi-gear text-primary me-2"></i>Quick Actions</h5>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary"><i class="bi bi-person-plus me-2"></i>Add User</a>
                    <a href="#" class="btn btn-outline-primary"><i class="bi bi-book-half me-2"></i>Manage Subjects</a>
                    <a href="#" class="btn btn-outline-primary"><i class="bi bi-file-text me-2"></i>View Reports</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
