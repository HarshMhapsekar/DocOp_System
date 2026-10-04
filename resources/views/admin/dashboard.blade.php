@extends('layouts.app')

@section('title', 'Admin Dashboard - DocOp Healthcare')
@section('body-class', 'light-page-wrapper')

@push('scripts')
<script>
  var check = function() {
    var pass = document.getElementById('dpassword');
    var cpass = document.getElementById('cdpassword');
    var msg = document.getElementById('message');
    if (!pass || !cpass || !msg) return;

    if (pass.value && cpass.value) {
      if (pass.value == cpass.value) {
        msg.style.color = '#10b981';
        msg.innerHTML = '<i class="fa fa-check-circle"></i> Passwords match';
      } else {
        msg.style.color = '#ef4444';
        msg.innerHTML = '<i class="fa fa-times-circle"></i> Passwords do not match';
      }
    } else {
      msg.innerHTML = '';
    }
  };

  function alphaOnly(event) {
    var key = event.keyCode;
    return ((key >= 65 && key <= 90) || key == 8 || key == 32);
  }

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
        var cols = rows[0] && rows[0].children ? rows[0].children.length : 6;
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
      <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
        <span class="brand-icon-box" style="background: linear-gradient(135deg, #4f46e5, #06b6d4);"><i class="fa fa-shield"></i></span>
        <span>DocOp <span style="font-weight: 400; opacity: 0.85; font-size: 0.85em;">Admin Console</span></span>
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
              <div class="user-avatar" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);"><i class="fa fa-user-secret"></i></div>
              <span>Administrator</span>
            </div>
          </li>
          <li class="nav-item">
            <form method="POST" action="{{ route('auth.logout') }}" class="d-inline">
              @csrf
              <button type="submit" class="nav-link nav-btn-logout border-0" style="cursor: pointer;">
                <i class="fa fa-sign-out"></i> Sign Out
              </button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>
@endsection

