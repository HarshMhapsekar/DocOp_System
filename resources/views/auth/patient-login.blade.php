@extends('layouts.app')

@section('title', 'Patient Sign In - DocOp Healthcare')
@section('body-class', 'auth-page-wrapper')

@section('navbar')
  @include('partials.navbar')
@endsection

@section('content')
  <div class="auth-glow-orb auth-glow-orb-1"></div>
  <div class="auth-glow-orb auth-glow-orb-2"></div>

  <div class="container d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 80px); padding-top: 100px; padding-bottom: 60px; position: relative; z-index: 2;">
    <div class="auth-card" style="max-width: 480px; width: 100%;">
      <div class="text-center mb-4">
        <div style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, #2563eb, #06b6d4); color: white; display: inline-flex; align-items: center; justify-content: center; font-size: 1.6rem; box-shadow: 0 8px 16px rgba(37,99,235,0.3); margin-bottom: 1rem;">
          <i class="fa fa-user"></i>
        </div>
        <h2 style="font-size: 1.65rem; color: #0f172a; margin-bottom: 0.35rem; font-weight: 800;">Patient Sign In</h2>
        <p style="color: #64748b; font-size: 0.9rem; margin: 0;">Access your appointment schedule, doctor prescriptions, and medical history.</p>
      </div>

      <form method="POST" action="{{ route('auth.patient.login') }}">
        @csrf
        <div class="modern-form-group">
          <label class="modern-form-label">Registered Email Address</label>
          <div class="input-icon-wrapper">
            <i class="fa fa-envelope-o form-icon"></i>
            <input type="email" name="email" class="modern-input" placeholder="e.g. patient@example.com" value="{{ old('email') }}" required autofocus />
          </div>
        </div>

        <div class="modern-form-group">
          <label class="modern-form-label">Your Password</label>
          <div class="input-icon-wrapper">
            <i class="fa fa-lock form-icon"></i>
            <input type="password" name="password" class="modern-input" placeholder="Enter your password" required />
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn-modern-primary w-100">
            <i class="fa fa-sign-in"></i> Sign In to Patient Portal
          </button>
        </div>

        <div class="text-center mt-4 pt-3" style="border-top: 1px solid #e2e8f0;">
          <p style="font-size: 0.875rem; color: #64748b; margin: 0;">
            Don't have an account yet? 
            <a href="{{ route('home') }}" style="color: #2563eb; font-weight: 600; text-decoration: none;">Register here</a>
          </p>
        </div>
      </form>
    </div>
  </div>
@endsection
