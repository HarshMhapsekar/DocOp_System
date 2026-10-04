@extends('layouts.app')

@section('title', 'Issue Prescription - DocOp Healthcare')
@section('body-class', 'light-page-wrapper')

@push('scripts')
<script>
  function appendInstruction(text) {
    var area = document.getElementById('prescription');
    if (!area) return;
    if (area.value.trim() === '') {
      area.value = text;
    } else {
      area.value += '\n' + text;
    }
    area.focus();
  }
</script>
@endpush

@section('navbar')
  <!-- Fixed Modern Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top modern-navbar">
    <div class="container-fluid px-lg-4">
      <a class="navbar-brand" href="{{ route('doctor.dashboard') }}">
        <span class="brand-icon-box" style="background: linear-gradient(135deg, #10b981, #06b6d4);"><i class="fa fa-stethoscope"></i></span>
        <span>DocOp <span style="font-weight: 400; opacity: 0.85; font-size: 0.85em;">Prescription Desk</span></span>
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navContent" aria-controls="navContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navContent">
        <ul class="navbar-nav ml-auto align-items-center">
          <li class="nav-item mr-3">
            <div class="user-profile-badge">
              <div class="user-avatar" style="background: linear-gradient(135deg, #10b981, #06b6d4);"><i class="fa fa-user-md"></i></div>
              <span>Dr. {{ $doctor }}</span>
            </div>
          </li>
          <li class="nav-item mr-2">
            <a class="nav-link" href="{{ route('doctor.dashboard') }}" style="color: #cbd5e1;">
              <i class="fa fa-arrow-left"></i> Doctor Dashboard
            </a>
          </li>
          <li class="nav-item">
            <form method="POST" action="{{ route('auth.logout') }}" class="d-inline">
              @csrf
              <button type="submit" class="nav-link nav-btn-logout border-0" style="cursor: pointer;">
                <i class="fa fa-sign-out"></i> Logout
              </button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>
@endsection