@section('content')
  <!-- Admin Dashboard Container -->
  <div class="dashboard-wrapper">
    <div class="container-fluid px-lg-4">

      <!-- Executive Header Banner -->
      <div class="dashboard-header-banner" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #0369a1 100%);">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h1 class="dashboard-title">Hospital Operations & Administration 🛡️</h1>
            <p class="dashboard-subtitle">Real-time overview of hospital departments, doctors, registered patients, and appointment logs.</p>
          </div>
          <div class="col-md-4 text-md-right mt-3 mt-md-0">
            <span class="badge-modern badge-modern-primary" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); font-size: 0.85rem; padding: 0.5rem 1rem;">
              <i class="fa fa-database"></i> Database: {{ config('database.connections.mysql.database') }}
            </span>
          </div>
        </div>
      </div>

      <!-- Main Layout -->
      <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
          <div class="modern-sidebar">
            <div class="sidebar-heading">Administration Menu</div>
            <div class="list-group" id="list-tab" role="tablist">
              <a class="list-group-item list-group-item-action active" id="list-dash-list" data-toggle="list" href="#list-dash" role="tab">
                <i class="fa fa-th-large"></i> Dashboard Overview
              </a>
              <a class="list-group-item list-group-item-action" href="#list-doc" id="list-doc-list" role="tab" data-toggle="list">
                <i class="fa fa-user-md"></i> Doctor Directory
              </a>
              <a class="list-group-item list-group-item-action" href="#list-pat" id="list-pat-list" role="tab" data-toggle="list">
                <i class="fa fa-users"></i> Patient Registry
              </a>
              <a class="list-group-item list-group-item-action" href="#list-app" id="list-app-list" role="tab" data-toggle="list">
                <i class="fa fa-calendar-check-o"></i> Appointment Logs
              </a>
              <a class="list-group-item list-group-item-action" href="#list-pres" id="list-pres-list" role="tab" data-toggle="list">
                <i class="fa fa-file-text-o"></i> Prescriptions
              </a>
              
              <div class="sidebar-heading mt-3">Staff Management</div>
              <a class="list-group-item list-group-item-action" href="#list-settings" id="list-adoc-list" role="tab" data-toggle="list">
                <i class="fa fa-plus-circle text-success"></i> Add New Doctor
              </a>
              <a class="list-group-item list-group-item-action" href="#list-settings1" id="list-ddoc-list" role="tab" data-toggle="list">
                <i class="fa fa-trash-o text-danger"></i> Remove Doctor
              </a>

              <div class="sidebar-heading mt-3">Communications</div>
              <a class="list-group-item list-group-item-action" href="#list-mes" id="list-mes-list" role="tab" data-toggle="list">
                <i class="fa fa-envelope-o"></i> Patient Messages
              </a>
            </div>
          </div>
        </div>

        <!-- Content Area -->
        <div class="col-lg-9">
          <div class="tab-content" id="nav-tabContent">

            <!-- ================= DASHBOARD OVERVIEW ================= -->
            <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
              <div class="row">
                <div class="col-md-6 col-xl-3 mb-4">
                  <div class="stat-card">
                    <div class="stat-card-icon-box stat-icon-blue">
                      <i class="fa fa-user-md"></i>
                    </div>
                    <div class="stat-card-title">Staff Doctors</div>
                    <div class="stat-card-value">{{ $count_docs }}</div>
                    <a class="stat-card-link" onclick="clickDiv('#list-doc-list')">
                      View doctors <i class="fa fa-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <div class="col-md-6 col-xl-3 mb-4">
                  <div class="stat-card">
                    <div class="stat-card-icon-box stat-icon-emerald">
                      <i class="fa fa-users"></i>
                    </div>
                    <div class="stat-card-title">Total Patients</div>
                    <div class="stat-card-value">{{ $count_pats }}</div>
                    <a class="stat-card-link" onclick="clickDiv('#list-pat-list')">
                      Patient registry <i class="fa fa-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <div class="col-md-6 col-xl-3 mb-4">
                  <div class="stat-card">
                    <div class="stat-card-icon-box stat-icon-amber">
                      <i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div class="stat-card-title">Appointments</div>
                    <div class="stat-card-value">{{ $count_apps }}</div>
                    <a class="stat-card-link" onclick="clickDiv('#list-app-list')">
                      View schedules <i class="fa fa-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <div class="col-md-6 col-xl-3 mb-4">
                  <div class="stat-card">
                    <div class="stat-card-icon-box stat-icon-purple">
                      <i class="fa fa-file-text-o"></i>
                    </div>
                    <div class="stat-card-title">Prescriptions</div>
                    <div class="stat-card-value">{{ $count_pres }}</div>
                    <a class="stat-card-link" onclick="clickDiv('#list-pres-list')">
                      Check scripts <i class="fa fa-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>

              <!-- Quick Action Card Group -->
              <div class="row mt-2">
                <div class="col-md-6 mb-4">
                  <div class="modern-card">
                    <div class="modern-card-header">
                      <h4 class="modern-card-title"><i class="fa fa-user-plus text-primary"></i> Quick Register Doctor</h4>
                    </div>
                    <p style="color: #64748b; font-size: 0.9rem;">Quickly onboard a new medical specialist, assign specialization and consultancy fees.</p>
                    <button class="btn-modern-primary" onclick="clickDiv('#list-adoc-list')" style="max-width: 220px;">
                      <i class="fa fa-plus"></i> Add New Doctor
                    </button>
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="modern-card">
                    <div class="modern-card-header">
                      <h4 class="modern-card-title"><i class="fa fa-comments-o text-primary"></i> Patient Inquiries</h4>
                    </div>
                    <p style="color: #64748b; font-size: 0.9rem;">Review incoming feedback, support requests, and hospital questions from patients.</p>
                    <button class="btn-modern-primary" onclick="clickDiv('#list-mes-list')" style="max-width: 220px; background: linear-gradient(135deg, #059669, #10b981);">
                      <i class="fa fa-envelope-open-o"></i> View Inquiries
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- ================= DOCTOR DIRECTORY ================= -->
            <div class="tab-pane fade" id="list-doc" role="tabpanel" aria-labelledby="list-doc-list">
              <div class="modern-card">
                <div class="modern-card-header d-flex flex-wrap justify-content-between align-items-center" style="gap: 0.75rem;">
                  <h4 class="modern-card-title mb-0"><i class="fa fa-user-md text-primary"></i> Doctors Directory</h4>
                  <div class="d-flex align-items-center" style="gap: 0.5rem;">
                    <button type="button" class="btn-modern-outline" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;" onclick="exportTableToCSV('adminDocTable', 'DocOp_Doctors_Registry.csv')">
                      <i class="fa fa-download mr-1"></i> Export CSV
                    </button>
                    <!-- Unified Search Form -->
                    <form action="{{ route('search') }}" method="POST" style="max-width: 240px; width: 100%;">
                      @csrf
                      <input type="hidden" name="search_type" value="doctor">
                      <div class="modern-search-box">
                        <input type="text" name="doctor_contact" placeholder="Search email..." required>
                        <button type="submit"><i class="fa fa-search"></i></button>
                      </div>
                    </form>
                  </div>
                </div>

                <!-- Instant Table Search Filter -->
                <div class="table-filter-group">
                  <div class="table-filter-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="adminDocSearch" class="table-filter-input" placeholder="Quick filter doctors by name, specialty, or email..." onkeyup="filterTable('adminDocSearch', 'adminDocBody', 'adminDocCount')">
                    <span class="search-kbd-hint"><kbd>/</kbd></span>
                  </div>
                  <span class="table-filter-badge" id="adminDocCount">{{ $doctors->count() }} Doctors</span>
                </div>

                <div class="modern-table-card">
                  <div class="table-responsive">
                    <table class="modern-table" id="adminDocTable">
                      <thead>
                        <tr>
                          <th>Doctor Name</th>
                          <th>Specialization</th>
                          <th>Email Address</th>
                          <th>Consultancy Fee</th>
                        </tr>
                      </thead>
                      <tbody id="adminDocBody">
                        @forelse ($doctors as $doc)
                          <tr>
                            <td style="font-weight: 600; color: #0f172a;">Dr. {{ $doc->username }}</td>
                            <td><span class="badge-modern badge-modern-primary">{{ $doc->spec }}</span></td>
                            <td>
                              <span style="cursor: pointer;" onclick="copyToClipboard('{{ $doc->email }}', 'Copied Doctor Email!')" title="Click to copy email">
                                {{ $doc->email }} <i class="fa fa-clone ml-1 text-muted" style="font-size: 0.7rem;"></i>
                              </span>
                            </td>
                            <td style="font-weight: 600; color: #059669;">${{ $doc->docFees }}</td>
                          </tr>
                        @empty
                          <tr class="no-filter-row"><td colspan="4" class="text-center py-4 text-muted">No doctors found.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- ================= PATIENT REGISTRY ================= -->
            <div class="tab-pane fade" id="list-pat" role="tabpanel" aria-labelledby="list-pat-list">
              <div class="modern-card">
                <div class="modern-card-header d-flex flex-wrap justify-content-between align-items-center" style="gap: 0.75rem;">
                  <h4 class="modern-card-title mb-0"><i class="fa fa-users text-primary"></i> Registered Patients</h4>
                  <div class="d-flex align-items-center" style="gap: 0.5rem;">
                    <button type="button" class="btn-modern-outline" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;" onclick="exportTableToCSV('adminPatTable', 'DocOp_Patients_Registry.csv')">
                      <i class="fa fa-download mr-1"></i> Export CSV
                    </button>
                    <!-- Unified Search Form -->
                    <form action="{{ route('search') }}" method="POST" style="max-width: 240px; width: 100%;">
                      @csrf
                      <input type="hidden" name="search_type" value="patient">
                      <div class="modern-search-box">
                        <input type="text" name="patient_contact" placeholder="Search contact..." required>
                        <button type="submit"><i class="fa fa-search"></i></button>
                      </div>
                    </form>
                  </div>
                </div>

                <!-- Instant Table Search Filter -->
                <div class="table-filter-group">
                  <div class="table-filter-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="adminPatSearch" class="table-filter-input" placeholder="Quick filter patients by name, email, or contact..." onkeyup="filterTable('adminPatSearch', 'adminPatBody', 'adminPatCount')">
                    <span class="search-kbd-hint"><kbd>/</kbd></span>
                  </div>
                  <span class="table-filter-badge" id="adminPatCount">{{ $patients->count() }} Patients</span>
                </div>

                <div class="modern-table-card">
                  <div class="table-responsive">
                    <table class="modern-table" id="adminPatTable">
                      <thead>
                        <tr>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Gender</th>
                          <th>Email</th>
                          <th>Phone</th>
                        </tr>
                      </thead>
                      <tbody id="adminPatBody">
                        @forelse ($patients as $pat)
                          <tr>
                            <td>
                              <span class="badge-modern badge-modern-primary" style="cursor: pointer;" onclick="copyToClipboard('{{ $pat->pid }}', 'Copied Patient ID #{{ $pat->pid }}')" title="Click to copy Patient ID">
                                #{{ $pat->pid }} <i class="fa fa-clone ml-1" style="font-size: 0.7rem; opacity: 0.7;"></i>
                              </span>
                            </td>
                            <td style="font-weight: 600; color: #0f172a;">{{ $pat->fname }} {{ $pat->lname }}</td>
                            <td>{{ $pat->gender }}</td>
                            <td>{{ $pat->email }}</td>
                            <td>
                              <span style="cursor: pointer;" onclick="copyToClipboard('{{ $pat->contact }}', 'Copied Contact!')" title="Click to copy phone">
                                {{ $pat->contact }} <i class="fa fa-clone ml-1 text-muted" style="font-size: 0.7rem;"></i>
                              </span>
                            </td>
                          </tr>
                        @empty
                          <tr class="no-filter-row"><td colspan="5" class="text-center py-4 text-muted">No patients registered.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- ================= APPOINTMENT DETAILS ================= -->
            <div class="tab-pane fade" id="list-app" role="tabpanel" aria-labelledby="list-app-list">
              <div class="modern-card">
                <div class="modern-card-header d-flex flex-wrap justify-content-between align-items-center" style="gap: 0.75rem;">
                  <h4 class="modern-card-title mb-0"><i class="fa fa-calendar-check-o text-primary"></i> Appointment Records</h4>
                  <div class="d-flex align-items-center" style="gap: 0.5rem;">
                    <button type="button" class="btn-modern-outline" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;" onclick="exportTableToCSV('adminAppTable', 'DocOp_Appointments_Log.csv')">
                      <i class="fa fa-download mr-1"></i> Export CSV
                    </button>
                    <!-- Unified Search Form -->
                    <form action="{{ route('search') }}" method="POST" style="max-width: 240px; width: 100%;">
                      @csrf
                      <input type="hidden" name="search_type" value="appointment">
                      <div class="modern-search-box">
                        <input type="text" name="app_contact" placeholder="Search contact..." required>
                        <button type="submit"><i class="fa fa-search"></i></button>
                      </div>
                    </form>
                  </div>
                </div>

                <!-- Instant Table Search Filter -->
                <div class="table-filter-group">
                  <div class="table-filter-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="adminAppSearch" class="table-filter-input" placeholder="Quick filter appointments by patient, doctor, date, or status..." onkeyup="filterTable('adminAppSearch', 'adminAppBody', 'adminAppCount')">
                    <span class="search-kbd-hint"><kbd>/</kbd></span>
                  </div>
                  <span class="table-filter-badge" id="adminAppCount">{{ $appointments->count() }} Records</span>
                </div>

                <div class="modern-table-card">
                  <div class="table-responsive">
                    <table class="modern-table" id="adminAppTable">
                      <thead>
                        <tr>
                          <th>App ID</th>
                          <th>Patient</th>
                          <th>Doctor</th>
                          <th>Fee</th>
                          <th>Date & Time</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody id="adminAppBody">
                        @forelse ($appointments as $app)
                          <tr>
                            <td>
                              <span class="badge-modern badge-modern-primary" style="cursor: pointer;" onclick="copyToClipboard('{{ $app->ID }}', 'Copied App ID #{{ $app->ID }}')" title="Click to copy App ID">
                                #{{ $app->ID }} <i class="fa fa-clone ml-1" style="font-size: 0.7rem; opacity: 0.7;"></i>
                              </span>
                            </td>
                            <td style="font-weight: 600; color: #0f172a;">
                              {{ $app->fname }} {{ $app->lname }}<br>
                              <small class="text-muted">{{ $app->contact }}</small>
                            </td>
                            <td>Dr. {{ $app->doctor }}</td>
                            <td>${{ $app->docFees }}</td>
                            <td>
                              {{ $app->appdate }}<br>
                              <small class="text-muted">{{ $app->apptime }}</small>
                            </td>
                            <td>
                              @if ($app->userStatus == 1 && $app->doctorStatus == 1)
                                @if ($app->isCompleted())
                                  <span class="badge-modern badge-modern-primary"><i class="fa fa-check-circle"></i> Completed</span>
                                @else
                                  <span class="badge-modern badge-modern-success"><i class="fa fa-clock-o"></i> Active</span>
                                @endif
                              @elseif ($app->userStatus == 0 && $app->doctorStatus == 1)
                                <span class="badge-modern badge-modern-danger"><i class="fa fa-times"></i> Cancelled by Patient</span>
                              @elseif ($app->userStatus == 1 && $app->doctorStatus == 0)
                                <span class="badge-modern badge-modern-warning"><i class="fa fa-exclamation-triangle"></i> Cancelled by Doctor</span>
                              @else
                                <span class="badge-modern badge-modern-danger">Cancelled</span>
                              @endif
                            </td>
                          </tr>
                        @empty
                          <tr class="no-filter-row"><td colspan="6" class="text-center py-4 text-muted">No appointments booked yet.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- ================= PRESCRIPTION LIST ================= -->
            <div class="tab-pane fade" id="list-pres" role="tabpanel" aria-labelledby="list-pres-list">
              <div class="modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                  <h4 class="modern-card-title mb-0"><i class="fa fa-file-text-o text-primary"></i> Clinical Prescriptions</h4>
                  <button type="button" class="btn-modern-outline" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;" onclick="exportTableToCSV('adminPresTable', 'DocOp_Prescriptions_Log.csv')">
                    <i class="fa fa-download mr-1"></i> Export CSV
                  </button>
                </div>

                <!-- Instant Table Search Filter -->
                <div class="table-filter-group">
                  <div class="table-filter-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="adminPresSearch" class="table-filter-input" placeholder="Quick filter prescriptions by doctor, patient, diagnosis, or medicine..." onkeyup="filterTable('adminPresSearch', 'adminPresBody', 'adminPresCount')">
                    <span class="search-kbd-hint"><kbd>/</kbd></span>
                  </div>
                  <span class="table-filter-badge" id="adminPresCount">{{ $prescriptions->count() }} Prescriptions</span>
                </div>

                <div class="modern-table-card">
                  <div class="table-responsive">
                    <table class="modern-table" id="adminPresTable">
                      <thead>
                        <tr>
                          <th>Doctor</th>
                          <th>Patient Name</th>
                          <th>App ID</th>
                          <th>Date</th>
                          <th>Diagnosis</th>
                          <th>Medication</th>
                          <th>Allergies</th>
                          <th>Clinical Advice</th>
                        </tr>
                      </thead>
                      <tbody id="adminPresBody">
                        @forelse ($prescriptions as $pres)
                          <tr>
                            <td style="font-weight: 600;">Dr. {{ $pres->doctor }}</td>
                            <td style="font-weight: 600; color: #0f172a;">{{ $pres->fname }} {{ $pres->lname }}</td>
                            <td>
                              <span class="badge-modern badge-modern-primary" style="cursor: pointer;" onclick="copyToClipboard('{{ $pres->ID }}', 'Copied App ID #{{ $pres->ID }}')" title="Click to copy App ID">
                                #{{ $pres->ID }} <i class="fa fa-clone ml-1" style="font-size: 0.7rem; opacity: 0.7;"></i>
                              </span>
                            </td>
                            <td>{{ $pres->appdate }}</td>
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
                          </tr>
                        @empty
                          <tr class="no-filter-row"><td colspan="8" class="text-center py-4 text-muted">No prescriptions issued yet.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- ================= ADD DOCTOR ================= -->
            <div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-adoc-list">
              <div class="modern-card">
                <div class="modern-card-header">
                  <h4 class="modern-card-title"><i class="fa fa-user-plus text-primary"></i> Register New Medical Doctor</h4>
                </div>

                <form method="POST" action="{{ route('admin.doctor.add') }}">
                  @csrf
                  <div class="row">
                    <div class="col-md-6">
                      <div class="modern-form-group">
                        <label class="modern-form-label">Doctor Username *</label>
                        <div class="input-icon-wrapper">
                          <i class="fa fa-user-md form-icon"></i>
                          <input type="text" class="modern-input" name="doctor" placeholder="e.g. Dr. John" onkeydown="return alphaOnly(event);" required>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="modern-form-group">
                        <label class="modern-form-label">Specialization *</label>
                        <select name="special" class="modern-input" id="special" required="required" style="height: 48px;">
                          <option value="" disabled selected>Select Medical Field</option>
                          <option value="General">General Physician</option>
                          <option value="Cardiologist">Cardiologist</option>
                          <option value="Neurologist">Neurologist</option>
                          <option value="Pediatrician">Pediatrician</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="modern-form-group">
                        <label class="modern-form-label">Official Email Address *</label>
                        <div class="input-icon-wrapper">
                          <i class="fa fa-envelope-o form-icon"></i>
                          <input type="email" class="modern-input" name="demail" placeholder="doctor@hospital.org" required>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="modern-form-group">
                        <label class="modern-form-label">Consultancy Fees ($) *</label>
                        <div class="input-icon-wrapper">
                          <i class="fa fa-usd form-icon"></i>
                          <input type="number" class="modern-input" name="docFees" placeholder="e.g. 500" required>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="modern-form-group">
                        <label class="modern-form-label">Portal Password *</label>
                        <div class="input-icon-wrapper has-toggle">
                          <i class="fa fa-lock form-icon"></i>
                          <input type="password" class="modern-input" onkeyup="check();" name="dpassword" id="dpassword" placeholder="Create password" required>
                          <button type="button" class="btn-password-toggle" onclick="togglePasswordVisibility('dpassword', this)" title="Show/Hide Password">
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
                          <input type="password" class="modern-input" onkeyup="check();" name="cdpassword" id="cdpassword" placeholder="Repeat password" required>
                          <button type="button" class="btn-password-toggle" onclick="togglePasswordVisibility('cdpassword', this)" title="Show/Hide Password">
                            <i class="fa fa-eye"></i>
                          </button>
                        </div>
                        <span id="message" style="font-size: 0.8rem; font-weight: 600; margin-top: 4px; display: block;"></span>
                      </div>
                    </div>
                  </div>

                  <div class="mt-3">
                    <button type="submit" class="btn-modern-primary" style="max-width: 250px;">
                      <i class="fa fa-check"></i> Register Doctor
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- ================= DELETE DOCTOR ================= -->
            <div class="tab-pane fade" id="list-settings1" role="tabpanel" aria-labelledby="list-ddoc-list">
              <div class="modern-card">
                <div class="modern-card-header">
                  <h4 class="modern-card-title text-danger"><i class="fa fa-trash-o"></i> Remove Doctor from Staff</h4>
                </div>

                <form method="POST" action="{{ route('admin.doctor.delete') }}" style="max-width: 500px;">
                  @csrf
                  <div class="modern-form-group">
                    <label class="modern-form-label">Doctor's Registered Email</label>
                    <div class="input-icon-wrapper">
                      <i class="fa fa-envelope-o form-icon"></i>
                      <input type="email" class="modern-input" name="demail" placeholder="Enter doctor's email to remove" required>
                    </div>
                  </div>

                  <div class="mt-4">
                    <button type="submit" class="btn-modern-danger" onclick="return confirm('Are you sure you want to permanently remove this doctor?')">
                      <i class="fa fa-trash"></i> Delete Doctor Account
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- ================= MESSAGES / QUERIES ================= -->
            <div class="tab-pane fade" id="list-mes" role="tabpanel" aria-labelledby="list-mes-list">
              <div class="modern-card">
                <div class="modern-card-header">
                  <h4 class="modern-card-title"><i class="fa fa-envelope-o text-primary"></i> Patient Messages & Queries</h4>
                  <!-- Unified Search Form -->
                  <form action="{{ route('search') }}" method="POST" style="max-width: 320px; width: 100%;">
                    @csrf
                    <input type="hidden" name="search_type" value="message">
                    <div class="modern-search-box">
                      <input type="text" name="mes_contact" placeholder="Search phone contact..." required>
                      <button type="submit"><i class="fa fa-search"></i></button>
                    </div>
                  </form>
                </div>

                <!-- Instant Table Search Filter -->
                <div class="table-filter-group">
                  <div class="table-filter-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="adminMesSearch" class="table-filter-input" placeholder="Quick filter messages by sender, email, or content..." onkeyup="filterTable('adminMesSearch', 'adminMesBody', 'adminMesCount')">
                  </div>
                  <span class="table-filter-badge" id="adminMesCount">{{ $messages->count() }} Messages</span>
                </div>

                <div class="modern-table-card">
                  <div class="table-responsive">
                    <table class="modern-table">
                      <thead>
                        <tr>
                          <th>Sender Name</th>
                          <th>Email Address</th>
                          <th>Phone Contact</th>
                          <th>Message Content</th>
                        </tr>
                      </thead>
                      <tbody id="adminMesBody">
                        @forelse ($messages as $msg)
                          <tr>
                            <td style="font-weight: 600; color: #0f172a;">{{ $msg->name }}</td>
                            <td>{{ $msg->email }}</td>
                            <td>{{ $msg->contact }}</td>
                            <td style="max-width: 350px;">{{ $msg->message }}</td>
                          </tr>
                        @empty
                          <tr class="no-filter-row"><td colspan="4" class="text-center py-4 text-muted">No queries or messages received.</td></tr>
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
