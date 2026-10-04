@extends('layouts.app')

@section('title', 'Contact Us & Hospital Location - DocOp Healthcare')
@section('body-class', 'light-page-wrapper')

@push('scripts')
<script>
  function alphaOnly(event) {
    var key = event.keyCode;
    return ((key >= 65 && key <= 90) || key == 8 || key == 32);
  }
</script>
@endpush

@section('navbar')
  @include('partials.navbar')
@endsection

@section('content')
  <!-- Clean Page Hero Banner -->
  <div class="page-hero-banner" style="background: linear-gradient(135deg, #09203f 0%, #1e3a8a 50%, #0369a1 100%);">
    <div class="container">
      <div class="hero-tag">
        <i class="fa fa-phone"></i> 24/7 Patient Assistance
      </div>
      <h1>Contact Us & Hospital Inquiries</h1>
      <p>
        Have a question for our doctors or hospital reception? We are available 24/7 for emergency ambulance calls, appointment queries, and feedback.
      </p>
    </div>
  </div>

  <!-- Main Content Container -->
  <div class="container mb-5">

    <!-- Highlight Cards Row -->
    <div class="row mb-4">
      <div class="col-md-4 mb-3">
        <div class="contact-highlight-box">
          <div class="contact-highlight-icon" style="background: #fee2e2; color: #ef4444;">
            <i class="fa fa-phone"></i>
          </div>
          <div>
            <h5 style="font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">Emergency Hotline</h5>
            <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 0.35rem;">Immediate 24/7 Trauma Dispatch</p>
            <div style="font-weight: 800; color: #ef4444; font-size: 1.05rem;">+1 (800) 456-7890</div>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-3">
        <div class="contact-highlight-box">
          <div class="contact-highlight-icon" style="background: #eff6ff; color: #2563eb;">
            <i class="fa fa-envelope-o"></i>
          </div>
          <div>
            <h5 style="font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">Email Support</h5>
            <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 0.35rem;">General & Prescription Inquiries</p>
            <div style="font-weight: 700; color: #2563eb; font-size: 0.95rem;">support@docop-hospital.org</div>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-3">
        <div class="contact-highlight-box">
          <div class="contact-highlight-icon" style="background: #ecfdf5; color: #10b981;">
            <i class="fa fa-map-marker"></i>
          </div>
          <div>
            <h5 style="font-weight: 700; color: #0f172a; margin-bottom: 0.25rem;">Hospital Location</h5>
            <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 0.35rem;">Main Healthcare Campus</p>
            <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">Building 4, Health Avenue</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Workspace: Info & Contact Form -->
    <div class="row">
      <!-- Left Side: Clinic Information & Visiting Schedule -->
      <div class="col-lg-5 mb-4">
        <!-- Visiting Hours Card -->
        <div class="modern-card mb-4">
          <div class="modern-card-header">
            <h4 class="modern-card-title"><i class="fa fa-clock-o text-primary"></i> OPD & Visiting Hours</h4>
          </div>
          <table class="table table-borderless" style="margin-bottom: 0; font-size: 0.9rem;">
            <tbody>
              <tr style="border-bottom: 1px solid #f1f5f9;">
                <td style="font-weight: 600; color: #0f172a; padding: 0.75rem 0;">Morning OPD</td>
                <td class="text-right text-muted" style="padding: 0.75rem 0;">08:00 AM – 01:00 PM</td>
              </tr>
              <tr style="border-bottom: 1px solid #f1f5f9;">
                <td style="font-weight: 600; color: #0f172a; padding: 0.75rem 0;">Evening OPD</td>
                <td class="text-right text-muted" style="padding: 0.75rem 0;">04:00 PM – 08:30 PM</td>
              </tr>
              <tr style="border-bottom: 1px solid #f1f5f9;">
                <td style="font-weight: 600; color: #0f172a; padding: 0.75rem 0;">ICU Visiting Hours</td>
                <td class="text-right text-muted" style="padding: 0.75rem 0;">05:00 PM – 07:00 PM</td>
              </tr>
              <tr>
                <td style="font-weight: 600; color: #ef4444; padding: 0.75rem 0;">Emergency Trauma</td>
                <td class="text-right" style="color: #ef4444; font-weight: 700; padding: 0.75rem 0;">Open 24/7 / 365 Days</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Department Extensions -->
        <div class="modern-card">
          <div class="modern-card-header">
            <h4 class="modern-card-title"><i class="fa fa-phone-square text-primary"></i> Direct Extensions</h4>
          </div>
          <div style="font-size: 0.875rem; color: #475569; line-height: 2;">
            <div><i class="fa fa-angle-right text-primary mr-2"></i> <strong>Cardiology Department:</strong> Ext. 102</div>
            <div><i class="fa fa-angle-right text-primary mr-2"></i> <strong>Pediatrics Clinic:</strong> Ext. 105</div>
            <div><i class="fa fa-angle-right text-primary mr-2"></i> <strong>Pharmacy & Dispensary:</strong> Ext. 110</div>
            <div><i class="fa fa-angle-right text-primary mr-2"></i> <strong>Billing & Insurance Desk:</strong> Ext. 115</div>
          </div>
        </div>
      </div>

      <!-- Right Side: Message Form -->
      <div class="col-lg-7 mb-4">
        <div class="modern-card p-4 p-md-5">
          <div class="mb-4">
            <span class="badge-modern badge-modern-primary mb-2">Direct Inquiry</span>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 0.35rem;">Send a Message to Reception</h3>
            <p style="color: #64748b; font-size: 0.9rem; margin: 0;">Our administrative desk will reply to your registered phone or email address.</p>
          </div>

          <form method="post" action="{{ route('contact.store') }}">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="modern-form-group">
                  <label class="modern-form-label">Full Name *</label>
                  <div class="input-icon-wrapper">
                    <i class="fa fa-user form-icon"></i>
                    <input type="text" name="txtName" class="modern-input" placeholder="e.g. Michael Smith" onkeydown="return alphaOnly(event);" required />
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="modern-form-group">
                  <label class="modern-form-label">Email Address *</label>
                  <div class="input-icon-wrapper">
                    <i class="fa fa-envelope-o form-icon"></i>
                    <input type="email" name="txtEmail" class="modern-input" placeholder="michael@example.com" required />
                  </div>
                </div>
              </div>
            </div>

            <div class="modern-form-group">
              <label class="modern-form-label">Phone Contact Number *</label>
              <div class="input-icon-wrapper">
                <i class="fa fa-phone form-icon"></i>
                <input type="tel" name="txtPhone" class="modern-input" placeholder="10-digit phone number" minlength="10" maxlength="15" required />
              </div>
            </div>

            <div class="modern-form-group">
              <label class="modern-form-label">Message / Clinical Query *</label>
              <textarea name="txtMsg" class="modern-input" style="height: auto; padding: 0.85rem 1rem;" rows="5" placeholder="Please describe how our clinical staff can assist you..." required></textarea>
            </div>

            <div class="mt-4">
              <button type="submit" name="btnSubmit" class="btn-modern-primary">
                <i class="fa fa-paper-plane"></i> Submit Message
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
@endsection
