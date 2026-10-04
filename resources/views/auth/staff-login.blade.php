@extends('layouts.app')

@section('title', 'Clinical Staff & Admin Portal - DocOp Healthcare')
@section('body-class', 'auth-page-wrapper')

@push('styles')
<style>
  .staff-role-switcher-wrapper {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 5px;
    margin-bottom: 1.5rem;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.04);
  }
  .staff-segmented-tabs {
    display: flex !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    list-style: none !important;
  }
  .staff-segmented-tabs .nav-item {
    flex: 1 1 0 !important;
    text-align: center;
  }
  .staff-segmented-tabs .nav-link {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 100% !important;
    padding: 0.65rem 0.5rem !important;
    font-size: 0.9rem !important;
    font-weight: 600 !important;
    color: #64748b !important;
    border-radius: 10px !important;
    background: transparent !important;
    border: 1px solid transparent !important;
    transition: all 0.2s ease-in-out !important;
    text-decoration: none !important;
  }
  .staff-segmented-tabs .nav-link:hover {
    color: #0f172a !important;
    background: rgba(255, 255, 255, 0.6) !important;
  }
  .staff-segmented-tabs .nav-link.active {
    background: #ffffff !important;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08) !important;
    border-color: rgba(226, 232, 240, 0.8) !important;
  }
  .staff-segmented-tabs .nav-link.role-doctor-tab.active {
    color: #059669 !important;
    border-bottom: 2px solid #059669 !important;
  }
  .staff-segmented-tabs .nav-link.role-admin-tab.active {
    color: #2563eb !important;
    border-bottom: 2px solid #2563eb !important;
  }
  .staff-segmented-tabs .nav-link.role-pharm-tab.active {
    color: #d97706 !important;
    border-bottom: 2px solid #d97706 !important;
  }
</style>
@endpush

@section('navbar')
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top modern-navbar">
    <div class="container">
      <a class="navbar-brand" href="{{ route('home') }}">
        <span class="brand-icon-box" style="background: linear-gradient(135deg, #0ea5e9, #6366f1);"><i class="fa fa-shield"></i></span>
        <span>DocOp <span style="font-weight: 400; opacity: 0.85; font-size: 0.9em;">Staff Console</span></span>
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarStaff" aria-controls="navbarStaff" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarStaff">
        <ul class="navbar-nav ml-auto align-items-lg-center">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('services') }}"><i class="fa fa-stethoscope"></i> Services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('contact') }}"><i class="fa fa-envelope-o"></i> Contact</a>
          </li>
          <li class="nav-item ml-lg-2 mt-2 mt-lg-0">
            <button type="button" class="btn-theme-toggle" onclick="toggleTheme()" title="Switch Light/Dark Mode">
              <i class="fa fa-moon-o"></i>
            </button>
          </li>
          <li class="nav-item ml-lg-2 mt-2 mt-lg-0">
            <a class="nav-link nav-btn-logout" href="{{ route('home') }}" style="background: rgba(255,255,255,0.1) !important; color: #e2e8f0 !important; border-color: rgba(255,255,255,0.2) !important;">
              <i class="fa fa-user"></i> Patient Portal
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
@endsection

