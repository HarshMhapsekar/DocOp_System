@extends('layouts.app')

@section('title', 'Clinical Services & Departments - DocOp Healthcare')
@section('body-class', 'light-page-wrapper')

@section('navbar')
  @include('partials.navbar')
@endsection

@section('content')
  <!-- Clean Page Hero Banner -->
  <div class="page-hero-banner">
    <div class="container">
      <div class="hero-tag">
        <i class="fa fa-hospital-o"></i> Specialized Clinical Excellence
      </div>
      <h1>Our Medical Services & Departments</h1>
      <p>
        Explore our full range of outpatient and inpatient clinical departments, specialist consultations, 24/7 trauma services, and digital diagnostic centers.
      </p>
    </div>
  </div>

  <!-- Main Content Container -->
  <div class="container mb-5">

    <!-- Clinical Departments Grid -->
    <div class="row">
      <!-- 1. Cardiology -->
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="service-dept-card">
          <div class="service-dept-icon" style="background: #fee2e2; color: #ef4444;">
            <i class="fa fa-heartbeat"></i>
          </div>
          <span class="badge-modern badge-modern-danger mb-2" style="width: fit-content;">Department of Cardiology</span>
          <h3 class="service-dept-title">Cardiovascular Care</h3>
          <p class="service-dept-desc">
            Advanced cardiac screening, resting 12-lead ECG, color Doppler echocardiography, hypertension monitoring, and preventive vascular care.
          </p>
          <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 1rem;">
            <div><i class="fa fa-user-md text-primary mr-1"></i> <strong>Specialists:</strong> Dr. Arun, Dr. Amit</div>
            <div><i class="fa fa-clock-o text-muted mr-1"></i> Mon - Sat: 09:00 AM - 05:00 PM</div>
          </div>
          <div class="service-dept-footer">
            <span style="font-weight: 800; color: #059669; font-size: 1.1rem;">From $600</span>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-primary font-weight-bold" style="border-radius: 8px;">
              Book Doctor <i class="fa fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- 2. Pediatrics -->
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="service-dept-card">
          <div class="service-dept-icon" style="background: #ecfdf5; color: #10b981;">
            <i class="fa fa-child"></i>
          </div>
          <span class="badge-modern badge-modern-success mb-2" style="width: fit-content;">Department of Pediatrics</span>
          <h3 class="service-dept-title">Pediatric & Child Health</h3>
          <p class="service-dept-desc">
            Complete neonatal health checks, immunizations, childhood viral infections, asthma care, and developmental milestone assessments.
          </p>
          <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 1rem;">
            <div><i class="fa fa-user-md text-primary mr-1"></i> <strong>Specialists:</strong> Dr. Ganesh, Dr. Kumar, Dr. Tiwary</div>
            <div><i class="fa fa-clock-o text-muted mr-1"></i> Mon - Sat: 08:00 AM - 06:00 PM</div>
          </div>
          <div class="service-dept-footer">
            <span style="font-weight: 800; color: #059669; font-size: 1.1rem;">From $450</span>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-primary font-weight-bold" style="border-radius: 8px;">
              Book Doctor <i class="fa fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- 3. Neurology -->
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="service-dept-card">
          <div class="service-dept-icon" style="background: #f5f3ff; color: #8b5cf6;">
            <i class="fa fa-user-md"></i>
          </div>
          <span class="badge-modern badge-modern-primary mb-2" style="width: fit-content;">Department of Neurology</span>
          <h3 class="service-dept-title">Neurology & Neurosciences</h3>
          <p class="service-dept-desc">
            Neurological assessments for chronic headaches, seizures, neuromuscular disorders, spinal care, and post-concussion recovery therapies.
          </p>
          <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 1rem;">
            <div><i class="fa fa-user-md text-primary mr-1"></i> <strong>Specialist:</strong> Dr. Shubham</div>
            <div><i class="fa fa-clock-o text-muted mr-1"></i> Tue - Sat: 10:00 AM - 04:00 PM</div>
          </div>
          <div class="service-dept-footer">
            <span style="font-weight: 800; color: #059669; font-size: 1.1rem;">$1,500</span>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-primary font-weight-bold" style="border-radius: 8px;">
              Book Doctor <i class="fa fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- 4. General Internal Medicine -->
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="service-dept-card">
          <div class="service-dept-icon" style="background: #eff6ff; color: #3b82f6;">
            <i class="fa fa-stethoscope"></i>
          </div>
          <span class="badge-modern badge-modern-primary mb-2" style="width: fit-content;">Internal Medicine</span>
          <h3 class="service-dept-title">General Medicine & OPD</h3>
          <p class="service-dept-desc">
            Primary care consultations for acute illness, fever, seasonal flu, diabetes management, dietary health, and routine physical exams.
          </p>
          <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 1rem;">
            <div><i class="fa fa-user-md text-primary mr-1"></i> <strong>Specialists:</strong> Dr. Ashok, Dr. Dinesh</div>
            <div><i class="fa fa-clock-o text-muted mr-1"></i> Daily: 08:00 AM - 08:00 PM</div>
          </div>
          <div class="service-dept-footer">
            <span style="font-weight: 800; color: #059669; font-size: 1.1rem;">From $500</span>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-primary font-weight-bold" style="border-radius: 8px;">
              Book Doctor <i class="fa fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- 5. Pharmacy & Dispensary -->
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="service-dept-card">
          <div class="service-dept-icon" style="background: #ecfeff; color: #06b6d4;">
            <i class="fa fa-medkit"></i>
          </div>
          <span class="badge-modern badge-modern-success mb-2" style="width: fit-content;">Hospital Pharmacy</span>
          <h3 class="service-dept-title">Digital Pharmacy Services</h3>
          <p class="service-dept-desc">
            Integrated prescription fulfillment with licensed pharmacists. Complete stocks of oral medications, specialized antibiotics, and topical treatments.
          </p>
          <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 1rem;">
            <div><i class="fa fa-check-circle text-success mr-1"></i> Certified Drugs Only</div>
            <div><i class="fa fa-clock-o text-muted mr-1"></i> Open 24 Hours / 7 Days</div>
          </div>
          <div class="service-dept-footer">
            <span style="font-weight: 800; color: #0f172a; font-size: 0.95rem;">Automated Dispatch</span>
            <a href="{{ route('patient.login.view') }}" class="btn btn-sm btn-outline-secondary font-weight-bold" style="border-radius: 8px;">
              View Records <i class="fa fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- 6. Emergency & Trauma -->
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="service-dept-card" style="border-left: 4px solid #ef4444;">
          <div class="service-dept-icon" style="background: #fff1f2; color: #f43f5e;">
            <i class="fa fa-ambulance"></i>
          </div>
          <span class="badge-modern badge-modern-danger mb-2" style="width: fit-content;">Emergency & ICU</span>
          <h3 class="service-dept-title">24/7 Trauma Dispatch</h3>
          <p class="service-dept-desc">
            Immediate life-saving critical response with advanced life support ambulances, trauma resuscitation bays, and emergency surgical teams.
          </p>
          <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 1rem;">
            <div><i class="fa fa-phone text-danger mr-1"></i> <strong>Hotline:</strong> +1 (800) 456-7890</div>
            <div><i class="fa fa-clock-o text-danger mr-1"></i> Zero-wait emergency triage</div>
          </div>
          <div class="service-dept-footer">
            <span style="font-weight: 800; color: #ef4444; font-size: 1.05rem;">Immediate</span>
            <a href="{{ route('contact') }}" class="btn btn-sm btn-danger font-weight-bold" style="border-radius: 8px;">
              Call Hospital <i class="fa fa-phone"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Diagnostic Lab & Radiology Banner -->
    <div class="modern-card p-4 p-lg-5 my-5" style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #eff6ff 100%); border-color: #a7f3d0;">
      <div class="row align-items-center">
        <div class="col-lg-8">
          <span class="badge-modern badge-modern-success mb-2"><i class="fa fa-flask"></i> Diagnostic Excellence</span>
          <h2 style="font-size: 1.85rem; font-weight: 800; color: #065f46; margin-bottom: 0.75rem;">
            Modern Pathology & Diagnostic Laboratory
          </h2>
          <p style="color: #334155; font-size: 0.975rem; line-height: 1.6; margin-bottom: 1rem;">
            Equipped with automated hematology analyzers, clinical biochemistry, digital X-Ray, high-resolution ultrasound, and molecular diagnostics. Results are securely linked to your patient portal.
          </p>
          <div class="d-flex flex-wrap gap-2">
            <span class="badge badge-light p-2 mr-2 mb-2" style="border: 1px solid #cbd5e1;"><i class="fa fa-check text-success"></i> 2-Hour Rapid Lab Reports</span>
            <span class="badge badge-light p-2 mr-2 mb-2" style="border: 1px solid #cbd5e1;"><i class="fa fa-check text-success"></i> Digital Health Portal Delivery</span>
            <span class="badge badge-light p-2 mr-2 mb-2" style="border: 1px solid #cbd5e1;"><i class="fa fa-check text-success"></i> Cashless Insurance Support</span>
          </div>
        </div>
        <div class="col-lg-4 text-lg-right mt-4 mt-lg-0">
          <a href="{{ route('home') }}" class="btn-modern-primary" style="text-decoration: none; padding: 0.85rem 1.75rem; display: inline-flex;">
            <i class="fa fa-calendar"></i> Book a Health Test
          </a>
        </div>
      </div>
    </div>

    <!-- Health Packages Section -->
    <div class="text-center mb-5">
      <span class="badge-modern badge-modern-primary mb-2">Preventive Care</span>
      <h2 style="font-size: 2rem; font-weight: 800; color: #0f172a;">Health Checkup Packages</h2>
      <p style="color: #64748b; max-width: 600px; margin: 0 auto 2.5rem;">
        Early detection is key to longevity. Select a comprehensive health screening package tailored to your age and lifestyle.
      </p>

      <div class="row">
        <!-- Basic -->
        <div class="col-md-4 mb-4">
          <div class="modern-card h-100 text-left p-4">
            <h4 style="font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">Standard Wellness</h4>
            <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 1rem;">For Young Adults & Annual Review</div>
            <div style="font-size: 2.25rem; font-weight: 800; color: #2563eb; margin-bottom: 1.25rem;">$99</div>
            <ul style="color: #475569; font-size: 0.9rem; line-height: 2.1; list-style: none; padding-left: 0; margin-bottom: 1.5rem;">
              <li><i class="fa fa-check text-success mr-2"></i> Complete Blood Count (CBC)</li>
              <li><i class="fa fa-check text-success mr-2"></i> Fasting Blood Glucose</li>
              <li><i class="fa fa-check text-success mr-2"></i> Lipid Profile (Cholesterol)</li>
              <li><i class="fa fa-check text-success mr-2"></i> Urine Routine Examination</li>
              <li><i class="fa fa-check text-success mr-2"></i> General Physician Review</li>
            </ul>
            <a href="{{ route('home') }}" class="btn-modern-primary" style="text-decoration: none;">Choose Plan</a>
          </div>
        </div>

        <!-- Comprehensive -->
        <div class="col-md-4 mb-4">
          <div class="modern-card h-100 text-left p-4" style="border: 2px solid #2563eb; position: relative;">
            <div style="position: absolute; top: -12px; right: 20px; background: #2563eb; color: white; padding: 2px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">RECOMMENDED</div>
            <h4 style="font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">Cardiac Comprehensive</h4>
            <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 1rem;">Complete Cardio-Metabolic Screening</div>
            <div style="font-size: 2.25rem; font-weight: 800; color: #059669; margin-bottom: 1.25rem;">$199</div>
            <ul style="color: #475569; font-size: 0.9rem; line-height: 2.1; list-style: none; padding-left: 0; margin-bottom: 1.5rem;">
              <li><i class="fa fa-check text-success mr-2"></i> All Standard Wellness Tests</li>
              <li><i class="fa fa-check text-success mr-2"></i> 12-Lead Resting ECG</li>
              <li><i class="fa fa-check text-success mr-2"></i> Glycated Hemoglobin (HbA1c)</li>
              <li><i class="fa fa-check text-success mr-2"></i> Kidney & Liver Function Tests</li>
              <li><i class="fa fa-check text-success mr-2"></i> Cardiologist Consultation</li>
            </ul>
            <a href="{{ route('home') }}" class="btn-modern-primary" style="text-decoration: none; background: linear-gradient(135deg, #059669, #10b981);">Choose Plan</a>
          </div>
        </div>

        <!-- Executive -->
        <div class="col-md-4 mb-4">
          <div class="modern-card h-100 text-left p-4">
            <h4 style="font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">Executive Whole Body</h4>
            <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 1rem;">In-depth screening for seniors & executives</div>
            <div style="font-size: 2.25rem; font-weight: 800; color: #d97706; margin-bottom: 1.25rem;">$349</div>
            <ul style="color: #475569; font-size: 0.9rem; line-height: 2.1; list-style: none; padding-left: 0; margin-bottom: 1.5rem;">
              <li><i class="fa fa-check text-success mr-2"></i> All Comprehensive Tests</li>
              <li><i class="fa fa-check text-success mr-2"></i> Chest Digital X-Ray</li>
              <li><i class="fa fa-check text-success mr-2"></i> Abdominal & Pelvic Ultrasound</li>
              <li><i class="fa fa-check text-success mr-2"></i> Thyroid Profile (T3, T4, TSH)</li>
              <li><i class="fa fa-check text-success mr-2"></i> Senior Specialist Follow-up</li>
            </ul>
            <a href="{{ route('home') }}" class="btn-modern-primary" style="text-decoration: none; background: linear-gradient(135deg, #d97706, #f59e0b);">Choose Plan</a>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
