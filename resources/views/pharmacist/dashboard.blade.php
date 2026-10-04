@extends('layouts.app')

@section('title', 'Pharmacy Console - DocOp Healthcare')
@section('body-class', 'light-page-wrapper')

@push('scripts')
<script>
  function clickDiv(id) {
    var elem = document.querySelector(id);
    if (elem) elem.click();
  }

  function quickDispense(doctor, medicine) {
    if (doctor) {
      var docSelect = document.querySelector('select[name="doctor"]');
      if (docSelect) {
        docSelect.value = doctor;
      }
    }
    if (medicine) {
      var medSelect = document.querySelector('select[name="special"]');
      if (medSelect) {
        for (var i = 0; i < medSelect.options.length; i++) {
          if (medSelect.options[i].value.toLowerCase().includes(medicine.toLowerCase()) || medicine.toLowerCase().includes(medSelect.options[i].value.toLowerCase())) {
            medSelect.selectedIndex = i;
            break;
          }
        }
      }
    }
    clickDiv('#list-adoc-list');
  }

  function filterTable(inputId, tbodyId, countId) {
    var input = document.getElementById(inputId);
    if (!input) return;
    var filter = input.value.toLowerCase().trim();
    var tbody = document.getElementById(tbodyId);
    if (!tbody) return;
    var rows = tbody.getElementsByTagName('tr');
    var matchCount = 0;
    var totalRows = 0;

    for (var i = 0; i < rows.length; i++) {
      if (rows[i].classList.contains('no-filter-row')) continue;
      totalRows++;
      var text = rows[i].textContent || rows[i].innerText;
      if (text.toLowerCase().indexOf(filter) > -1) {
        rows[i].style.display = "";
        matchCount++;
      } else {
        rows[i].style.display = "none";
      }
    }

    var noMatchRow = tbody.querySelector('.no-matches-row');
    if (matchCount === 0 && totalRows > 0) {
      if (!noMatchRow) {
        noMatchRow = document.createElement('tr');
        noMatchRow.className = 'no-matches-row no-filter-row';
        var cols = rows[0] && rows[0].children ? rows[0].children.length : 8;
        noMatchRow.innerHTML = '<td colspan="' + cols + '" class="text-center py-4 text-muted"><i class="fa fa-search-minus mr-2"></i>No records matching "<strong>' + filter + '</strong>"</td>';
        tbody.appendChild(noMatchRow);
      } else {
        noMatchRow.style.display = "";
        var strong = noMatchRow.querySelector('strong');
        if (strong) strong.innerText = filter;
      }
    } else if (noMatchRow) {
      noMatchRow.style.display = "none";
    }

    if (countId) {
      var countEl = document.getElementById(countId);
      if (countEl) {
        countEl.innerText = filter ? matchCount + ' of ' + totalRows + ' Matching' : totalRows + ' Total';
      }
    }
  }

  $(document).ready(function() {
    if (window.location.hash) {
      $('a[href="' + window.location.hash + '"]').tab('show');
    }
  });
</script>
@endpush