@section('content')
  <div class="auth-glow-orb auth-glow-orb-1"></div>
  <div class="auth-glow-orb auth-glow-orb-2"></div>

  <div class="container d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 70px); padding-top: 110px; padding-bottom: 60px; position: relative; z-index: 2;">
    <div class="auth-card" style="max-width: 520px; width: 100%;">
      
      <!-- Card Header -->
      <div class="text-center mb-4">
        <div style="width: 58px; height: 58px; border-radius: 18px; background: linear-gradient(135deg, #1e1b4b, #312e81, #0284c7); color: white; display: inline-flex; align-items: center; justify-content: center; font-size: 1.6rem; box-shadow: 0 8px 20px rgba(49,46,129,0.3); margin-bottom: 1rem;">
          <i class="fa fa-lock"></i>
        </div>
        <h2 style="font-size: 1.65rem; color: #0f172a; margin-bottom: 0.35rem; font-weight: 800;">Hospital Staff Access</h2>
        <p style="color: #64748b; font-size: 0.9rem; margin: 0;">Authorized portal for Medical Consultants, Operations Administration, and Dispensary.</p>
      </div>

      @php
        $activeRole = request('role', 'doctor');
      @endphp

      <!-- Role Tabs Segmented Switcher -->
      <div class="staff-role-switcher-wrapper mb-4">
        <ul class="nav nav-pills staff-segmented-tabs" id="staffTabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link role-doctor-tab {{ $activeRole === 'doctor' ? 'active' : '' }}" id="staff-doctor-tab" data-toggle="pill" href="#staffDoctorPane" role="tab" aria-selected="{{ $activeRole === 'doctor' ? 'true' : 'false' }}">
              <i class="fa fa-user-md mr-1"></i> Doctor
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link role-admin-tab {{ $activeRole === 'admin' ? 'active' : '' }}" id="staff-admin-tab" data-toggle="pill" href="#staffAdminPane" role="tab" aria-selected="{{ $activeRole === 'admin' ? 'true' : 'false' }}">
              <i class="fa fa-shield mr-1"></i> Admin
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link role-pharm-tab {{ $activeRole === 'pharmacist' ? 'active' : '' }}" id="staff-pharm-tab" data-toggle="pill" href="#staffPharmPane" role="tab" aria-selected="{{ $activeRole === 'pharmacist' ? 'true' : 'false' }}">
              <i class="fa fa-medkit mr-1"></i> Pharmacy
            </a>
          </li>
        </ul>
      </div>

      <div class="tab-content" id="staffTabContent">
        
        <!-- ================= DOCTOR TAB ================= -->
        <div class="tab-pane fade {{ $activeRole === 'doctor' ? 'show active' : '' }}" id="staffDoctorPane" role="tabpanel">
          <div class="mb-3 text-center">
            <span class="badge-modern badge-modern-success mb-1"><i class="fa fa-stethoscope"></i> Medical Staff</span>
            <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Access appointments, medical histories, and issue prescriptions.</p>
          </div>

          <form method="POST" action="{{ route('auth.doctor.login') }}">
            @csrf
            <div class="modern-form-group">
              <label class="modern-form-label">Doctor Username *</label>
              <div class="input-icon-wrapper">
                <i class="fa fa-user-md form-icon"></i>
                <input type="text" class="modern-input" placeholder="e.g. ashok, arun, dinesh" name="username" required autofocus />
              </div>
            </div>

            <div class="modern-form-group">
              <label class="modern-form-label">Security Password *</label>
              <div class="input-icon-wrapper has-toggle">
                <i class="fa fa-lock form-icon"></i>
                <input type="password" class="modern-input" id="doctorPasswordInput" placeholder="Enter your password" name="password" required />
                <button type="button" class="btn-password-toggle" onclick="togglePasswordVisibility('doctorPasswordInput', this)" title="Show/Hide Password">
                  <i class="fa fa-eye"></i>
                </button>
              </div>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn-modern-primary w-100" style="background: linear-gradient(135deg, #059669, #10b981);">
                <i class="fa fa-sign-in"></i> Doctor Sign In
              </button>
            </div>
          </form>
        </div>

        <!-- ================= ADMIN TAB ================= -->
        <div class="tab-pane fade {{ $activeRole === 'admin' ? 'show active' : '' }}" id="staffAdminPane" role="tabpanel">
          <div class="mb-3 text-center">
            <span class="badge-modern badge-modern-primary mb-1"><i class="fa fa-shield"></i> Hospital Operations</span>
            <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Manage hospital staff, departments, doctors, and system logs.</p>
          </div>

          <form method="POST" action="{{ route('auth.admin.login') }}">
            @csrf
            <div class="modern-form-group">
              <label class="modern-form-label">Administrator Username *</label>
              <div class="input-icon-wrapper">
                <i class="fa fa-shield form-icon"></i>
                <input type="text" class="modern-input" placeholder="e.g. admin" name="username" required />
              </div>
            </div>

            <div class="modern-form-group">
              <label class="modern-form-label">Password *</label>
              <div class="input-icon-wrapper has-toggle">
                <i class="fa fa-lock form-icon"></i>
                <input type="password" class="modern-input" id="adminPasswordInput" placeholder="Enter admin password" name="password" required />
                <button type="button" class="btn-password-toggle" onclick="togglePasswordVisibility('adminPasswordInput', this)" title="Show/Hide Password">
                  <i class="fa fa-eye"></i>
                </button>
              </div>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn-modern-primary w-100" style="background: linear-gradient(135deg, #1e3a8a, #2563eb);">
                <i class="fa fa-sign-in"></i> Administrator Sign In
              </button>
            </div>
          </form>
        </div>

        <!-- ================= PHARMACIST TAB ================= -->
        <div class="tab-pane fade {{ $activeRole === 'pharmacist' ? 'show active' : '' }}" id="staffPharmPane" role="tabpanel">
          <div class="mb-3 text-center">
            <span class="badge-modern badge-modern-warning mb-1"><i class="fa fa-medkit"></i> Pharmacy & Dispensary</span>
            <p style="font-size: 0.85rem; color: #64748b; margin: 0;">Fulfill patient prescriptions and manage stock dispensary.</p>
          </div>

          <form method="POST" action="{{ route('auth.pharmacist.login') }}">
            @csrf
            <div class="modern-form-group">
              <label class="modern-form-label">Pharmacist Username *</label>
              <div class="input-icon-wrapper">
                <i class="fa fa-user-circle form-icon"></i>
                <input type="text" class="modern-input" placeholder="e.g. phar" name="username" required />
              </div>
            </div>

            <div class="modern-form-group">
              <label class="modern-form-label">Password *</label>
              <div class="input-icon-wrapper has-toggle">
                <i class="fa fa-lock form-icon"></i>
                <input type="password" class="modern-input" id="pharPasswordInput" placeholder="Enter pharmacy password" name="password" required />
                <button type="button" class="btn-password-toggle" onclick="togglePasswordVisibility('pharPasswordInput', this)" title="Show/Hide Password">
                  <i class="fa fa-eye"></i>
                </button>
              </div>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn-modern-primary w-100" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                <i class="fa fa-sign-in"></i> Pharmacist Sign In
              </button>
            </div>
          </form>
        </div>

      </div>

      <!-- Card Footer -->
      <div class="text-center mt-4 pt-3 border-top">
        <p style="font-size: 0.875rem; color: #64748b; margin: 0;">
          Are you a patient? 
          <a href="{{ route('home') }}" style="color: #2563eb; font-weight: 600; text-decoration: none;">
            Return to Patient Portal <i class="fa fa-arrow-right"></i>
          </a>
        </p>
      </div>

    </div>
  </div>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    var params = new URLSearchParams(window.location.search);
    var role = params.get('role');
    if (role === 'admin') {
      $('#staff-admin-tab').tab('show');
    } else if (role === 'pharmacist') {
      $('#staff-pharm-tab').tab('show');
    } else if (role === 'doctor') {
      $('#staff-doctor-tab').tab('show');
    }
  });
</script>
@endpush
