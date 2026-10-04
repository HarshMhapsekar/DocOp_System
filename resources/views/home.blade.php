@extends('layouts.app')

@section('title', 'DocOp - Healthcare & Hospital Management System')
@section('body-class', 'auth-page-wrapper')

@section('navbar')
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top modern-navbar">
    <div class="container">
      <a class="navbar-brand" href="{{ route('home') }}">
        <span class="brand-icon-box"><i class="fa fa-heartbeat"></i></span>
        <span>DocOp <span style="font-weight: 400; opacity: 0.85; font-size: 0.9em;">System</span></span>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto align-items-lg-center">
          <li class="nav-item">
            <a class="nav-link active" href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('services') }}"><i class="fa fa-stethoscope"></i> Services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('about') }}"><i class="fa fa-info-circle"></i> About Us</a>
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
            <a class="nav-link nav-btn-logout" href="{{ route('patient.login.view') }}" style="background: rgba(37,99,235,0.2) !important; color: #93c5fd !important; border-color: rgba(96,165,250,0.3) !important;">
              <i class="fa fa-sign-in"></i> Patient Login
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

  <div class="container" style="padding-top: 110px; padding-bottom: 60px; position: relative; z-index: 2;">
    <div class="row align-items-center">
      
      <!-- Left Column: Branding, Value Prop & Live Stats -->
      <div class="col-lg-5 mb-5 mb-lg-0 text-white">
        <div class="d-inline-flex align-items-center mb-3 px-3 py-1" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 999px; backdrop-filter: blur(8px);">
          <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; display: inline-block; margin-right: 8px; box-shadow: 0 0 10px #10b981;"></span>
          <span style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #e2e8f0;">Advanced Hospital Management</span>
        </div>

        <h1 style="font-size: 2.8rem; font-weight: 800; line-height: 1.15; margin-bottom: 1.25rem; letter-spacing: -0.02em;">
          Precision Care, <br>
          <span style="background: linear-gradient(135deg, #60a5fa, #38bdf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Seamless Access.</span>
        </h1>

        <p style="font-size: 1.05rem; color: #94a3b8; line-height: 1.6; margin-bottom: 2rem; max-width: 460px;">
          DocOp unifies clinical workflows, patient registrations, prescription distribution, and doctor appointment scheduling into one modern platform.
        </p>

        <!-- Feature Points -->
        <div class="mb-4">
          <div class="d-flex align-items-center mb-2" style="font-size: 0.95rem; color: #cbd5e1;">
            <i class="fa fa-check-circle mr-3" style="color: #38bdf8; font-size: 1.1rem;"></i>
            <span>Real-time Doctor Consultation Booking</span>
          </div>
          <div class="d-flex align-items-center mb-2" style="font-size: 0.95rem; color: #cbd5e1;">
            <i class="fa fa-check-circle mr-3" style="color: #38bdf8; font-size: 1.1rem;"></i>
            <span>Digital Prescriptions & In-house Pharmacy Dispensing</span>
          </div>
          <div class="d-flex align-items-center" style="font-size: 0.95rem; color: #cbd5e1;">
            <i class="fa fa-check-circle mr-3" style="color: #38bdf8; font-size: 1.1rem;"></i>
            <span>Centralized Hospital Administration & Role Dashboards</span>
          </div>
        </div>

        <div class="pt-3 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
          <small style="color: #64748b;">Need direct patient access? <a href="{{ route('patient.login.view') }}" style="color: #60a5fa; font-weight: 600; text-decoration: underline;">Click here to sign in</a></small>
        </div>
      </div>

      <!-- Right Column: Elevated Patient Auth Card -->
      <div class="col-lg-7">
        <div class="auth-card">
          <!-- ================= PATIENT SIGN IN VIEW ================= -->
          <div id="patientLoginView" style="display: block;">
            <!-- Login Header -->
            <div class="mb-4">
              <div class="d-flex align-items-center mb-2">
                <span class="badge badge-pill badge-primary px-3 py-1 mr-2" style="font-size: 0.8rem; font-weight: 600; background: rgba(37,99,235,0.1); color: #2563eb; border: 1px solid rgba(37,99,235,0.2);">
                  <i class="fa fa-heartbeat mr-1"></i> Patient Portal
                </span>
                <span style="font-size: 0.85rem; color: #64748b;">Instant Appointment Booking & Records</span>
              </div>
              <h3 style="font-size: 1.45rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">
                Welcome to DocOp Care
              </h3>
              <p style="font-size: 0.875rem; color: #64748b; margin-bottom: 0;">
                Sign in to manage your appointments, view digital prescriptions, and check bills.
              </p>
            </div>

            <!-- Patient Sign In Form -->
            <form id="patientLoginForm" method="POST" action="{{ route('auth.patient.login') }}">
              @csrf
              <div class="modern-form-group">
                <label class="modern-form-label">Patient Email Address *</label>
                <div class="input-icon-wrapper">
                  <i class="fa fa-envelope-o form-icon"></i>
                  <input type="email" name="email" class="modern-input" placeholder="Enter registered email (e.g. ram@gmail.com)" required />
                </div>
              </div>

              <div class="modern-form-group">
                <label class="modern-form-label">Password *</label>
                <div class="input-icon-wrapper has-toggle">
                  <i class="fa fa-lock form-icon"></i>
                  <input type="password" class="modern-input" id="patientLoginPassword" name="password" placeholder="Enter your password" required />
                  <button type="button" class="btn-password-toggle" onclick="togglePasswordVisibility('patientLoginPassword', this)" title="Show/Hide Password">
                    <i class="fa fa-eye"></i>
                  </button>
                </div>
              </div>

              <div class="mt-4">
                <button type="submit" class="btn-modern-primary w-100">
                  <i class="fa fa-sign-in"></i> Sign In to Patient Dashboard
                </button>
              </div>

              <div class="text-center mt-3 pt-2" style="font-size: 0.875rem; color: #64748b;">
                Don't have an account yet?
                <a href="javascript:void(0)" onclick="togglePatientAuth('register')" style="color: #2563eb; font-weight: 600; text-decoration: none;">
                  Create new patient account
                </a>
              </div>
            </form>
          </div>

          <!-- ================= PATIENT REGISTER VIEW ================= -->
          <div id="patientRegisterView" style="display: none;">
            <!-- Register Header -->
            <div class="mb-4">
              <div class="d-flex align-items-center mb-2">
                <span class="badge badge-pill badge-primary px-3 py-1 mr-2" style="font-size: 0.8rem; font-weight: 600; background: rgba(16,185,129,0.1); color: #059669; border: 1px solid rgba(16,185,129,0.2);">
                  <i class="fa fa-user-plus mr-1"></i> New Patient
                </span>
                <span style="font-size: 0.85rem; color: #64748b;">Fast & Secure Registration</span>
              </div>
              <h3 style="font-size: 1.45rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">
                Create Patient Account
              </h3>
              <p style="font-size: 0.875rem; color: #64748b; margin-bottom: 0;">
                Fill in your details below to register and book appointments instantly.
              </p>
            </div>

            <!-- Registration Form -->
            <form id="patientRegisterForm" method="post" action="{{ route('auth.patient.register') }}">
              @csrf
              <div class="row">
                <div class="col-md-6">
                  <div class="modern-form-group">
                    <label class="modern-form-label">First Name *</label>
                    <div class="input-icon-wrapper">
                      <i class="fa fa-user form-icon"></i>
                      <input type="text" class="modern-input" placeholder="e.g. John" name="fname" required />
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="modern-form-group">
                    <label class="modern-form-label">Last Name *</label>
                    <div class="input-icon-wrapper">
                      <i class="fa fa-user form-icon"></i>
                      <input type="text" class="modern-input" placeholder="e.g. Doe" name="lname" required />
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="modern-form-group">
                    <label class="modern-form-label">Email Address *</label>
                    <div class="input-icon-wrapper">
                      <i class="fa fa-envelope-o form-icon"></i>
                      <input type="email" class="modern-input" placeholder="john@example.com" name="email" required />
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="modern-form-group">
                    <label class="modern-form-label">Phone Number *</label>
                    <div class="input-icon-wrapper">
                      <i class="fa fa-phone form-icon"></i>
                      <input type="tel" minlength="10" maxlength="15" name="contact" class="modern-input" placeholder="Phone number" required />
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="modern-form-group">
                    <label class="modern-form-label">Create Password *</label>
                    <div class="input-icon-wrapper has-toggle">
                      <i class="fa fa-lock form-icon"></i>
                      <input type="password" class="modern-input" placeholder="Min. 6 characters" id="password" name="password" required />
                      <button type="button" class="btn-password-toggle" onclick="togglePasswordVisibility('password', this)" title="Show/Hide Password">
                        <i class="fa fa-eye"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="modern-form-group">
                    <label class="modern-form-label">Confirm Password *</label>
                    <div class="input-icon-wrapper has-toggle">
                      <i class="fa fa-lock form-icon"></i>
                      <input type="password" class="modern-input" id="cpassword" placeholder="Repeat password" name="cpassword" required />
                      <button type="button" class="btn-password-toggle" onclick="togglePasswordVisibility('cpassword', this)" title="Show/Hide Password">
                        <i class="fa fa-eye"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="modern-form-group">
                <label class="modern-form-label">Gender</label>
                <div class="gender-selector">
                  <div class="gender-option">
                    <input type="radio" id="genderMale" name="gender" value="Male" checked>
                    <label for="genderMale" class="gender-label"><i class="fa fa-male"></i> Male</label>
                  </div>
                  <div class="gender-option">
                    <input type="radio" id="genderFemale" name="gender" value="Female">
                    <label for="genderFemale" class="gender-label"><i class="fa fa-female"></i> Female</label>
                  </div>
                </div>
              </div>

              <div class="mt-4">
                <button type="submit" class="btn-modern-primary w-100">
                  <i class="fa fa-user-plus"></i> Register & Continue
                </button>
              </div>

              <div class="text-center mt-3 pt-2" style="font-size: 0.875rem; color: #64748b;">
                Already have a registered account?
                <a href="javascript:void(0)" onclick="togglePatientAuth('login')" style="color: #2563eb; font-weight: 600; text-decoration: none;">
                  Sign in here
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection

@push('scripts')
<script>
  function togglePatientAuth(mode) {
    var logView = document.getElementById("patientLoginView");
    var regView = document.getElementById("patientRegisterView");

    if (mode === 'login') {
      regView.style.display = "none";
      logView.style.display = "block";
    } else {
      regView.style.display = "block";
      logView.style.display = "none";
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    if (window.location.hash === '#register' || new URLSearchParams(window.location.search).get('action') === 'register') {
      togglePatientAuth('register');
    }
  });

  @if($errors->any() && (old('fname') || old('lname') || old('gender')))
    togglePatientAuth('register');
  @endif
</script>
@endpush