@section('navbar')
  <!-- Fixed Modern Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top modern-navbar">
    <div class="container-fluid px-lg-4">
      <a class="navbar-brand" href="{{ route('pharmacist.dashboard') }}">
        <span class="brand-icon-box" style="background: linear-gradient(135deg, #059669, #06b6d4);"><i class="fa fa-medkit"></i></span>
        <span>DocOp <span style="font-weight: 400; opacity: 0.85; font-size: 0.85em;">Dispensary Portal</span></span>
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navContent" aria-controls="navContent" aria-expanded="false" aria-label="Toggle navigation" style="border:none;">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navContent">
        <ul class="navbar-nav ml-auto align-items-center">
          <li class="nav-item mr-3">
            <button type="button" class="btn-theme-toggle" onclick="toggleTheme()" title="Switch Light/Dark Mode">
              <i class="fa fa-moon-o"></i>
            </button>
          </li>
          <li class="nav-item mr-3">
            <div class="user-profile-badge">
              <div class="user-avatar" style="background: linear-gradient(135deg, #059669, #10b981);"><i class="fa fa-user-circle"></i></div>
              <span>Pharmacist</span>
            </div>
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
  <!-- Dispensary Dashboard Container -->
  <div class="dashboard-wrapper">
    <div class="container-fluid px-lg-4">

      <!-- Header Banner -->
      <div class="dashboard-header-banner" style="background: linear-gradient(135deg, #064e3b 0%, #065f46 45%, #0d9488 100%);">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h1 class="dashboard-title">Pharmacy & Medication Dispensary 💊</h1>
            <p class="dashboard-subtitle">Fulfill patient prescription statements, allocate medications, and update invoice records.</p>
          </div>
          <div class="col-md-4 text-md-right mt-3 mt-md-0">
            <span class="badge-modern badge-modern-primary" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); font-size: 0.85rem; padding: 0.5rem 1rem;">
              <i class="fa fa-check-circle"></i> Dispensary Active
            </span>
          </div>
        </div>
      </div>

      <!-- Main Layout -->
      <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
          <div class="modern-sidebar">
            <div class="sidebar-heading">Pharmacy Menu</div>
            <div class="list-group" id="list-tab" role="tablist">
              <a class="list-group-item list-group-item-action active" id="list-dash-list" data-toggle="list" href="#list-dash" role="tab">
                <i class="fa fa-th-large"></i> Dashboard Overview
              </a>
              <a class="list-group-item list-group-item-action" href="#list-before" id="list-pres-before" role="tab" data-toggle="list">
                <i class="fa fa-clock-o text-warning"></i> Pending Prescriptions
              </a>
              <a class="list-group-item list-group-item-action" href="#list-settings" id="list-adoc-list" role="tab" data-toggle="list">
                <i class="fa fa-plus-circle text-success"></i> Dispense Medication
              </a>
              <a class="list-group-item list-group-item-action" href="#list-pres" id="list-pres-list" role="tab" data-toggle="list">
                <i class="fa fa-check-circle text-primary"></i> Fulfilled Prescriptions
              </a>
              <a class="list-group-item list-group-item-action" href="#list-stock" id="list-stock-list" role="tab" data-toggle="list">
                <i class="fa fa-cubes text-info"></i> Dispensary Stock Inventory
              </a>
            </div>
          </div>
        </div>

        <!-- Content Area -->
        <div class="col-lg-9">
          <div class="tab-content" id="nav-tabContent">

            <!-- ================= DASHBOARD OVERVIEW ================= -->
            <div class="tab-pane fade show active" id="list-dash" role="tabpanel">
              @if(isset($lowStockCount) && $lowStockCount > 0)
                <div class="alert alert-warning d-flex flex-wrap align-items-center justify-content-between p-3 mb-4" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border: 1.5px solid #fde68a; border-radius: 14px; color: #92400e;">
                  <div class="d-flex align-items-center mb-2 mb-md-0">
                    <span style="font-size: 1.6rem; margin-right: 0.85rem;">⚠️</span>
                    <div>
                      <strong style="color: #92400e; font-size: 0.95rem;">Low Inventory Alert: {{ $lowStockCount }} Item(s) Need Restocking</strong>
                      <div style="font-size: 0.825rem; color: #b45309;">Stock levels for critical medications have dropped to or below 10 units.</div>
                    </div>
                  </div>
                  <div>
                    <button type="button" class="btn-modern-primary" style="padding: 0.4rem 0.9rem; font-size: 0.8rem; background: #d97706; border-color: #d97706;" onclick="clickDiv('#list-stock-list')">
                      <i class="fa fa-cubes mr-1"></i> Review Stock Levels
                    </button>
                  </div>
                </div>
              @endif

              <div class="row">
                <div class="col-md-3 mb-4">
                  <div class="stat-card">
                    <div class="stat-card-icon-box stat-icon-amber">
                      <i class="fa fa-hourglass-half"></i>
                    </div>
                    <div class="stat-card-title">Prescriptions</div>
                    <div class="stat-card-value">{{ $prescriptions->count() }} Scripts</div>
                    <p style="font-size: 0.825rem; color: #64748b; margin-bottom: 1rem;">View doctor diagnoses awaiting medication assignment.</p>
                    <a class="stat-card-link" onclick="clickDiv('#list-pres-before')">
                      View pending <i class="fa fa-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <div class="col-md-3 mb-4">
                  <div class="stat-card">
                    <div class="stat-card-icon-box stat-icon-emerald">
                      <i class="fa fa-plus-square"></i>
                    </div>
                    <div class="stat-card-title">Dispense Drug</div>
                    <div class="stat-card-value">Add Medicine</div>
                    <p style="font-size: 0.825rem; color: #64748b; margin-bottom: 1rem;">Assign drugs and medication fees to prescriptions.</p>
                    <a class="stat-card-link" onclick="clickDiv('#list-adoc-list')">
                      Dispense now <i class="fa fa-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <div class="col-md-3 mb-4">
                  <div class="stat-card">
                    <div class="stat-card-icon-box stat-icon-blue">
                      <i class="fa fa-check-square-o"></i>
                    </div>
                    <div class="stat-card-title">Dispensary Stock</div>
                    <div class="stat-card-value">{{ $medicines->count() }} Items</div>
                    <p style="font-size: 0.825rem; color: #64748b; margin-bottom: 1rem;">Inventory of medicines issued and unit pricing.</p>
                    <a class="stat-card-link" onclick="clickDiv('#list-stock-list')">
                      View inventory <i class="fa fa-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <div class="col-md-3 mb-4">
                  <div class="stat-card">
                    <div class="stat-card-icon-box {{ ($lowStockCount ?? 0) > 0 ? 'stat-icon-amber' : 'stat-icon-emerald' }}">
                      <i class="fa fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-card-title">Stock Status</div>
                    <div class="stat-card-value">{{ $lowStockCount ?? 0 }} Low Items</div>
                    <p style="font-size: 0.825rem; color: #64748b; margin-bottom: 1rem;">Medications requiring immediate replenishment.</p>
                    <a class="stat-card-link" onclick="clickDiv('#list-stock-list')">
                      Restock items <i class="fa fa-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <!-- ================= PENDING PRESCRIPTIONS ================= -->
            <div class="tab-pane fade" id="list-before" role="tabpanel">
              <div class="modern-card">
                <div class="modern-card-header">
                  <h4 class="modern-card-title"><i class="fa fa-clock-o text-warning"></i> Pending Prescriptions (Before Medication)</h4>
                </div>

                <!-- Instant Table Search Filter -->
                <div class="table-filter-group">
                  <div class="table-filter-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="pharPendingSearch" class="table-filter-input" placeholder="Search pending prescriptions by doctor, patient, or diagnosis..." onkeyup="filterTable('pharPendingSearch', 'pharPendingBody', 'pharPendingCount')">
                    <span class="search-kbd-hint"><kbd>/</kbd></span>
                  </div>
                  <span class="table-filter-badge" id="pharPendingCount">{{ $prescriptions->count() }} Pending</span>
                </div>

                <div class="modern-table-card">
                  <div class="table-responsive">
                    <table class="modern-table">
                      <thead>
                        <tr>
                          <th>Doctor</th>
                          <th>Patient ID</th>
                          <th>App ID</th>
                          <th>Patient Name</th>
                          <th>Date & Time</th>
                          <th>Diagnosis</th>
                          <th>Allergy</th>
                          <th>Doctor Advice</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="pharPendingBody">
                        @forelse ($prescriptions as $pres)
                          <tr>
                            <td style="font-weight: 600;">Dr. {{ $pres->doctor }}</td>
                            <td>
                              <span class="badge-modern badge-modern-primary" style="cursor: pointer;" onclick="copyToClipboard('{{ $pres->pid }}', 'Copied Patient ID #{{ $pres->pid }}')" title="Click to copy Patient ID">
                                #{{ $pres->pid }} <i class="fa fa-clone ml-1" style="font-size: 0.7rem; opacity: 0.7;"></i>
                              </span>
                            </td>
                            <td>
                              <span class="badge-modern badge-modern-primary" style="cursor: pointer;" onclick="copyToClipboard('{{ $pres->ID }}', 'Copied App ID #{{ $pres->ID }}')" title="Click to copy App ID">
                                #{{ $pres->ID }} <i class="fa fa-clone ml-1" style="font-size: 0.7rem; opacity: 0.7;"></i>
                              </span>
                            </td>
                            <td style="font-weight: 600; color: #0f172a;">{{ $pres->fname }} {{ $pres->lname }}</td>
                            <td>
                              {{ $pres->appdate }}<br>
                              <small class="text-muted">{{ $pres->apptime }}</small>
                            </td>
                            <td><span class="badge-modern badge-modern-primary">{{ $pres->disease }}</span></td>
                            <td>{{ $pres->allergy }}</td>
                            <td style="max-width: 200px;">{{ $pres->prescription }}</td>
                            <td>
                              <div class="d-flex align-items-center" style="gap: 0.35rem;">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="quickDispense('{{ addslashes($pres->doctor) }}', '{{ addslashes($pres->medicine ?? '') }}')">
                                  <i class="fa fa-medkit"></i> Dispense
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" title="Copy Rx Details" onclick="copyToClipboard('Patient: {{ addslashes($pres->fname . ' ' . $pres->lname) }} (ID #{{ $pres->pid }}), Rx #{{ $pres->ID }}, Diagnosis: {{ addslashes($pres->disease) }}, Advice: {{ addslashes($pres->prescription) }}', 'Copied prescription details!')">
                                  <i class="fa fa-clone"></i>
                                </button>
                              </div>
                            </td>
                          </tr>
                        @empty
                          <tr class="no-filter-row"><td colspan="9" class="text-center py-4 text-muted">No prescriptions pending.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- ================= ADD MEDICINE FORM ================= -->
            <div class="tab-pane fade" id="list-settings" role="tabpanel">
              <div class="modern-card">
                <div class="modern-card-header">
                  <h4 class="modern-card-title"><i class="fa fa-medkit text-primary"></i> Allocate & Dispense Medicine</h4>
                </div>

                <form method="POST" action="{{ route('pharmacist.medicine.add') }}" style="max-width: 700px;">
                  @csrf
                  <div class="modern-form-group">
                    <label class="modern-form-label">Doctor Name *</label>
                    <div class="input-icon-wrapper">
                      <i class="fa fa-user-md form-icon"></i>
                      @if(isset($doctors) && $doctors->count() > 0)
                        <select name="doctor" class="modern-input" required style="height: 48px;">
                          <option value="" disabled selected>Select Prescribing Doctor</option>
                          @foreach($doctors as $doc)
                            <option value="{{ $doc->username }}">Dr. {{ $doc->username }} ({{ $doc->spec }})</option>
                          @endforeach
                        </select>
                      @else
                        <input type="text" class="modern-input" name="doctor" placeholder="e.g. ashok" required>
                      @endif
                    </div>
                  </div>

                  <div class="modern-form-group">
                    <label class="modern-form-label">Select Prescribed Medication *</label>
                    <select name="special" class="modern-input" id="special" required="required" style="height: 48px;">
                      <option value="" disabled selected>Select Medicine from Stock / Formulary</option>
                      
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
                    <label class="modern-form-label">Medicine / Pharmacy Fee ($) *</label>
                    <div class="input-icon-wrapper">
                      <i class="fa fa-usd form-icon"></i>
                      <input type="number" class="modern-input" name="bill" placeholder="e.g. 150" required>
                    </div>
                  </div>

                  <div class="mt-4">
                    <button type="submit" class="btn-modern-primary" style="max-width: 250px;">
                      <i class="fa fa-plus-circle"></i> Add Medication
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- ================= FULFILLED PRESCRIPTIONS ================= -->
            <div class="tab-pane fade" id="list-pres" role="tabpanel">
              <div class="modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                  <h4 class="modern-card-title mb-0"><i class="fa fa-check-circle text-success"></i> Fulfilled Prescriptions</h4>
                  <button type="button" class="btn-modern-outline" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;" onclick="exportTableToCSV('pharFulfilledTable', 'Pharmacist_Fulfilled_Prescriptions.csv')">
                    <i class="fa fa-download mr-1"></i> Export CSV
                  </button>
                </div>

                <!-- Instant Table Search Filter -->
                <div class="table-filter-group">
                  <div class="table-filter-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="pharFulfilledSearch" class="table-filter-input" placeholder="Search fulfilled prescriptions by doctor, patient, diagnosis, or medicine..." onkeyup="filterTable('pharFulfilledSearch', 'pharFulfilledBody', 'pharFulfilledCount')">
                    <span class="search-kbd-hint"><kbd>/</kbd></span>
                  </div>
                  <span class="table-filter-badge" id="pharFulfilledCount">{{ $prescriptions->count() }} Records</span>
                </div>

                <div class="modern-table-card">
                  <div class="table-responsive">
                    <table class="modern-table" id="pharFulfilledTable">
                      <thead>
                        <tr>
                          <th>Doctor</th>
                          <th>Patient ID</th>
                          <th>App ID</th>
                          <th>Patient Name</th>
                          <th>Date</th>
                          <th>Condition</th>
                          <th>Allergy</th>
                          <th>Doctor Advice</th>
                          <th>Medicine Issued</th>
                        </tr>
                      </thead>
                      <tbody id="pharFulfilledBody">
                        @forelse ($prescriptions as $pres)
                          <tr>
                            <td style="font-weight: 600;">Dr. {{ $pres->doctor }}</td>
                            <td>
                              <span class="badge-modern badge-modern-primary" style="cursor: pointer;" onclick="copyToClipboard('{{ $pres->pid }}', 'Copied Patient ID #{{ $pres->pid }}')" title="Click to copy Patient ID">
                                #{{ $pres->pid }} <i class="fa fa-clone ml-1" style="font-size: 0.7rem; opacity: 0.7;"></i>
                              </span>
                            </td>
                            <td>
                              <span class="badge-modern badge-modern-primary" style="cursor: pointer;" onclick="copyToClipboard('{{ $pres->ID }}', 'Copied App ID #{{ $pres->ID }}')" title="Click to copy App ID">
                                #{{ $pres->ID }} <i class="fa fa-clone ml-1" style="font-size: 0.7rem; opacity: 0.7;"></i>
                              </span>
                            </td>
                            <td style="font-weight: 600; color: #0f172a;">{{ $pres->fname }} {{ $pres->lname }}</td>
                            <td>{{ $pres->appdate }}</td>
                            <td><span class="badge-modern badge-modern-primary">{{ $pres->disease }}</span></td>
                            <td>{{ $pres->allergy }}</td>
                            <td style="max-width: 180px;">{{ $pres->prescription }}</td>
                            <td>
                              @if (!empty($pres->medicine))
                                <span class="badge-modern badge-modern-success">{{ $pres->medicine }}</span>
                              @else
                                <span class="badge-modern badge-modern-warning">Pending Dispense</span>
                              @endif
                            </td>
                          </tr>
                        @empty
                          <tr class="no-filter-row"><td colspan="9" class="text-center py-4 text-muted">No prescriptions found.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- ================= DISPENSARY STOCK INVENTORY ================= -->
            <div class="tab-pane fade" id="list-stock" role="tabpanel">
              <div class="modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                  <h4 class="modern-card-title mb-0"><i class="fa fa-cubes text-info"></i> Dispensary Stock & Live Inventory</h4>
                  <div class="d-flex align-items-center" style="gap: 0.5rem;">
                    <button type="button" class="btn-modern-outline" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;" onclick="exportTableToCSV('pharStockTable', 'Pharmacist_Stock_Inventory.csv')">
                      <i class="fa fa-download mr-1"></i> Export CSV
                    </button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="clickDiv('#list-adoc-list')">
                      <i class="fa fa-plus"></i> Add New Stock
                    </button>
                  </div>
                </div>

                <!-- Instant Table Search Filter -->
                <div class="table-filter-group">
                  <div class="table-filter-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="pharStockSearch" class="table-filter-input" placeholder="Search stock inventory by medicine name or prescribing doctor..." onkeyup="filterTable('pharStockSearch', 'pharStockBody', 'pharStockCount')">
                    <span class="search-kbd-hint"><kbd>/</kbd></span>
                  </div>
                  <span class="table-filter-badge" id="pharStockCount">{{ $medicines->count() }} Stock Items</span>
                </div>

                <div class="modern-table-card">
                  <div class="table-responsive">
                    <table class="modern-table" id="pharStockTable">
                      <thead>
                        <tr>
                          <th>Item #</th>
                          <th>Medicine Name</th>
                          <th>Prescribing Doctor</th>
                          <th>Dispense Price</th>
                          <th>Stock Level</th>
                          <th>Inventory Actions</th>
                        </tr>
                      </thead>
                      <tbody id="pharStockBody">
                        @forelse ($medicines as $med)
                          <tr>
                            <td>#{{ $med->id }}</td>
                            <td style="font-weight: 600; color: #0f172a;">{{ $med->medicine }}</td>
                            <td>Dr. {{ $med->doctor ?? 'DocOp Formulary' }}</td>
                            <td>
                              <span class="badge-modern badge-modern-success">
                                ${{ number_format($med->bill, 2) }}
                              </span>
                            </td>
                            <td>
                              @if(($med->stock_qty ?? 50) <= 10)
                                <span class="badge-modern badge-modern-danger">
                                  <i class="fa fa-exclamation-triangle"></i> Low Stock ({{ $med->stock_qty ?? 0 }} left)
                                </span>
                              @else
                                <span class="badge-modern badge-modern-success">
                                  <i class="fa fa-cubes"></i> {{ $med->stock_qty ?? 50 }} Units
                                </span>
                              @endif
                            </td>
                            <td>
                              <div class="d-flex align-items-center" style="gap: 0.35rem;">
                                <form method="POST" action="{{ route('pharmacist.medicine.restock', $med->id) }}" class="d-inline">
                                  @csrf
                                  <input type="hidden" name="qty" value="20">
                                  <button type="submit" class="btn btn-sm btn-outline-success" title="Restock +20 units immediately">
                                    <i class="fa fa-plus-circle"></i> +20
                                  </button>
                                </form>

                                <form method="POST" action="{{ route('pharmacist.medicine.delete', $med->id) }}" onsubmit="return confirm('Are you sure you want to remove this medicine item?');" class="d-inline">
                                  @csrf
                                  <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove Item">
                                    <i class="fa fa-trash-o"></i>
                                  </button>
                                </form>
                              </div>
                            </td>
                          </tr>
                        @empty
                          <tr class="no-filter-row"><td colspan="6" class="text-center py-4 text-muted">No medicines found in stock. Use 'Dispense Medication' to add items.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
