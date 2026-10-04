@extends('layouts.app')

@section('title', 'Logged Out - DocOp Healthcare')
@section('body-class', 'auth-page-wrapper d-flex align-items-center justify-content-center')

@section('content')
  <div class="auth-glow-orb auth-glow-orb-1"></div>
  <div class="auth-glow-orb auth-glow-orb-2"></div>

  <div class="container text-center" style="max-width: 460px; z-index: 2; padding: 2rem 1rem;">
    <div class="auth-card">
      <div style="width: 64px; height: 64px; border-radius: 20px; background: rgba(14,165,233,0.12); color: #0284c7; display: inline-flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 1.25rem;">
        <i class="fa fa-sign-out"></i>
      </div>
      <h3 style="font-size: 1.5rem; color: #0f172a; margin-bottom: 0.5rem; font-weight: 700;">You Have Logged Out</h3>
      <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 1.75rem;">
        Your session has ended safely. Thank you for using DocOp Healthcare Management System.
      </p>

      <a href="{{ route('home') }}" class="btn-modern-primary w-100 mb-3" style="text-decoration: none;">
        <i class="fa fa-sign-in"></i> Back to Main Portal
      </a>

      <div>
        <a href="{{ route('patient.login.view') }}" style="color: #2563eb; font-size: 0.875rem; text-decoration: none; font-weight: 600;">
          <i class="fa fa-user"></i> Patient Sign In
        </a>
      </div>
    </div>
  </div>
@endsection
