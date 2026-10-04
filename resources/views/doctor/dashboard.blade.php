@extends('layouts.app')

@section('title', 'Doctor Portal - DocOp Healthcare')
@section('body-class', 'light-page-wrapper')

@push('scripts')
<script>
  function clickDiv(id) {
    var elem = document.querySelector(id);
    if (elem) elem.click();
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

  function openRxModal(doctor, pid, appid, patientName, date, time, disease, allergy, medicine, prescription) {
    document.getElementById('rxDoctorName').innerText = 'Dr. ' + (doctor || 'Attending Physician');
    document.getElementById('rxDoctorSignature').innerText = 'Dr. ' + (doctor || 'Physician');
    document.getElementById('rxPatName').innerText = patientName || 'Patient';
    document.getElementById('rxPatId').innerText = '#' + (pid || '--');
    document.getElementById('rxTokenId').innerText = '#' + (appid || '--');
    document.getElementById('rxDateBadge').innerText = 'Date: ' + (date || '') + ' ' + (time || '');
    document.getElementById('rxDiseaseVal').innerText = disease || 'General Medical Consultation';
    document.getElementById('rxAllergyVal').innerText = (allergy && allergy.trim() !== '') ? allergy : 'None Reported';
    document.getElementById('rxMedVal').innerText = medicine || 'Prescribed as per clinical notes below';
    document.getElementById('rxInstructionVal').innerText = prescription || 'Follow standard dosage instructions as directed by consulting physician.';
    
    $('#modalRxSlip').modal('show');
  }

  function openDocAppPass(appid, doctor, date, time, patName, pid) {
    document.getElementById('passTokenNum').innerText = '#' + (appid || '--');
    document.getElementById('passDocName').innerText = 'Dr. ' + (doctor || 'Specialist');
    document.getElementById('passPatName').innerText = patName + ' (#' + pid + ')';
    document.getElementById('passDate').innerText = date || '-';
    document.getElementById('passTime').innerText = time || '-';
    document.getElementById('passBarcodeText').innerText = 'DOC-OP-TK-' + appid + '-P' + pid;

    $('#modalDocAppPass').modal('show');
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
      <a class="navbar-brand" href="{{ route('doctor.dashboard') }}">
        <span class="brand-icon-box"><i class="fa fa-user-md"></i></span>
        <span>DocOp <span style="font-weight: 400; opacity: 0.85; font-size: 0.85em;">Doctor Portal</span></span>
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navContent" aria-controls="navContent" aria-expanded="false" aria-label="Toggle navigation" style="border:none;">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navContent">
        <!-- Search bar inside doctor navbar -->
        <form class="form-inline my-2 my-lg-0 ml-auto mr-lg-3" method="POST" action="{{ route('search') }}">
          @csrf
          <input type="hidden" name="search_type" value="doctor_patient">
          <div class="modern-search-box" style="padding: 2px 4px;">
            <input type="text" placeholder="Search patient contact..." name="contact" required style="font-size: 0.85rem; padding: 0.35rem 0.65rem;">
            <button type="submit" style="padding: 0.35rem 0.85rem; font-size: 0.8rem;">
              <i class="fa fa-search"></i>
            </button>
          </div>
        </form>

        <ul class="navbar-nav align-items-center">
          <li class="nav-item mr-3">
            <button type="button" class="btn-theme-toggle" onclick="toggleTheme()" title="Switch Light/Dark Mode">
              <i class="fa fa-moon-o"></i>
            </button>
          </li>
          <li class="nav-item mr-3">
            <div class="user-profile-badge">
              <div class="user-avatar" style="background: linear-gradient(135deg, #10b981, #06b6d4);"><i class="fa fa-stethoscope"></i></div>
              <span>Dr. {{ $doctor }}</span>
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
  <!-- Doctor Dashboard Container -->
  <div class="dashboard-wrapper">
    <div class="container-fluid px-lg-4">

      <!-- Welcome Banner -->
      <div class="dashboard-header-banner" style="background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #0284c7 100%);">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h1 class="dashboard-title">Welcome, Dr. {{ $doctor }} 🩺</h1>
            <p class="dashboard-subtitle">Manage patient consultations, diagnose medical records, and issue digital prescriptions.</p>
          </div>
          <div class="col-md-4 text-md-right mt-3 mt-md-0">
            <span class="badge-modern badge-modern-primary" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); font-size: 0.85rem; padding: 0.5rem 1rem;">
              <i class="fa fa-check-circle"></i> On Duty & Available
            </span>
          </div>
        </div>
      </div>

      <!-- Main Layout -->
      <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
          <div class="modern-sidebar">
            <div class="sidebar-heading">Doctor Console</div>
            <div class="list-group" id="list-tab" role="tablist">
              <a class="list-group-item list-group-item-action active" href="#list-dash" role="tab" data-toggle="list">
                <i class="fa fa-th-large"></i> Dashboard
              </a>
              <a class="list-group-item list-group-item-action" href="#list-app" id="list-app-list" role="tab" data-toggle="list">
                <i class="fa fa-calendar-check-o"></i> Patient Appointments
              </a>
              <a class="list-group-item list-group-item-action" href="#list-pres" id="list-pres-list" role="tab" data-toggle="list">
                <i class="fa fa-file-text-o"></i> Issued Prescriptions
              </a>
            </div>
          </div>
        </div>

        <!-- Content Area -->
        <div class="col-lg-9">
          <div class="tab-content" id="nav-tabContent">

            <!-- ================= DASHBOARD OVERVIEW ================= -->
            <div class="tab-pane fade show active" id="list-dash" role="tabpanel">
              <div class="row">
                <div class="col-md-6 mb-4">
                  <div class="stat-card">
                    <div class="stat-card-icon-box stat-icon-blue">
                      <i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div class="stat-card-title">Consultation Schedule</div>
                    <div class="stat-card-value">{{ $appointments->count() }} Appointments</div>
                    <p style="font-size: 0.825rem; color: #64748b; margin-bottom: 1rem;">Review booked patient visits and prescribe medications.</p>
                    <a class="stat-card-link" onclick="clickDiv('#list-app-list')">
                      View appointments <i class="fa fa-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="stat-card">
                    <div class="stat-card-icon-box stat-icon-emerald">
                      <i class="fa fa-medkit"></i>
                    </div>
                    <div class="stat-card-title">Prescription Records</div>
                    <div class="stat-card-value">{{ $prescriptions->count() }} Records</div>
                    <p style="font-size: 0.825rem; color: #64748b; margin-bottom: 1rem;">Browse previously prescribed medical treatments and diseases.</p>
                    <a class="stat-card-link" onclick="clickDiv('#list-pres-list')">
                      Open prescription list <i class="fa fa-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <!-- ================= APPOINTMENTS LIST ================= -->
            <div class="tab-pane fade" id="list-app" role="tabpanel">
              <div class="modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                  <h4 class="modern-card-title mb-0"><i class="fa fa-calendar-check-o text-primary"></i> Patient Appointments</h4>
                  <button type="button" class="btn-modern-outline" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;" onclick="exportTableToCSV('docAppTable', 'Doctor_Appointments.csv')">
                    <i class="fa fa-download mr-1"></i> Export CSV
                  </button>
                </div>

                <!-- Instant Table Search Filter -->
                <div class="table-filter-group">
                  <div class="table-filter-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="docAppSearch" class="table-filter-input" placeholder="Quick search appointments by patient, ID, contact, or date..." onkeyup="filterTable('docAppSearch', 'docAppBody', 'docAppCount')">
                    <span class="search-kbd-hint"><kbd>/</kbd></span>
                  </div>
                  <span class="table-filter-badge" id="docAppCount">{{ $appointments->count() }} Appointments</span>
                </div>

                <div class="modern-table-card">
                  <div class="table-responsive">
                    <table class="modern-table" id="docAppTable">
                      <thead>
                        <tr>
                          <th>Patient ID</th>
                          <th>App ID</th>
                          <th>Patient Name</th>
                          <th>Gender</th>
                          <th>Contact Details</th>
                          <th>Date & Time</th>
                          <th>Status</th>
                          <th>Slip</th>
                          <th>Action</th>
                          <th>Consultation</th>
                        </tr>
                      </thead>
                      <tbody id="docAppBody">
                        @forelse ($appointments as $app)
                          <tr>
                            <td>
                              <span class="badge-modern badge-modern-primary" style="cursor: pointer;" onclick="copyToClipboard('{{ $app->pid }}', 'Copied Patient ID #{{ $app->pid }}')" title="Click to copy Patient ID">
                                #{{ $app->pid }} <i class="fa fa-clone ml-1" style="font-size: 0.7rem; opacity: 0.7;"></i>
                              </span>
                            </td>
                            <td>
                              <span class="badge-modern badge-modern-primary" style="cursor: pointer;" onclick="copyToClipboard('{{ $app->ID }}', 'Copied App ID #{{ $app->ID }}')" title="Click to copy App ID">
                                #{{ $app->ID }} <i class="fa fa-clone ml-1" style="font-size: 0.7rem; opacity: 0.7;"></i>
                              </span>
                            </td>
                            <td style="font-weight: 600; color: #0f172a;">{{ $app->fname }} {{ $app->lname }}</td>
                            <td>{{ $app->gender }}</td>
                            <td>
                              <small>{{ $app->email }}</small><br>
                              <small class="text-muted"><i class="fa fa-phone"></i> {{ $app->contact }}</small>
                            </td>
                            <td>
                              {{ $app->appdate }}<br>
                              <small class="text-muted">{{ $app->apptime }}</small>
                            </td>
                            <td>
                              @if ($app->userStatus == 1 && $app->doctorStatus == 1)
                                @if ($app->isCompleted())
                                  <span class="badge-modern badge-modern-primary"><i class="fa fa-check-circle"></i> Completed</span>
                                @else
                                  <span class="badge-modern badge-modern-warning"><i class="fa fa-clock-o"></i> Scheduled</span>
                                @endif
                              @elseif ($app->userStatus == 0 && $app->doctorStatus == 1)
                                <span class="badge-modern badge-modern-danger"><i class="fa fa-times"></i> Cancelled by Patient</span>
                              @elseif ($app->userStatus == 1 && $app->doctorStatus == 0)
                                <span class="badge-modern badge-modern-warning"><i class="fa fa-exclamation-triangle"></i> Cancelled by You</span>
                              @else
                                <span class="badge-modern badge-modern-danger">Cancelled</span>
                              @endif
                            </td>
                            <td>
                              <button type="button" class="btn-modern-outline" style="padding: 0.3rem 0.65rem; font-size: 0.75rem;" onclick="openDocAppPass('{{ $app->ID }}', '{{ addslashes($doctor) }}', '{{ $app->appdate }}', '{{ $app->apptime }}', '{{ addslashes($app->fname . ' ' . $app->lname) }}', '{{ $app->pid }}')">
                                <i class="fa fa-ticket"></i> Slip
                              </button>
                            </td>
                            <td>
                              @if ($app->userStatus == 1 && $app->doctorStatus == 1 && !$app->isCompleted())
                                <form method="POST" action="{{ route('doctor.appointment.cancel', $app->ID) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                  @csrf
                                  <button type="submit" class="btn-modern-danger border-0" style="padding: 0.35rem 0.75rem; font-size: 0.775rem;">
                                    <i class="fa fa-times"></i> Cancel
                                  </button>
                                </form>
                              @else
                                <span class="text-muted" style="font-size: 0.85rem;">{{ $app->isCompleted() ? 'Attended' : 'Cancelled' }}</span>
                              @endif
                            </td>
                            <td>
                              @if ($app->userStatus == 1 && $app->doctorStatus == 1)
                                @if ($app->isCompleted())
                                  <span class="badge-modern badge-modern-success" style="font-size: 0.775rem;">
                                    <i class="fa fa-check"></i> Attended
                                  </span>
                                @else
                                  <a href="{{ route('doctor.prescribe', [
                                      'pid' => $app->pid,
                                      'ID' => $app->ID,
                                      'fname' => $app->fname,
                                      'lname' => $app->lname,
                                      'appdate' => $app->appdate,
                                      'apptime' => $app->apptime
                                  ]) }}" class="btn-modern-success" style="text-decoration:none; padding: 0.35rem 0.75rem; font-size: 0.775rem;">
                                    <i class="fa fa-pencil-square-o"></i> Prescribe
                                  </a>
                                @endif
                              @else
                                <span class="text-muted">-</span>
                              @endif
                            </td>
                          </tr>
                        @empty
                          <tr class="no-filter-row"><td colspan="10" class="text-center py-4 text-muted">No appointments currently scheduled.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- ================= ISSUED PRESCRIPTIONS ================= -->
            <div class="tab-pane fade" id="list-pres" role="tabpanel">
              <div class="modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                  <h4 class="modern-card-title mb-0"><i class="fa fa-file-text-o text-primary"></i> Prescriptions Issued by Dr. {{ $doctor }}</h4>
                  <button type="button" class="btn-modern-outline" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;" onclick="exportTableToCSV('docPresTable', 'Doctor_Prescriptions.csv')">
                    <i class="fa fa-download mr-1"></i> Export CSV
                  </button>
                </div>

                <!-- Instant Table Search Filter -->
                <div class="table-filter-group">
                  <div class="table-filter-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="docPresSearch" class="table-filter-input" placeholder="Quick search prescriptions by patient, diagnosis, or medicine..." onkeyup="filterTable('docPresSearch', 'docPresBody', 'docPresCount')">
                    <span class="search-kbd-hint"><kbd>/</kbd></span>
                  </div>
                  <span class="table-filter-badge" id="docPresCount">{{ $prescriptions->count() }} Prescriptions</span>
                </div>

                <div class="modern-table-card">
                  <div class="table-responsive">
                    <table class="modern-table" id="docPresTable">
                      <thead>
                        <tr>
                          <th>Patient ID</th>
                          <th>Patient Name</th>
                          <th>App ID</th>
                          <th>Date & Time</th>
                          <th>Condition</th>
                          <th>Medication</th>
                          <th>Allergies</th>
                          <th>Clinical Notes</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="docPresBody">
                        @forelse ($prescriptions as $pres)
                          <tr>
                            <td>
                              <span class="badge-modern badge-modern-primary" style="cursor: pointer;" onclick="copyToClipboard('{{ $pres->pid }}', 'Copied Patient ID #{{ $pres->pid }}')" title="Click to copy Patient ID">
                                #{{ $pres->pid }} <i class="fa fa-clone ml-1" style="font-size: 0.7rem; opacity: 0.7;"></i>
                              </span>
                            </td>
                            <td style="font-weight: 600; color: #0f172a;">{{ $pres->fname }} {{ $pres->lname }}</td>
                            <td>
                              <span class="badge-modern badge-modern-primary" style="cursor: pointer;" onclick="copyToClipboard('{{ $pres->ID }}', 'Copied App ID #{{ $pres->ID }}')" title="Click to copy App ID">
                                #{{ $pres->ID }} <i class="fa fa-clone ml-1" style="font-size: 0.7rem; opacity: 0.7;"></i>
                              </span>
                            </td>
                            <td>
                              {{ $pres->appdate }}<br>
                              <small class="text-muted">{{ $pres->apptime }}</small>
                            </td>
                            <td><span class="badge-modern badge-modern-primary">{{ $pres->disease }}</span></td>
                            <td>
                              @if(!empty($pres->medicine))
                                <span class="badge-modern badge-modern-success" style="font-size: 0.8rem;"><i class="fa fa-medkit"></i> {{ $pres->medicine }}</span>
                              @else
                                <span class="text-muted" style="font-size: 0.8rem;">Clinical Plan</span>
                              @endif
                            </td>
                            <td>
                              @if(!empty($pres->allergy))
                                <span class="text-danger" style="font-weight: 500; font-size: 0.825rem;"><i class="fa fa-exclamation-circle"></i> {{ $pres->allergy }}</span>
                              @else
                                <span class="text-muted" style="font-size: 0.825rem;">None</span>
                              @endif
                            </td>
                            <td style="max-width: 220px; font-size: 0.85rem;">{{ $pres->prescription }}</td>
                            <td>
                              <button type="button" class="btn-modern-success border-0" style="padding: 0.35rem 0.75rem; font-size: 0.775rem;" onclick="openRxModal('{{ addslashes($doctor) }}', '{{ addslashes($pres->pid) }}', '{{ addslashes($pres->ID) }}', '{{ addslashes($pres->fname . ' ' . $pres->lname) }}', '{{ addslashes($pres->appdate) }}', '{{ addslashes($pres->apptime) }}', '{{ addslashes($pres->disease) }}', '{{ addslashes($pres->allergy) }}', '{{ addslashes($pres->medicine ?? '') }}', '{{ addslashes($pres->prescription) }}')">
                                <i class="fa fa-file-text-o"></i> View Rx
                              </button>
                            </td>
                          </tr>
                        @empty
                          <tr class="no-filter-row"><td colspan="9" class="text-center py-4 text-muted">No prescriptions issued yet.</td></tr>
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

  <!-- ================= PRESCRIPTION (Rx) PRINTABLE MODAL ================= -->
  <div class="modal fade" id="modalRxSlip" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content rx-doc-modal">
        <div class="rx-header-band">
          <div class="rx-hospital-brand">
            <h3><i class="fa fa-heartbeat text-info"></i> DocOp Healthcare Center</h3>
            <p>Multi-Specialty Clinic & Emergency Center &bull; Registration: DOC-OP-2026-MED</p>
          </div>
          <div class="rx-meta-tag">
            <div style="font-weight: 700; font-size: 0.95rem;">Official Digital Prescription</div>
            <div id="rxDateBadge">Date: -</div>
          </div>
        </div>

        <div class="rx-body-content printable-area">
          <div class="rx-patient-bar">
            <div class="rx-bar-item">
              <div class="label">Patient Name</div>
              <div class="val" id="rxPatName">-</div>
            </div>
            <div class="rx-bar-item">
              <div class="label">Patient ID</div>
              <div class="val" id="rxPatId">-</div>
            </div>
            <div class="rx-bar-item">
              <div class="label">Prescribing Specialist</div>
              <div class="val" id="rxDoctorName">Dr. {{ $doctor }}</div>
            </div>
            <div class="rx-bar-item">
              <div class="label">Appointment Token</div>
              <div class="val" id="rxTokenId">-</div>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6 mb-2">
              <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.75rem 1rem;">
                <small class="text-uppercase text-muted font-weight-bold" style="font-size: 0.7rem;">Clinical Diagnosis</small>
                <div id="rxDiseaseVal" style="font-weight: 600; color: #0f172a; font-size: 0.95rem;">-</div>
              </div>
            </div>
            <div class="col-md-6 mb-2">
              <div style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 10px; padding: 0.75rem 1rem;">
                <small class="text-uppercase text-danger font-weight-bold" style="font-size: 0.7rem;"><i class="fa fa-warning"></i> Known Patient Allergies</small>
                <div id="rxAllergyVal" style="font-weight: 600; color: #9f1239; font-size: 0.95rem;">-</div>
              </div>
            </div>
          </div>

          <div class="rx-main-symbol">℞</div>

          <div class="rx-medicine-box">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
              <strong style="color: #15803d; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.04em;">
                <i class="fa fa-medkit mr-1"></i> Prescribed Medication & Formulary
              </strong>
              <span class="badge-modern badge-modern-success">DocOp Verified</span>
            </div>
            <div id="rxMedVal" style="font-size: 1.15rem; font-weight: 700; color: #14532d;">-</div>
          </div>

          <div class="rx-notes-box">
            <strong style="color: #475569; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.04em; display: block; margin-bottom: 0.35rem;">
              <i class="fa fa-info-circle mr-1"></i> Clinical Instructions & Dosage Schedule
            </strong>
            <div id="rxInstructionVal" style="color: #1e293b; font-size: 0.925rem; line-height: 1.5;">-</div>
          </div>

          <div class="rx-footer-stamp">
            <div>
              <small class="text-muted d-block">&bull; Valid at DocOp Dispensary & authorized pharmacies.</small>
              <small class="text-muted d-block">&bull; Emergency line: <strong>+1 (800) 911-DOCOP</strong></small>
            </div>
            <div class="rx-sign-box">
              <div style="font-family: 'Brush Script MT', cursive, sans-serif; font-size: 1.5rem; color: #1e3a8a;" id="rxDoctorSignature">Dr. {{ $doctor }}</div>
              <div class="rx-sign-line"></div>
              <small class="text-muted text-uppercase" style="font-weight: 600; font-size: 0.7rem;">Authorized Medical Officer</small>
            </div>
          </div>
        </div>

        <div class="modal-footer no-print" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
          <button type="button" class="btn-modern-outline" data-dismiss="modal">Close</button>
          <button type="button" class="btn-modern-primary" style="max-width: 200px;" onclick="window.print()">
            <i class="fa fa-print"></i> Print Rx Slip
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ================= APPOINTMENT TOKEN PASS PRINTABLE MODAL ================= -->
  <div class="modal fade" id="modalDocAppPass" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 20px 40px rgba(15,23,42,0.15);">
        <div class="modal-body p-4 printable-area">
          <div class="token-pass-card">
            <div class="token-pass-header">
              <div>
                <h4 style="margin: 0; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 0.5rem; font-size: 1.25rem;">
                  <i class="fa fa-heartbeat text-primary"></i> DocOp Medical Pass
                </h4>
                <p style="margin: 0.2rem 0 0; font-size: 0.8rem; color: #64748b;">Hospital Consultation Check-In Token</p>
              </div>
              <div class="token-big-badge">
                <div class="token-num" id="passTokenNum">#--</div>
                <div class="token-lbl">Token</div>
              </div>
            </div>

            <div class="token-details-grid">
              <div>
                <div class="text-muted text-uppercase" style="font-size: 0.7rem; font-weight: 700;">Specialist Doctor</div>
                <div style="font-weight: 700; color: #0f172a;" id="passDocName">-</div>
              </div>
              <div>
                <div class="text-muted text-uppercase" style="font-size: 0.7rem; font-weight: 700;">Patient</div>
                <div style="font-weight: 700; color: #0f172a;" id="passPatName">-</div>
              </div>
              <div>
                <div class="text-muted text-uppercase" style="font-size: 0.7rem; font-weight: 700;">Appointment Date</div>
                <div style="font-weight: 700; color: #0f172a;" id="passDate">-</div>
              </div>
              <div>
                <div class="text-muted text-uppercase" style="font-size: 0.7rem; font-weight: 700;">Time Slot</div>
                <div style="font-weight: 700; color: #0f172a;" id="passTime">-</div>
              </div>
              <div>
                <div class="text-muted text-uppercase" style="font-size: 0.7rem; font-weight: 700;">Hospital Department</div>
                <div style="font-weight: 700; color: #2563eb;">Outpatient Clinic (OPD)</div>
              </div>
              <div>
                <div class="text-muted text-uppercase" style="font-size: 0.7rem; font-weight: 700;">Verification Status</div>
                <span class="badge-modern badge-modern-success"><i class="fa fa-check"></i> Doctor Verified</span>
              </div>
            </div>

            <div class="barcode-simulation">
              <div class="barcode-lines"></div>
              <small class="text-muted" style="font-size: 0.75rem; letter-spacing: 0.15em; font-family: monospace;" id="passBarcodeText">DOC-OP-TOKEN</small>
            </div>
            <p class="text-center text-muted mt-3 mb-0" style="font-size: 0.75rem;">
              Doctor's Consultation Slip & Reception Desk Verification Pass.
            </p>
          </div>
        </div>
        <div class="modal-footer no-print" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
          <button type="button" class="btn-modern-outline" data-dismiss="modal">Close</button>
          <button type="button" class="btn-modern-primary" style="max-width: 180px;" onclick="window.print()">
            <i class="fa fa-print"></i> Print Slip
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection
