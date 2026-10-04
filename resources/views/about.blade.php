@extends('layouts.app')

@section('title', 'About Us - DocOp Healthcare System')
@section('body-class', 'light-page-wrapper')

@section('navbar')
  @include('partials.navbar')
@endsection

@section('content')
  <!-- Clean Page Hero Banner -->
  <div class="page-hero-banner" style="background: linear-gradient(135deg, #1e1b4b 0%, #1e3a8a 50%, #0284c7 100%);">
    <div class="container">
      <div class="hero-tag">
        <i class="fa fa-heartbeat"></i> Clinical Leadership & Trust
      </div>
      <h1>About DocOp Healthcare System</h1>
      <p>
        Pioneering digital healthcare delivery, compassionate medical consultations, and state-of-the-art clinical operations.
      </p>
    </div>
  </div>

  <div class="container mb-5">
    <!-- Quick Metrics Counters -->
    <div class="row mb-5">
      <div class="col-md-3 col-6 mb-3">
        <div class="modern-card text-center py-4">
          <h2 style="font-size: 2.25rem; font-weight: 800; color: #2563eb; margin-bottom: 0.25rem;">50k+</h2>
          <div style="font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Patients Treated</div>
        </div>
      </div>
      <div class="col-md-3 col-6 mb-3">
        <div class="modern-card text-center py-4">
          <h2 style="font-size: 2.25rem; font-weight: 800; color: #059669; margin-bottom: 0.25rem;">120+</h2>
          <div style="font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Specialist Doctors</div>
        </div>
      </div>
      <div class="col-md-3 col-6 mb-3">
        <div class="modern-card text-center py-4">
          <h2 style="font-size: 2.25rem; font-weight: 800; color: #0891b2; margin-bottom: 0.25rem;">99.8%</h2>
          <div style="font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Recovery Rate</div>
        </div>
      </div>
      <div class="col-md-3 col-6 mb-3">
        <div class="modern-card text-center py-4">
          <h2 style="font-size: 2.25rem; font-weight: 800; color: #7c3aed; margin-bottom: 0.25rem;">24/7</h2>
          <div style="font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Emergency Dispatch</div>
        </div>
      </div>
    </div>

    <!-- Mission, Vision & Core Values -->
    <div class="row mb-5">
      <div class="col-lg-6 mb-4">
        <div class="modern-card h-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(240,249,255,0.95));">
          <div style="width: 52px; height: 52px; border-radius: 14px; background: rgba(37,99,235,0.15); color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
            <i class="fa fa-bullseye"></i>
          </div>
          <h3 style="font-size: 1.45rem; color: #0f172a; margin-bottom: 0.75rem;">Our Mission</h3>
          <p style="color: #475569; line-height: 1.7; font-size: 0.95rem;">
            To empower patients with direct, transparent, and seamless access to quality clinical consultations, digital medical histories, and timely prescriptions while removing bureaucratic hurdles and unnecessary waiting times.
          </p>
        </div>
      </div>

      <div class="col-lg-6 mb-4">
        <div class="modern-card h-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(240,253,250,0.95));">
          <div style="width: 52px; height: 52px; border-radius: 14px; background: rgba(16,185,129,0.15); color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
            <i class="fa fa-eye"></i>
          </div>
          <h3 style="font-size: 1.45rem; color: #0f172a; margin-bottom: 0.75rem;">Our Vision</h3>
          <p style="color: #475569; line-height: 1.7; font-size: 0.95rem;">
            To be the benchmark in integrated hospital operations and clinical intelligence, where appointments, diagnosis histories, pharmacy dispensing, and billing operate in real-time harmony for superior patient recovery.
          </p>
        </div>
      </div>
    </div>

    <!-- Department Specialists -->
    <div class="mb-5">
      <div class="text-center mb-4">
        <h2 style="font-size: 2rem; font-weight: 800; color: #0f172a;">Our Specialized Medical Departments</h2>
        <p style="color: #64748b;">Led by senior doctors and board-certified medical consultants.</p>
      </div>

      <div class="row">
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="modern-card text-center h-100">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: inline-flex; align-items: center; justify-content: center; font-size: 1.75rem; margin-bottom: 1rem;">
              <i class="fa fa-heartbeat"></i>
            </div>
            <h5 style="font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">Cardiology</h5>
            <p style="color: #64748b; font-size: 0.85rem;">Dr. Arun & Dr. Amit</p>
            <span class="badge-modern badge-modern-primary">Heart Care</span>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
          <div class="modern-card text-center h-100">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: #ecfdf5; color: #059669; display: inline-flex; align-items: center; justify-content: center; font-size: 1.75rem; margin-bottom: 1rem;">
              <i class="fa fa-child"></i>
            </div>
            <h5 style="font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">Pediatrics</h5>
            <p style="color: #64748b; font-size: 0.85rem;">Dr. Ganesh & Dr. Kumar</p>
            <span class="badge-modern badge-modern-success">Child Health</span>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
          <div class="modern-card text-center h-100">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: #f5f3ff; color: #7c3aed; display: inline-flex; align-items: center; justify-content: center; font-size: 1.75rem; margin-bottom: 1rem;">
              <i class="fa fa-user-md"></i>
            </div>
            <h5 style="font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">Neurology</h5>
            <p style="color: #64748b; font-size: 0.85rem;">Dr. Shubham</p>
            <span class="badge-modern badge-modern-warning">Neuroscience</span>
          </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
          <div class="modern-card text-center h-100">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: #fff7ed; color: #ea580c; display: inline-flex; align-items: center; justify-content: center; font-size: 1.75rem; margin-bottom: 1rem;">
              <i class="fa fa-stethoscope"></i>
            </div>
            <h5 style="font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">General Medicine</h5>
            <p style="color: #64748b; font-size: 0.85rem;">Dr. Ashok & Dr. Dinesh</p>
            <span class="badge-modern badge-modern-primary">Primary Care</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Call to Action Banner -->
    <div class="modern-card text-center py-5" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 60%, #06b6d4 100%); color: white;">
      <h3 style="font-size: 1.85rem; font-weight: 800; margin-bottom: 0.5rem;">Need Medical Consultation Today?</h3>
      <p style="color: #e0f2fe; max-width: 580px; margin: 0 auto 1.5rem auto;">
        Book an appointment directly in our portal or call our 24/7 hotline for emergency assistance.
      </p>
      <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="{{ route('home') }}" class="btn btn-light px-4 py-2 font-weight-bold" style="border-radius: 12px; color: #1e3a8a;">
          <i class="fa fa-calendar-check-o"></i> Book Appointment
        </a>
        <a href="{{ route('contact') }}" class="btn btn-outline-light px-4 py-2 font-weight-bold ml-2" style="border-radius: 12px;">
          <i class="fa fa-phone"></i> Contact Hospital
        </a>
      </div>
    </div>
  </div>
@endsection