@section('content')
  <!-- Prescription Workspace Container -->
  <div class="dashboard-wrapper">
    <div class="container" style="max-width: 900px;">
      
      <!-- Patient Summary Card -->
      <div class="modern-card mb-4" style="background: linear-gradient(135deg, #064e3b 0%, #047857 60%, #0284c7 100%); color: white;">
        <div class="row align-items-center">
          <div class="col-md-8">
            <span class="badge-modern badge-modern-primary" style="background: rgba(255,255,255,0.2); color: white; border: none; margin-bottom: 0.5rem;">
              <i class="fa fa-calendar-check-o"></i> Appointment #{{ $ID }}
            </span>
            <h2 style="font-size: 1.65rem; font-weight: 700; margin-bottom: 0.25rem;">
              Patient: {{ $fname }} {{ $lname }}
            </h2>
            <p style="color: #a7f3d0; margin: 0; font-size: 0.9rem;">
              <i class="fa fa-id-badge"></i> Patient ID: #{{ $pid }} &nbsp;|&nbsp; 
              <i class="fa fa-clock-o"></i> Consultation: {{ $appdate }} at {{ $apptime }}
            </p>
          </div>
          <div class="col-md-4 text-md-right mt-3 mt-md-0">
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-sm btn-light" style="font-weight: 600; border-radius: 8px;">
              <i class="fa fa-times"></i> Cancel & Return
            </a>
          </div>
        </div>
      </div>

      <!-- Patient EHR & Diagnostic Snapshot Card -->
      <div class="modern-card mb-4" style="border-left: 4px solid #2563eb;">
        <div class="modern-card-header d-flex justify-content-between align-items-center">
          <h4 class="modern-card-title mb-0" style="font-size: 1.05rem;">
            <i class="fa fa-heartbeat text-danger"></i> Patient Electronic Health Record (EHR Snapshot)
          </h4>
          <span class="badge-modern badge-modern-primary">Verified Clinical File</span>
        </div>

        <div class="row">
          <div class="col-md-4 mb-3">
            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 0.75rem 1rem;">
              <small class="text-uppercase text-danger font-weight-bold" style="font-size: 0.7rem;"><i class="fa fa-tint"></i> Blood Group</small>
              <div style="font-size: 1.35rem; font-weight: 800; color: #b91c1c; margin-top: 0.2rem;">
                {{ $patient->blood_group ?? 'Not Set' }}
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-3">
            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 0.75rem 1rem;">
              <small class="text-uppercase text-warning font-weight-bold" style="font-size: 0.7rem; color: #b45309 !important;"><i class="fa fa-warning"></i> Recorded Allergies</small>
              <div style="font-size: 0.95rem; font-weight: 700; color: #92400e; margin-top: 0.25rem;">
                {{ $patient->allergies ? $patient->allergies : 'No allergies recorded' }}
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-3">
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 0.75rem 1rem;">
              <small class="text-uppercase text-success font-weight-bold" style="font-size: 0.7rem; color: #15803d !important;"><i class="fa fa-stethoscope"></i> Chronic History</small>
              <div style="font-size: 0.95rem; font-weight: 700; color: #166534; margin-top: 0.25rem;">
                {{ $patient->chronic_conditions ? $patient->chronic_conditions : 'None reported' }}
              </div>
            </div>
          </div>
        </div>

        @if(isset($documents) && $documents->count() > 0)
          <div class="mt-2 pt-3" style="border-top: 1px dashed #e2e8f0;">
            <strong style="font-size: 0.825rem; color: #475569; text-transform: uppercase; letter-spacing: 0.04em; display: block; margin-bottom: 0.5rem;">
              <i class="fa fa-folder-open-o text-primary mr-1"></i> Patient Lab Investigations & Diagnostic Files ({{ $documents->count() }})
            </strong>
            <div class="d-flex flex-wrap" style="gap: 0.5rem;">
              @foreach($documents as $doc)
                <div style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 0.4rem 0.75rem; font-size: 0.8rem;">
                  <strong style="color: #0f172a;">{{ $doc->title }}</strong>
                  <span class="text-muted">({{ $doc->report_date }})</span>
                  @if(!empty($doc->notes))
                    <div style="font-size: 0.75rem; color: #64748b;">Findings: {{ Str::limit($doc->notes, 40) }}</div>
                  @endif
                </div>
              @endforeach
            </div>
          </div>
        @endif
      </div>

      <!-- Prescription Form Card -->
      <div class="modern-card">
        <div class="modern-card-header">
          <h4 class="modern-card-title"><i class="fa fa-pencil-square-o text-primary"></i> Clinical Diagnosis & Prescription</h4>
        </div>

        <form method="POST" action="{{ route('doctor.prescribe.store') }}">
          @csrf
          <input type="hidden" name="fname" value="{{ $fname }}" />
          <input type="hidden" name="lname" value="{{ $lname }}" />
          <input type="hidden" name="appdate" value="{{ $appdate }}" />
          <input type="hidden" name="apptime" value="{{ $apptime }}" />
          <input type="hidden" name="pid" value="{{ $pid }}" />
          <input type="hidden" name="ID" value="{{ $ID }}" />

          <div class="modern-form-group">
            <label class="modern-form-label">Diagnosis / Disease Identified *</label>
            <textarea name="disease" id="disease" rows="3" class="modern-input" style="height: auto; padding: 0.75rem 1rem;" placeholder="e.g. Acute bronchitis, viral fever, hypertension..." required></textarea>
          </div>

          <div class="modern-form-group">
            <label class="modern-form-label">Known Allergies / Sensitivities *</label>
            <textarea name="allergy" id="allergy" rows="3" class="modern-input" style="height: auto; padding: 0.75rem 1rem;" placeholder="e.g. Penicillin, pollen allergy, lactose intolerance (or 'None')..." required>{{ $patient->allergies ?? '' }}</textarea>
          </div>

          <div class="modern-form-group">
            <label class="modern-form-label">Primary Prescribed Medication</label>
            <select name="medicine" class="modern-input" id="medicine" style="height: 48px;">
              <option value="" selected>Select Medicine from Formulary (or specify in notes below)</option>
              
              <optgroup label="Analgesics & Antipyretics (Fever & Pain Relief)">
                <option value="Paracetamol 650mg (Dolo 650)">Paracetamol 650mg (Dolo 650)</option>
                <option value="Paracetamol 500mg (Crocin)">Paracetamol 500mg (Crocin)</option>
                <option value="Crocin 650 Advance">Crocin 650 Advance</option>
                <option value="DOLO-Cold (General)">DOLO-Cold (General)</option>
                <option value="Ibuprofen 400mg (Brufen)">Ibuprofen 400mg (Brufen)</option>
                <option value="Combiflam (Ibuprofen + Paracetamol)">Combiflam (Ibuprofen + Paracetamol)</option>
                <option value="Aceclofenac 100mg + Paracetamol (Zerodol-P)">Aceclofenac 100mg + Paracetamol (Zerodol-P)</option>
                <option value="Tramadol 50mg (Ultram)">Tramadol 50mg (Ultram)</option>
              </optgroup>

              <optgroup label="Antibiotics & Antimicrobials">
                <option value="Amoxicillin 250mg">Amoxicillin 250mg</option>
                <option value="Amoxicillin 500mg (Novamox)">Amoxicillin 500mg (Novamox)</option>
                <option value="Augmentin 625 Duo (Amoxicillin + Clavulanic Acid)">Augmentin 625 Duo (Amoxicillin + Clavulanic Acid)</option>
                <option value="Azithromycin 500mg (Azithral)">Azithromycin 500mg (Azithral)</option>
                <option value="Ciprofloxacin 500mg (Ciplox)">Ciprofloxacin 500mg (Ciplox)</option>
                <option value="Ofloxacin 200mg + Ornidazole 500mg (O2)">Ofloxacin 200mg + Ornidazole 500mg (O2)</option>
                <option value="Cefixime 200mg (Zifi 200)">Cefixime 200mg (Zifi 200)</option>
                <option value="Doxycycline 100mg (Doxicip)">Doxycycline 100mg (Doxicip)</option>
                <option value="Metronidazole 400mg (Flagyl)">Metronidazole 400mg (Flagyl)</option>
              </optgroup>

              <optgroup label="Cough, Cold & Antihistamines">
                <option value="Benadryl DX Cough Syrup (100ml)">Benadryl DX Cough Syrup (100ml)</option>
                <option value="Ascoril D Plus Cough Syrup">Ascoril D Plus Cough Syrup</option>
                <option value="Grilinctus Cough Syrup">Grilinctus Cough Syrup</option>
                <option value="Cetirizine 10mg (Cetzine)">Cetirizine 10mg (Cetzine)</option>
                <option value="Levocetirizine 5mg (Levocet)">Levocetirizine 5mg (Levocet)</option>
                <option value="Montair-LC (Montelukast + Levocetirizine)">Montair-LC (Montelukast + Levocetirizine)</option>
                <option value="Asthalin 100mcg Inhaler (Salbutamol)">Asthalin 100mcg Inhaler (Salbutamol)</option>
                <option value="Budecort 200 Inhaler">Budecort 200 Inhaler</option>
              </optgroup>

              <optgroup label="Gastrointestinal & Antacids">
                <option value="Pantoprazole 40mg (Pan 40)">Pantoprazole 40mg (Pan 40)</option>
                <option value="Pantoprazole + Domperidone (Pan-D)">Pantoprazole + Domperidone (Pan-D)</option>
                <option value="Omeprazole 20mg (Omez)">Omeprazole 20mg (Omez)</option>
                <option value="Rabeprazole 20mg (Razo 20)">Rabeprazole 20mg (Razo 20)</option>
                <option value="Digene Antacid Gel / Tablets">Digene Antacid Gel / Tablets</option>
                <option value="Ondansetron 4mg (Emeset - Anti-emetic)">Ondansetron 4mg (Emeset - Anti-emetic)</option>
                <option value="Loperamide 2mg (Imodium)">Loperamide 2mg (Imodium)</option>
              </optgroup>

              <optgroup label="Cardiovascular & Blood Pressure">
                <option value="Amlodipine 5mg (Amlokind 5)">Amlodipine 5mg (Amlokind 5)</option>
                <option value="Telmisartan 40mg (Telma 40)">Telmisartan 40mg (Telma 40)</option>
                <option value="Telma-AM (Telmisartan + Amlodipine)">Telma-AM (Telmisartan + Amlodipine)</option>
                <option value="Metoprolol Succinate 25mg (Metolar-XR)">Metoprolol Succinate 25mg (Metolar-XR)</option>
                <option value="Atenolol 50mg (Aten 50)">Atenolol 50mg (Aten 50)</option>
                <option value="Atorvastatin 10mg (Atorva)">Atorvastatin 10mg (Atorva)</option>
                <option value="Ecosprin 75mg (Aspirin)">Ecosprin 75mg (Aspirin)</option>
              </optgroup>

              <optgroup label="Diabetes & Metabolic Care">
                <option value="Metformin 500mg (Glycomet 500)">Metformin 500mg (Glycomet 500)</option>
                <option value="Metformin 850mg (Glycomet SR)">Metformin 850mg (Glycomet SR)</option>
                <option value="Glimepiride 1mg (Amaryl)">Glimepiride 1mg (Amaryl)</option>
                <option value="Glycomet-GP 2 (Glimepiride + Metformin)">Glycomet-GP 2 (Glimepiride + Metformin)</option>
                <option value="Vildagliptin 50mg (Galvus)">Vildagliptin 50mg (Galvus)</option>
              </optgroup>

              <optgroup label="Dermatology & Topicals">
                <option value="Emolene Cream (Moisturizer)">Emolene Cream (Moisturizer)</option>
                <option value="Betnovate-C Cream">Betnovate-C Cream</option>
                <option value="Candid Clotrimazole 1% Cream">Candid Clotrimazole 1% Cream</option>
                <option value="T-Bact 2% Mupirocin Ointment">T-Bact 2% Mupirocin Ointment</option>
                <option value="Burnol Antiseptic Cream">Burnol Antiseptic Cream</option>
              </optgroup>

              <optgroup label="Vitamins, Minerals & Supplements">
                <option value="Becosules Z Capsules">Becosules Z Capsules</option>
                <option value="Calcirol 60000 IU (Vitamin D3)">Calcirol 60000 IU (Vitamin D3)</option>
                <option value="Shelcal 500 (Calcium + Vit D3)">Shelcal 500 (Calcium + Vit D3)</option>
                <option value="Limcee 500mg (Vitamin C)">Limcee 500mg (Vitamin C)</option>
                <option value="Zincovit Multivitamin Tablets">Zincovit Multivitamin Tablets</option>
                <option value="Electral ORS Sachet (21.8g)">Electral ORS Sachet (21.8g)</option>
              </optgroup>
            </select>
          </div>

          <div class="modern-form-group">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label class="modern-form-label mb-0">Detailed Prescription & Dosage Instructions *</label>
              <span class="badge-modern badge-modern-primary" style="font-size: 0.725rem;"><i class="fa fa-magic"></i> Quick Presets</span>
            </div>

            <!-- Fast Clinical Preset Chips -->
            <div class="preset-chips-wrapper mt-2">
              <span class="clinical-preset-chip" onclick="appendInstruction('• 1 Tab twice daily after meals (bid pc) - 5 days.')">1 Tab bid pc (5 days)</span>
              <span class="clinical-preset-chip" onclick="appendInstruction('• 1 Tab thrice daily after food (tid pc) - 3 days.')">1 Tab tid pc (3 days)</span>
              <span class="clinical-preset-chip" onclick="appendInstruction('• 1 Tab once daily early morning empty stomach (od ac) - 14 days.')">1 Tab od ac (empty stomach)</span>
              <span class="clinical-preset-chip" onclick="appendInstruction('• SOS: Take 1 tablet only in case of high fever (>100°F) or severe pain.')">SOS (Fever/Pain)</span>
              <span class="clinical-preset-chip" onclick="appendInstruction('• 10ml (2 tsp) syrup thrice daily after food.')">10ml Syrup tid</span>
              <span class="clinical-preset-chip" onclick="appendInstruction('• Drink plenty of boiled warm water. Avoid oily/spicy foods. Rest for 2 days.')">Dietary & Hydration</span>
            </div>

            <textarea name="prescription" id="prescription" rows="6" class="modern-input" style="height: auto; padding: 0.75rem 1rem;" placeholder="Enter medications, dosage, duration, and dietary advice or click preset chips above..." required></textarea>
          </div>

          <div class="mt-4 pt-2 d-flex justify-content-between align-items-center">
            <a href="{{ route('doctor.dashboard') }}" class="text-muted" style="font-weight: 500; font-size: 0.9rem; text-decoration: none;">
              <i class="fa fa-chevron-left"></i> Discard and go back
            </a>
            <button type="submit" class="btn-modern-primary" style="max-width: 260px;">
              <i class="fa fa-check"></i> Save & Issue Prescription
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
@endsection
