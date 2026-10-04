@extends('layouts.app')

@section('title', 'Patient Portal - DocOp Healthcare')
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

  function openAppPass(appid, doctor, date, time, fees, patName, pid) {
    document.getElementById('passTokenNum').innerText = '#' + (appid || '--');
    document.getElementById('passDocName').innerText = 'Dr. ' + (doctor || 'Specialist');
    document.getElementById('passPatName').innerText = patName + ' (#' + pid + ')';
    document.getElementById('passDate').innerText = date || '-';
    document.getElementById('passTime').innerText = time || '-';
    document.getElementById('passFees').innerText = '$' + (fees || '0') + ' (Confirmed)';
    document.getElementById('passBarcodeText').innerText = 'DOC-OP-TK-' + appid + '-P' + pid;

    $('#modalAppPass').modal('show');
  }

  function openInvoiceModal(invNum, docName, date, time, docFee, medFee, patName, pid) {
    var feeVal = parseFloat(docFee) || 0;
    var medVal = parseFloat(medFee) || 0;
    var facilityFee = 15.00;
    var subtotal = feeVal + medVal + facilityFee;
    var tax = subtotal * 0.05;
    var grandTotal = subtotal + tax;

    document.getElementById('invModalNumber').innerText = invNum;
    document.getElementById('invModalDate').innerText = date;
    document.getElementById('invModalPatName').innerText = patName;
    document.getElementById('invModalPatId').innerText = '#' + pid;
    document.getElementById('invModalDocName').innerText = 'Dr. ' + docName;
    document.getElementById('invModalTime').innerText = time;
    
    document.getElementById('invModalDocFee').innerText = '$' + feeVal.toFixed(2);
    document.getElementById('invModalMedFee').innerText = '$' + medVal.toFixed(2);
    document.getElementById('invModalFacilityFee').innerText = '$' + facilityFee.toFixed(2);
    document.getElementById('invModalSubtotal').innerText = '$' + subtotal.toFixed(2);
    document.getElementById('invModalTax').innerText = '$' + tax.toFixed(2);
    document.getElementById('invModalGrandTotal').innerText = '$' + grandTotal.toFixed(2);

    $('#modalHospitalInvoice').modal('show');
  }

  function checkBookedSlots() {
    var docSelect = document.getElementById('doctor');
    var dateInput = document.getElementById('appdateInput');
    var timeSelect = document.getElementById('apptime');
    if (!docSelect || !dateInput || !timeSelect) return;

    var doc = docSelect.value;
    var date = dateInput.value;
    if (!doc || !date) return;

    fetch('{{ route("patient.booked.slots") }}?doctor=' + encodeURIComponent(doc) + '&appdate=' + encodeURIComponent(date))
      .then(function(res) { return res.json(); })
      .then(function(data) {
        var booked = data.booked_slots || [];
        for (var i = 0; i < timeSelect.options.length; i++) {
          var opt = timeSelect.options[i];
          if (!opt.value) continue;
          var isBooked = booked.some(function(b) {
            return b.substring(0, 5) === opt.value.substring(0, 5);
          });
          if (isBooked) {
            opt.disabled = true;
            if (!opt.text.includes('(Booked)')) {
              opt.text = opt.text.replace(' (Booked)', '') + ' (Booked)';
            }
          } else {
            opt.disabled = false;
            opt.text = opt.text.replace(' (Booked)', '');
          }
        }
        if (timeSelect.selectedOptions[0] && timeSelect.selectedOptions[0].disabled) {
          timeSelect.value = '';
          updateBookingSummary();
        }
      })
      .catch(function(err) {
        console.error('Error fetching booked slots:', err);
      });
  }

  function updateBookingSummary() {
    var docSelect = document.getElementById('doctor');
    var dateInput = document.getElementById('appdateInput');
    var timeSelect = document.getElementById('apptime');

    if (docSelect && docSelect.selectedIndex > 0) {
      var opt = docSelect.options[docSelect.selectedIndex];
      var docName = opt.value;
      var spec = opt.getAttribute('data-spec') || 'Specialist';
      var fee = opt.getAttribute('data-value') || '0';
      var docEl = document.getElementById('summaryDocName');
      var deptEl = document.getElementById('summaryDocDept');
      var feeEl = document.getElementById('summaryFeeVal');
      var totEl = document.getElementById('summaryTotalVal');
      if (docEl) docEl.innerText = 'Dr. ' + docName;
      if (deptEl) deptEl.innerText = spec;
      if (feeEl) feeEl.innerText = '$' + fee;
      if (totEl) totEl.innerText = '$' + fee;
    } else {
      var docEl = document.getElementById('summaryDocName');
      var deptEl = document.getElementById('summaryDocDept');
      var feeEl = document.getElementById('summaryFeeVal');
      var totEl = document.getElementById('summaryTotalVal');
      if (docEl) docEl.innerText = 'Select a Specialist';
      if (deptEl) deptEl.innerText = 'Specialization';
      if (feeEl) feeEl.innerText = '$0.00';
      if (totEl) totEl.innerText = '$0.00';
    }

    if (dateInput && dateInput.value) {
      var dateEl = document.getElementById('summaryDateVal');
      if (dateEl) {
        var altInput = document.querySelector('input.modern-datepicker.flatpickr-input:not([type="hidden"])');
        dateEl.innerText = (altInput && altInput.value) ? altInput.value : dateInput.value;
      }
    }

    if (timeSelect && timeSelect.selectedIndex > 0) {
      var optTime = timeSelect.options[timeSelect.selectedIndex];
      var timeEl = document.getElementById('summaryTimeVal');
      if (timeEl) timeEl.innerText = optTime.text;
    }
  }

  $(document).ready(function() {
    if (window.location.hash) {
      $('a[href="' + window.location.hash + '"]').tab('show');
    }

    // Initialize Modern Datepicker (Flatpickr)
    if (window.flatpickr) {
      flatpickr('#appdateInput', {
        minDate: 'today',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'F j, Y',
        altInputClass: 'modern-input modern-datepicker',
        disableMobile: false,
        onChange: function(selectedDates, dateStr, instance) {
          updateBookingSummary();
          checkBookedSlots();
        }
      });
    }

    var specSelect = document.getElementById('spec');
    if (specSelect) {
      specSelect.onchange = function() {
        let spec = this.value;
        let doctorElem = document.getElementById('doctor');
        if (!doctorElem) return;
        let docs = [...doctorElem.options];
        docs.forEach((el, ind, arr) => {
          if (ind === 0) return; // Keep placeholder
          arr[ind].style.display = "";
          if (el.getAttribute("data-spec") && el.getAttribute("data-spec") !== spec) {
            arr[ind].style.display = "none";
          }
        });
        doctorElem.value = "";
        var feesElem = document.getElementById('docFees');
        if (feesElem) feesElem.value = "";
        updateBookingSummary();
        checkBookedSlots();
      };
    }

    var doctorSelect = document.getElementById('doctor');
    if (doctorSelect) {
      doctorSelect.onchange = function() {
        var opt = this.options[this.selectedIndex];
        if (opt) {
          var val = opt.getAttribute('data-value');
          var feesElem = document.getElementById('docFees');
          if (feesElem) feesElem.value = val || '';
        }
        updateBookingSummary();
        checkBookedSlots();
      };
    }

    var dateInput = document.getElementById('appdateInput');
    if (dateInput) {
      dateInput.onchange = function() {
        updateBookingSummary();
        checkBookedSlots();
      };
    }

    var timeSelect = document.getElementById('apptime');
    if (timeSelect) {
      timeSelect.onchange = updateBookingSummary;
    }
  });
</script>
@endpush

@section('navbar')
  <!-- Modern Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top modern-navbar">
    <div class="container-fluid px-lg-4">
      <a class="navbar-brand" href="{{ route('patient.dashboard') }}">
        <span class="brand-icon-box"><i class="fa fa-heartbeat"></i></span>
        <span>DocOp <span style="font-weight: 400; opacity: 0.85; font-size: 0.85em;">Patient Portal</span></span>
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
              <div class="user-avatar"><i class="fa fa-user"></i></div>
              <span>{{ session('username') }}</span>
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
  <!-- Dashboard Container -->
  <div class="dashboard-wrapper">
    <div class="container-fluid px-lg-4">
      
      <!-- Welcome Header Banner -->
      <div class="dashboard-header-banner">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h1 class="dashboard-title">Welcome back, {{ session('fname') }}! 👋</h1>
            <p class="dashboard-subtitle">Manage your consultations, book specialist visits, and review prescriptions.</p>
          </div>
          <div class="col-md-4 text-md-right mt-3 mt-md-0">
            <span class="badge-modern badge-modern-primary" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); font-size: 0.85rem; padding: 0.5rem 1rem;">
              <i class="fa fa-id-badge"></i> Patient ID: #{{ session('pid') }}
            </span>
          </div>
        </div>
      </div>

      <!-- Main Layout: Sidebar & Content Panes -->
      <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
          <div class="modern-sidebar">
            <div class="sidebar-heading">Navigation Menu</div>
            <div class="list-group" id="list-tab" role="tablist">
              <a class="list-group-item list-group-item-action active" id="list-dash-list" data-toggle="list" href="#list-dash" role="tab">
                <i class="fa fa-th-large"></i> Dashboard
              </a>
              <a class="list-group-item list-group-item-action" id="list-home-list" data-toggle="list" href="#list-home" role="tab">
                <i class="fa fa-calendar-plus-o"></i> Book Appointment
              </a>
              <a class="list-group-item list-group-item-action" id="list-pat-list" data-toggle="list" href="#app-hist" role="tab">
                <i class="fa fa-history"></i> Appointment History
              </a>
              <a class="list-group-item list-group-item-action" id="list-pres-list" data-toggle="list" href="#list-pres" role="tab">
                <i class="fa fa-file-text-o"></i> Prescriptions
              </a>
              <a class="list-group-item list-group-item-action" id="list-ehr-list" data-toggle="list" href="#list-ehr" role="tab">
                <i class="fa fa-heartbeat text-danger"></i> Health Profile & EHR
              </a>
              <a class="list-group-item list-group-item-action" id="list-docs-list" data-toggle="list" href="#list-docs" role="tab">
                <i class="fa fa-folder-open-o text-primary"></i> Diagnostic Reports
              </a>
              <a class="list-group-item list-group-item-action" id="list-inv-list" data-toggle="list" href="#list-inv" role="tab">
                <i class="fa fa-credit-card text-success"></i> Invoices & Receipts
              </a>
            </div>
          </div>
        </div>

        <!-- Content Area -->
        <div class="col-lg-9">
          <div class="tab-content" id="nav-tabContent">

            <!-- ================= DASHBOARD OVERVIEW ================= -->
            <div class="tab-pane fade show active" id="list-dash" role="tabpanel">
              @php
                $nextApp = $appointments->where('userStatus', 1)->where('doctorStatus', 1)->sortBy('appdate')->first();
              @endphp

              @if($nextApp)
                <div class="alert alert-info d-flex flex-wrap align-items-center justify-content-between p-3 mb-4" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border: 1.5px solid #bfdbfe; border-radius: 16px; color: #1e3a8a; box-shadow: 0 4px 15px rgba(37,99,235,0.08);">
                  <div class="d-flex align-items-center mb-2 mb-md-0">
                    <div style="width: 46px; height: 46px; border-radius: 12px; background: #2563eb; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-right: 1.15rem; flex-shrink: 0; box-shadow: 0 4px 10px rgba(37,99,235,0.3);">
                      <i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div>
                      <strong style="font-size: 1rem; color: #0f172a; display: block;">Upcoming Consultation: Dr. {{ $nextApp->doctor }}</strong>
                      <span style="font-size: 0.85rem; color: #2563eb; font-weight: 500;">
                        <i class="fa fa-clock-o mr-1"></i> {{ $nextApp->appdate }} at {{ $nextApp->apptime }} &bull; Token #{{ $nextApp->ID }} &bull; Fee ${{ $nextApp->docFees }}
                      </span>
                    </div>
                  </div>
                  <div>
                    <button type="button" class="btn-modern-primary" style="padding: 0.45rem 1.1rem; height: auto; font-size: 0.825rem;" onclick="openAppPass('{{ $nextApp->ID }}', '{{ $nextApp->doctor }}', '{{ $nextApp->appdate }}', '{{ $nextApp->apptime }}', '{{ $nextApp->docFees }}', '{{ session('fname') }} {{ session('lname') }}', '{{ session('pid') }}')">
                      <i class="fa fa-ticket mr-1"></i> View Hospital Pass
                    </button>
                  </div>
                </div>
              @endif

              <div class="row">
                <div class="col-md-4 mb-4">
                  <div class="stat-card">
                    <div class="stat-card-icon-box stat-icon-blue">
                      <i class="fa fa-calendar-plus-o"></i>
                    </div>
                    <div class="stat-card-title">New Consultation</div>
                    <div class="stat-card-value">Book Visit</div>
                    <p style="font-size: 0.825rem; color: #64748b; margin-bottom: 1rem;">Schedule time with available specialist doctors.</p>
                    <a class="stat-card-link" onclick="clickDiv('#list-home-list')">
                      Schedule now <i class="fa fa-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <div class="col-md-4 mb-4">
                  <div class="stat-card">
                    <div class="stat-card-icon-box stat-icon-emerald">
                      <i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div class="stat-card-title">Appointments</div>
                    <div class="stat-card-value">{{ $appointments->count() }} Total</div>
                    <p style="font-size: 0.825rem; color: #64748b; margin-bottom: 1rem;">View status of upcoming or past doctor consultations.</p>
                    <a class="stat-card-link" onclick="clickDiv('#list-pat-list')">
                      View history <i class="fa fa-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <div class="col-md-4 mb-4">
                  <div class="stat-card">
                    <div class="stat-card-icon-box stat-icon-purple">
                      <i class="fa fa-medkit"></i>
                    </div>
                    <div class="stat-card-title">Medical Records</div>
                    <div class="stat-card-value">{{ $prescriptions->count() }} Prescriptions</div>
                    <p style="font-size: 0.825rem; color: #64748b; margin-bottom: 1rem;">Access digital prescriptions and dosage plans.</p>
                    <a class="stat-card-link" onclick="clickDiv('#list-pres-list')">
                      Open records <i class="fa fa-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <div class="col-md-4 mb-4">
                  <div class="stat-card">
                    <div class="stat-card-icon-box stat-icon-amber">
                      <i class="fa fa-heartbeat"></i>
                    </div>
                    <div class="stat-card-title">Health Profile (EHR)</div>
                    <div class="stat-card-value">{{ $patient->blood_group ?? 'Blood: --' }}</div>
                    <p style="font-size: 0.825rem; color: #64748b; margin-bottom: 1rem;">Allergies: {{ $patient->allergies ? Str::limit($patient->allergies, 20) : 'None reported' }}.</p>
                    <a class="stat-card-link" onclick="clickDiv('#list-ehr-list')">
                      Update profile <i class="fa fa-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <div class="col-md-4 mb-4">
                  <div class="stat-card">
                    <div class="stat-card-icon-box stat-icon-emerald">
                      <i class="fa fa-file-pdf-o"></i>
                    </div>
                    <div class="stat-card-title">Diagnostic Reports</div>
                    <div class="stat-card-value">{{ $documents->count() }} Reports</div>
                    <p style="font-size: 0.825rem; color: #64748b; margin-bottom: 1rem;">Lab tests, pathology, radiology and imaging files.</p>
                    <a class="stat-card-link" onclick="clickDiv('#list-docs-list')">
                      View documents <i class="fa fa-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <div class="col-md-4 mb-4">
                  <div class="stat-card">
                    <div class="stat-card-icon-box stat-icon-blue">
                      <i class="fa fa-credit-card"></i>
                    </div>
                    <div class="stat-card-title">Invoices & Billing</div>
                    <div class="stat-card-value">${{ number_format($appointments->where('userStatus', 1)->sum('docFees'), 2) }}</div>
                    <p style="font-size: 0.825rem; color: #64748b; margin-bottom: 1rem;">Consolidated hospital invoices and tax receipts.</p>
                    <a class="stat-card-link" onclick="clickDiv('#list-inv-list')">
                      View receipts <i class="fa fa-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>

              <!-- Quick Health & Emergency Services Banner -->
              <div class="p-3 mt-2" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; box-shadow: 0 2px 8px rgba(15,23,42,0.04);">
                <div class="row align-items-center">
                  <div class="col-md-8">
                    <div class="d-flex align-items-center">
                      <span style="font-size: 1.5rem; margin-right: 0.75rem;">🚑</span>
                      <div>
                        <strong style="color: #0f172a; font-size: 0.95rem;">DocOp 24x7 Emergency Services & Ambulance</strong>
                        <div style="font-size: 0.825rem; color: #64748b;">Emergency hotline: <span class="text-danger font-weight-bold">+1 (800) 911-DOCOP</span> &bull; General hospital inquiry: <span class="text-primary font-weight-bold">+1 (800) 432-DOCTOR</span></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 text-md-right mt-2 mt-md-0">
                    <a href="{{ route('contact') }}" class="btn-modern-outline" style="padding: 0.4rem 0.9rem; font-size: 0.8rem; text-decoration: none;">
                      <i class="fa fa-envelope-o mr-1"></i> Contact Desk
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <!-- ================= BOOK APPOINTMENT ================= -->
            <div class="tab-pane fade" id="list-home" role="tabpanel">
              <div class="row">
                <!-- Left: Booking Form -->
                <div class="col-xl-8 mb-4">
                  <div class="modern-card">
                    <div class="modern-card-header">
                      <h4 class="modern-card-title"><i class="fa fa-calendar-plus-o text-primary"></i> Book Doctor Consultation</h4>
                      <span class="badge-modern badge-modern-primary">Instant Booking</span>
                    </div>

                    <form method="POST" action="{{ route('patient.appointment.book') }}" id="bookAppForm">
                      @csrf
                      <div class="row">
                        <div class="col-md-6">
                          <div class="modern-form-group">
                            <label class="modern-form-label">Medical Specialization</label>
                            <select name="spec" class="modern-input" id="spec" style="height: 48px;">
                              <option value="" disabled selected>Choose Specialization</option>
                              @foreach($specializations as $spec)
                                <option value="{{ $spec }}">{{ $spec }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="modern-form-group">
                            <label class="modern-form-label">Available Specialist *</label>
                            <select name="doctor" class="modern-input" id="doctor" required="required" style="height: 48px;">
                              <option value="" disabled selected>Select Doctor</option>
                              @foreach($doctors as $doc)
                                <option value="{{ $doc->username }}" data-value="{{ $doc->docFees }}" data-spec="{{ $doc->spec }}">
                                  Dr. {{ $doc->username }} ({{ $doc->spec }})
                                </option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-4">
                          <div class="modern-form-group">
                            <label class="modern-form-label">Consultancy Fee ($)</label>
                            <div class="input-icon-wrapper">
                              <i class="fa fa-usd form-icon"></i>
                              <input class="modern-input" type="text" name="docFees" id="docFees" readonly="readonly" placeholder="Doctor's Fee" style="background:#f8fafc;" required />
                            </div>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="modern-form-group">
                            <label class="modern-form-label">Appointment Date *</label>
                            <div class="input-icon-wrapper">
                              <i class="fa fa-calendar form-icon"></i>
                              <input type="date" class="modern-input modern-datepicker" name="appdate" id="appdateInput" min="{{ date('Y-m-d') }}" placeholder="Choose consultation date..." required />
                            </div>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="modern-form-group">
                            <label class="modern-form-label">Preferred Time Slot *</label>
                            <select name="apptime" class="modern-input" id="apptime" required="required" style="height: 48px;">
                              <option value="" disabled selected>Select Slot</option>
                              <option value="08:00:00">08:00 AM (Morning)</option>
                              <option value="10:00:00">10:00 AM (Morning)</option>
                              <option value="12:00:00">12:00 PM (Noon)</option>
                              <option value="14:00:00">02:00 PM (Afternoon)</option>
                              <option value="16:00:00">04:00 PM (Evening)</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="mt-4">
                        <button type="submit" class="btn-modern-primary" style="max-width: 280px;">
                          <i class="fa fa-check-circle"></i> Confirm Appointment
                        </button>
                      </div>
                    </form>
                  </div>
                </div>

                <!-- Right: Live Consultation Summary Card Preview -->
                <div class="col-xl-4 mb-4">
                  <div class="booking-summary-card">
                    <div class="booking-summary-header">
                      <div class="booking-doc-avatar">
                        <i class="fa fa-user-md" id="summaryAvatarIcon"></i>
                      </div>
                      <div>
                        <strong style="color: #0f172a; font-size: 1rem; display: block;" id="summaryDocName">Select a Specialist</strong>
                        <span class="badge-modern badge-modern-primary" id="summaryDocDept">Specialization</span>
                      </div>
                    </div>

                    <div class="summary-detail-row">
                      <span class="summary-detail-label"><i class="fa fa-calendar-o mr-1"></i> Date</span>
                      <span class="summary-detail-value" id="summaryDateVal">--</span>
                    </div>

                    <div class="summary-detail-row">
                      <span class="summary-detail-label"><i class="fa fa-clock-o mr-1"></i> Time Slot</span>
                      <span class="summary-detail-value" id="summaryTimeVal">--</span>
                    </div>

                    <div class="summary-detail-row">
                      <span class="summary-detail-label"><i class="fa fa-hospital-o mr-1"></i> Clinic Block</span>
                      <span class="summary-detail-value">DocOp OPD Wing</span>
                    </div>

                    <div class="summary-detail-row">
                      <span class="summary-detail-label"><i class="fa fa-tag mr-1"></i> Doctor Fee</span>
                      <span class="summary-detail-value" id="summaryFeeVal">$0.00</span>
                    </div>

                    <div class="summary-detail-row">
                      <span class="summary-detail-label"><i class="fa fa-shield mr-1"></i> Booking Fee</span>
                      <span class="summary-detail-value" style="color: #10b981;">FREE ($0.00)</span>
                    </div>

                    <div class="summary-total-banner">
                      <span style="font-weight: 700; color: #1e3a8a; font-size: 0.9rem;">Estimated Total</span>
                      <span class="summary-total-price" id="summaryTotalVal">$0.00</span>
                    </div>

                    <div class="mt-3 p-2 text-center" style="background: rgba(37,99,235,0.06); border-radius: 10px; font-size: 0.775rem; color: #475569;">
                      <i class="fa fa-info-circle mr-1 text-primary"></i> Digital Appointment pass generated immediately upon confirmation.
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- ================= APPOINTMENT HISTORY ================= -->
            <div class="tab-pane fade" id="app-hist" role="tabpanel">
              <div class="modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                  <h4 class="modern-card-title mb-0"><i class="fa fa-calendar-check-o text-primary"></i> My Appointment History</h4>
                  <button type="button" class="btn-modern-outline" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;" onclick="exportTableToCSV('patientAppTable', 'DocOp_My_Appointments.csv')">
                    <i class="fa fa-download mr-1"></i> Export CSV
                  </button>
                </div>

                <!-- Instant Table Search Filter -->
                <div class="table-filter-group">
                  <div class="table-filter-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="appTableInput" class="table-filter-input" placeholder="Quick search appointments by doctor, date, fee, or status..." onkeyup="filterTable('appTableInput', 'appTableBody', 'appTableCount')">
                    <span class="search-kbd-hint"><kbd>/</kbd></span>
                  </div>
                  <span class="table-filter-badge" id="appTableCount">{{ $appointments->count() }} Appointments</span>
                </div>

                <div class="modern-table-card">
                  <div class="table-responsive">
                    <table class="modern-table" id="patientAppTable">
                      <thead>
                        <tr>
                          <th>Doctor Name</th>
                          <th>Token #</th>
                          <th>Consultancy Fee</th>
                          <th>Date</th>
                          <th>Time</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="appTableBody">
                        @forelse ($appointments as $app)
                          <tr>
                            <td style="font-weight: 600; color: #0f172a;">Dr. {{ $app->doctor }}</td>
                            <td>
                              <span class="badge-modern badge-modern-primary" style="cursor: pointer;" onclick="copyToClipboard('{{ $app->ID }}', 'Copied Token #{{ $app->ID }}')" title="Click to copy Token #">
                                #{{ $app->ID }} <i class="fa fa-clone ml-1" style="font-size: 0.7rem; opacity: 0.7;"></i>
                              </span>
                            </td>
                            <td>${{ $app->docFees }}</td>
                            <td>{{ $app->appdate }}</td>
                            <td>{{ $app->apptime }}</td>
                            <td>
                              @if ($app->userStatus == 1 && $app->doctorStatus == 1)
                                @if ($app->isCompleted())
                                  <span class="badge-modern badge-modern-primary"><i class="fa fa-check-circle"></i> Completed & Attended</span>
                                @else
                                  <span class="badge-modern badge-modern-success"><i class="fa fa-clock-o"></i> Confirmed & Scheduled</span>
                                @endif
                              @elseif ($app->userStatus == 0 && $app->doctorStatus == 1)
                                <span class="badge-modern badge-modern-danger"><i class="fa fa-times"></i> Cancelled by You</span>
                              @elseif ($app->userStatus == 1 && $app->doctorStatus == 0)
                                <span class="badge-modern badge-modern-warning"><i class="fa fa-exclamation-triangle"></i> Cancelled by Doctor</span>
                              @else
                                <span class="badge-modern badge-modern-danger">Cancelled</span>
                              @endif
                            </td>
                            <td>
                              <div class="d-flex align-items-center" style="gap: 0.4rem;">
                                <button type="button" class="btn-modern-outline" style="padding: 0.35rem 0.75rem; font-size: 0.775rem;" onclick="openAppPass('{{ $app->ID }}', '{{ $app->doctor }}', '{{ $app->appdate }}', '{{ $app->apptime }}', '{{ $app->docFees }}', '{{ session('fname') }} {{ session('lname') }}', '{{ session('pid') }}')">
                                  <i class="fa fa-ticket"></i> Pass
                                </button>

                                @if ($app->userStatus == 1 && $app->doctorStatus == 1 && !$app->isCompleted())
                                  <form method="POST" action="{{ route('patient.appointment.cancel', $app->ID) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                    @csrf
                                    <button type="submit" class="btn-modern-danger border-0" style="padding: 0.35rem 0.75rem; font-size: 0.775rem;">
                                      <i class="fa fa-times"></i> Cancel
                                    </button>
                                  </form>
                                @endif
                              </div>
                            </td>
                          </tr>
                        @empty
                          <tr class="no-filter-row"><td colspan="7" class="text-center py-4 text-muted">No appointments found in your record.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- ================= PRESCRIPTIONS ================= -->
            <div class="tab-pane fade" id="list-pres" role="tabpanel">
              <div class="modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                  <h4 class="modern-card-title mb-0"><i class="fa fa-file-text-o text-primary"></i> Prescription Statements</h4>
                  <button type="button" class="btn-modern-outline" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;" onclick="exportTableToCSV('patientPresTable', 'DocOp_My_Prescriptions.csv')">
                    <i class="fa fa-download mr-1"></i> Export CSV
                  </button>
                </div>

                <!-- Instant Table Search Filter -->
                <div class="table-filter-group">
                  <div class="table-filter-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="presTableInput" class="table-filter-input" placeholder="Search prescriptions by doctor, medicine, or condition..." onkeyup="filterTable('presTableInput', 'presTableBody', 'presTableCount')">
                    <span class="search-kbd-hint"><kbd>/</kbd></span>
                  </div>
                  <span class="table-filter-badge" id="presTableCount">{{ $prescriptions->count() }} Prescriptions</span>
                </div>

                <div class="modern-table-card">
                  <div class="table-responsive">
                    <table class="modern-table" id="patientPresTable">
                      <thead>
                        <tr>
                          <th>Doctor</th>
                          <th>App ID</th>
                          <th>Date & Time</th>
                          <th>Condition</th>
                          <th>Medication</th>
                          <th>Allergies</th>
                          <th>Clinical Notes</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="presTableBody">
                        @forelse ($prescriptions as $pres)
                          <tr>
                            <td style="font-weight: 600;">Dr. {{ $pres->doctor }}</td>
                            <td>#{{ $pres->ID }}</td>
                            <td>{{ $pres->appdate }}<br><small class="text-muted">{{ $pres->apptime }}</small></td>
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
                            <td style="max-width: 200px; font-size: 0.85rem;">{{ $pres->prescription }}</td>
                            <td>
                              <button type="button" class="btn-modern-success border-0" style="padding: 0.35rem 0.75rem; font-size: 0.775rem;" onclick="openRxModal('{{ addslashes($pres->doctor) }}', '{{ addslashes($pres->pid) }}', '{{ addslashes($pres->ID) }}', '{{ addslashes($pres->fname . ' ' . $pres->lname) }}', '{{ addslashes($pres->appdate) }}', '{{ addslashes($pres->apptime) }}', '{{ addslashes($pres->disease) }}', '{{ addslashes($pres->allergy) }}', '{{ addslashes($pres->medicine ?? '') }}', '{{ addslashes($pres->prescription) }}')">
                                <i class="fa fa-file-text-o"></i> View Rx
                              </button>
                            </td>
                          </tr>
                        @empty
                          <tr class="no-filter-row"><td colspan="8" class="text-center py-4 text-muted">No prescriptions recorded yet.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- ================= HEALTH PROFILE & EHR ================= -->
            <div class="tab-pane fade" id="list-ehr" role="tabpanel">
              <div class="modern-card mb-4">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                  <h4 class="modern-card-title mb-0"><i class="fa fa-heartbeat text-danger"></i> Patient Electronic Health Record (EHR)</h4>
                  <span class="badge-modern badge-modern-primary">Live Clinical Record</span>
                </div>

                <!-- Clinical Snapshot Cards -->
                <div class="row mb-4">
                  <div class="col-md-4 mb-3">
                    <div style="background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 12px; padding: 1.25rem;">
                      <small class="text-uppercase text-danger font-weight-bold" style="font-size: 0.725rem; letter-spacing: 0.05em;">Blood Group</small>
                      <div style="font-size: 1.75rem; font-weight: 800; color: #b91c1c; margin-top: 0.25rem;">
                        <i class="fa fa-tint mr-1"></i> {{ $patient->blood_group ?? 'Not Set' }}
                      </div>
                      <small class="text-muted">Critical for emergency & blood bank match</small>
                    </div>
                  </div>

                  <div class="col-md-4 mb-3">
                    <div style="background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 12px; padding: 1.25rem;">
                      <small class="text-uppercase text-warning font-weight-bold" style="font-size: 0.725rem; letter-spacing: 0.05em; color: #b45309 !important;">Known Allergies</small>
                      <div style="font-size: 1rem; font-weight: 700; color: #92400e; margin-top: 0.35rem; min-height: 2.2rem;">
                        <i class="fa fa-exclamation-triangle mr-1"></i> {{ $patient->allergies ? $patient->allergies : 'No allergies reported' }}
                      </div>
                      <small class="text-muted">Critical alert for prescribing doctors</small>
                    </div>
                  </div>

                  <div class="col-md-4 mb-3">
                    <div style="background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 12px; padding: 1.25rem;">
                      <small class="text-uppercase text-success font-weight-bold" style="font-size: 0.725rem; letter-spacing: 0.05em; color: #15803d !important;">Chronic Conditions</small>
                      <div style="font-size: 1rem; font-weight: 700; color: #166534; margin-top: 0.35rem; min-height: 2.2rem;">
                        <i class="fa fa-stethoscope mr-1"></i> {{ $patient->chronic_conditions ? $patient->chronic_conditions : 'None recorded' }}
                      </div>
                      <small class="text-muted">Long-term diagnosis history</small>
                    </div>
                  </div>
                </div>

                <!-- Update EHR Form -->
                <form method="POST" action="{{ route('patient.ehr.update') }}">
                  @csrf
                  <h5 style="font-weight: 700; font-size: 1rem; color: #0f172a; margin-bottom: 1rem;">Update Your Clinical Profile</h5>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="modern-form-group">
                        <label class="modern-form-label">Blood Group *</label>
                        <select name="blood_group" class="modern-input" required style="height: 48px;">
                          <option value="" disabled {{ empty($patient->blood_group) ? 'selected' : '' }}>Select Blood Group</option>
                          @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                            <option value="{{ $bg }}" {{ ($patient->blood_group ?? '') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class="modern-form-group">
                        <label class="modern-form-label">Known Allergies / Sensitivities</label>
                        <input type="text" name="allergies" class="modern-input" value="{{ $patient->allergies ?? '' }}" placeholder="e.g. Penicillin, Peanuts, Sulfa drugs, Latex (or None)" />
                      </div>
                    </div>
                  </div>

                  <div class="modern-form-group">
                    <label class="modern-form-label">Chronic Health Conditions & Medical History</label>
                    <textarea name="chronic_conditions" rows="3" class="modern-input" style="height: auto; padding: 0.75rem 1rem;" placeholder="e.g. Hypertension (high BP), Type-2 Diabetes Mellitus, Asthma, Thyroid disorders...">{{ $patient->chronic_conditions ?? '' }}</textarea>
                  </div>

                  <div class="mt-3">
                    <button type="submit" class="btn-modern-primary" style="max-width: 260px;">
                      <i class="fa fa-save"></i> Save Health Profile
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- ================= DIAGNOSTIC REPORTS ================= -->
            <div class="tab-pane fade" id="list-docs" role="tabpanel">
              <div class="modern-card mb-4">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                  <h4 class="modern-card-title mb-0"><i class="fa fa-folder-open-o text-primary"></i> Diagnostic Tests & Lab Reports</h4>
                  <div class="d-flex align-items-center" style="gap: 0.5rem;">
                    <button type="button" class="btn-modern-outline" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;" onclick="exportTableToCSV('patientDocsTable', 'DocOp_Lab_Reports.csv')">
                      <i class="fa fa-download mr-1"></i> Export CSV
                    </button>
                    <button type="button" class="btn-modern-primary" style="padding: 0.35rem 0.8rem; font-size: 0.8rem; height: auto;" data-toggle="collapse" data-target="#collapseAddDoc">
                      <i class="fa fa-plus-circle mr-1"></i> Add Lab Report
                    </button>
                  </div>
                </div>

                <!-- Collapse Add Document Form -->
                <div class="collapse mb-4" id="collapseAddDoc">
                  <div style="background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 12px; padding: 1.25rem;">
                    <h5 style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem;">
                      <i class="fa fa-upload text-primary mr-1"></i> Record Diagnostic Report / Investigation
                    </h5>
                    <form method="POST" action="{{ route('patient.document.add') }}">
                      @csrf
                      <div class="row">
                        <div class="col-md-6">
                          <div class="modern-form-group">
                            <label class="modern-form-label">Report Title / Test Name *</label>
                            <input type="text" name="title" class="modern-input" placeholder="e.g. Complete Blood Count (CBC) or Chest X-Ray" required />
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="modern-form-group">
                            <label class="modern-form-label">Report Category *</label>
                            <select name="report_type" class="modern-input" required style="height: 48px;">
                              <option value="Pathology">Pathology / Blood</option>
                              <option value="Radiology">Radiology / X-Ray / CT</option>
                              <option value="Cardiology">Cardiology / ECG</option>
                              <option value="Biochemistry">Biochemistry</option>
                              <option value="General">General Medical Report</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="modern-form-group">
                            <label class="modern-form-label">Test Date *</label>
                            <input type="date" name="report_date" class="modern-input" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required />
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="modern-form-group">
                            <label class="modern-form-label">Diagnostic Laboratory / Hospital</label>
                            <input type="text" name="laboratory" class="modern-input" placeholder="e.g. DocOp Central Diagnostics & Imaging" />
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="modern-form-group">
                            <label class="modern-form-label">Key Findings / Doctor's Observation</label>
                            <input type="text" name="notes" class="modern-input" placeholder="e.g. Normal values, Hb 14.2 g/dL, clear lung fields" />
                          </div>
                        </div>
                      </div>

                      <div class="mt-2 text-right">
                        <button type="button" class="btn-modern-outline mr-2" data-toggle="collapse" data-target="#collapseAddDoc">Cancel</button>
                        <button type="submit" class="btn-modern-primary" style="display: inline-block; width: auto; padding: 0.5rem 1.5rem;">
                          <i class="fa fa-check"></i> Save Diagnostic Record
                        </button>
                      </div>
                    </form>
                  </div>
                </div>

                <!-- Instant Table Search Filter -->
                <div class="table-filter-group">
                  <div class="table-filter-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="docTableInput" class="table-filter-input" placeholder="Search diagnostic reports by test title, type, lab, or notes..." onkeyup="filterTable('docTableInput', 'docTableBody', 'docTableCount')">
                    <span class="search-kbd-hint"><kbd>/</kbd></span>
                  </div>
                  <span class="table-filter-badge" id="docTableCount">{{ $documents->count() }} Reports</span>
                </div>

                <div class="modern-table-card">
                  <div class="table-responsive">
                    <table class="modern-table" id="patientDocsTable">
                      <thead>
                        <tr>
                          <th>Record ID</th>
                          <th>Test Title</th>
                          <th>Category</th>
                          <th>Report Date</th>
                          <th>Diagnostic Lab</th>
                          <th>Clinical Findings</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody id="docTableBody">
                        @forelse ($documents as $doc)
                          <tr>
                            <td><span class="badge-modern badge-modern-primary">DOC-{{ str_pad($doc->id, 3, '0', STR_PAD_LEFT) }}</span></td>
                            <td style="font-weight: 700; color: #0f172a;">{{ $doc->title }}</td>
                            <td>
                              @if($doc->report_type == 'Pathology')
                                <span class="badge-modern badge-modern-danger"><i class="fa fa-tint mr-1"></i> Pathology</span>
                              @elseif($doc->report_type == 'Radiology')
                                <span class="badge-modern badge-modern-primary"><i class="fa fa-camera mr-1"></i> Radiology</span>
                              @elseif($doc->report_type == 'Cardiology')
                                <span class="badge-modern badge-modern-warning"><i class="fa fa-heartbeat mr-1"></i> Cardiology</span>
                              @else
                                <span class="badge-modern badge-modern-secondary">{{ $doc->report_type }}</span>
                              @endif
                            </td>
                            <td>{{ $doc->report_date }}</td>
                            <td><small class="text-muted"><i class="fa fa-hospital-o mr-1"></i> {{ $doc->laboratory ?? 'DocOp Diagnostics' }}</small></td>
                            <td style="font-size: 0.85rem; max-width: 220px;">{{ $doc->notes ?? 'Normal evaluation recorded' }}</td>
                            <td><span class="badge-modern badge-modern-success"><i class="fa fa-check"></i> Verified</span></td>
                          </tr>
                        @empty
                          <tr class="no-filter-row"><td colspan="7" class="text-center py-4 text-muted">No diagnostic reports uploaded. Click 'Add Lab Report' to log medical investigations.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- ================= INVOICES & RECEIPTS ================= -->
            <div class="tab-pane fade" id="list-inv" role="tabpanel">
              <div class="modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                  <h4 class="modern-card-title mb-0"><i class="fa fa-credit-card text-success"></i> Hospital Tax Invoices & Billing Slips</h4>
                  <button type="button" class="btn-modern-outline" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;" onclick="exportTableToCSV('patientInvoiceTable', 'DocOp_Hospital_Invoices.csv')">
                    <i class="fa fa-download mr-1"></i> Export CSV
                  </button>
                </div>

                <!-- Instant Table Search Filter -->
                <div class="table-filter-group">
                  <div class="table-filter-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="invTableInput" class="table-filter-input" placeholder="Search invoices by invoice number, doctor, or date..." onkeyup="filterTable('invTableInput', 'invTableBody', 'invTableCount')">
                    <span class="search-kbd-hint"><kbd>/</kbd></span>
                  </div>
                  <span class="table-filter-badge" id="invTableCount">{{ $appointments->where('userStatus', 1)->count() }} Invoices</span>
                </div>

                <div class="modern-table-card">
                  <div class="table-responsive">
                    <table class="modern-table" id="patientInvoiceTable">
                      <thead>
                        <tr>
                          <th>Invoice #</th>
                          <th>Date</th>
                          <th>Doctor / Department</th>
                          <th>Consultation Fee</th>
                          <th>Pharmacy / Labs</th>
                          <th>Total Amount (Incl. 5% Tax)</th>
                          <th>Payment Status</th>
                          <th>Receipt</th>
                        </tr>
                      </thead>
                      <tbody id="invTableBody">
                        @forelse ($appointments->where('userStatus', 1) as $app)
                          @php
                            $hasRx = $app->isCompleted();
                            $medEst = $hasRx ? 25.00 : 0.00;
                            $feeEst = floatval($app->docFees);
                            $facEst = 15.00;
                            $sub = $feeEst + $medEst + $facEst;
                            $tax = $sub * 0.05;
                            $tot = $sub + $tax;
                            $invNum = 'INV-2026-' . str_pad($app->ID, 4, '0', STR_PAD_LEFT);
                          @endphp
                          <tr>
                            <td style="font-weight: 700; color: #0f172a;">
                              <span class="badge-modern badge-modern-primary">{{ $invNum }}</span>
                            </td>
                            <td>{{ $app->appdate }}</td>
                            <td style="font-weight: 600;">Dr. {{ $app->doctor }}</td>
                            <td>${{ number_format($feeEst, 2) }}</td>
                            <td>${{ number_format($medEst, 2) }}</td>
                            <td style="font-weight: 700; color: #16a34a;">${{ number_format($tot, 2) }}</td>
                            <td><span class="badge-modern badge-modern-success"><i class="fa fa-check"></i> Paid Online</span></td>
                            <td>
                              <button type="button" class="btn-modern-outline" style="padding: 0.3rem 0.75rem; font-size: 0.775rem;" onclick="openInvoiceModal('{{ $invNum }}', '{{ addslashes($app->doctor) }}', '{{ $app->appdate }}', '{{ $app->apptime }}', '{{ $feeEst }}', '{{ $medEst }}', '{{ addslashes(session('fname') . ' ' . session('lname')) }}', '{{ session('pid') }}')">
                                <i class="fa fa-print mr-1"></i> Tax Receipt
                              </button>
                            </td>
                          </tr>
                        @empty
                          <tr class="no-filter-row"><td colspan="8" class="text-center py-4 text-muted">No billable consultations recorded yet.</td></tr>
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
              <div class="label">Consulting Doctor</div>
              <div class="val" id="rxDoctorName">-</div>
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
              <i class="fa fa-info-circle mr-1"></i> Doctor's Clinical Instructions & Dosage
            </strong>
            <div id="rxInstructionVal" style="color: #1e293b; font-size: 0.925rem; line-height: 1.5;">-</div>
          </div>

          <div class="rx-footer-stamp">
            <div>
              <small class="text-muted d-block">&bull; Valid at DocOp Dispensary & authorized pharmacies.</small>
              <small class="text-muted d-block">&bull; Emergency line: <strong>+1 (800) 911-DOCOP</strong></small>
            </div>
            <div class="rx-sign-box">
              <div style="font-family: 'Brush Script MT', cursive, sans-serif; font-size: 1.5rem; color: #1e3a8a;" id="rxDoctorSignature">Dr. Practitioner</div>
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
  <div class="modal fade" id="modalAppPass" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 20px 40px rgba(15,23,42,0.15);">
        <div class="modal-body p-4 printable-area">
          <div class="token-pass-card">
            <div class="token-pass-header">
              <div>
                <h4 style="margin: 0; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 0.5rem; font-size: 1.25rem;">
                  <i class="fa fa-heartbeat text-primary"></i> DocOp Medical Pass
                </h4>
                <p style="margin: 0.2rem 0 0; font-size: 0.8rem; color: #64748b;">Consultation Check-In Token</p>
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
                <div class="text-muted text-uppercase" style="font-size: 0.7rem; font-weight: 700;">Consultation Fee</div>
                <div style="font-weight: 700; color: #16a34a;" id="passFees">-</div>
              </div>
              <div>
                <div class="text-muted text-uppercase" style="font-size: 0.7rem; font-weight: 700;">Verification Status</div>
                <span class="badge-modern badge-modern-success"><i class="fa fa-check"></i> Confirmed</span>
              </div>
            </div>

            <div class="barcode-simulation">
              <div class="barcode-lines"></div>
              <small class="text-muted" style="font-size: 0.75rem; letter-spacing: 0.15em; font-family: monospace;" id="passBarcodeText">DOC-OP-TOKEN</small>
            </div>
            <p class="text-center text-muted mt-3 mb-0" style="font-size: 0.75rem;">
              Please present this pass at Hospital Reception Desk 15 mins prior to slot.
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
  <!-- ================= HOSPITAL TAX INVOICE PRINTABLE MODAL ================= -->
  <div class="modal fade" id="modalHospitalInvoice" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content rx-doc-modal">
        <div class="rx-header-band" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0369a1 100%);">
          <div class="rx-hospital-brand">
            <h3><i class="fa fa-hospital-o text-info"></i> DocOp Healthcare Center</h3>
            <p>Official Tax Invoice & Outpatient Hospital Receipt &bull; GSTIN: 27AABCT8812K1Z9</p>
          </div>
          <div class="rx-meta-tag">
            <div style="font-weight: 700; font-size: 0.95rem;" id="invModalNumber">INV-2026-0000</div>
            <div>Date: <span id="invModalDate">-</span></div>
          </div>
        </div>

        <div class="rx-body-content printable-area" style="padding: 1.5rem 2rem;">
          <div class="row mb-4">
            <div class="col-sm-6 mb-2">
              <small class="text-muted text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Billed To Patient</small>
              <div style="font-weight: 700; font-size: 1.1rem; color: #0f172a;" id="invModalPatName">-</div>
              <div style="font-size: 0.85rem; color: #475569;">Patient ID: <span id="invModalPatId">-</span></div>
              <div style="font-size: 0.85rem; color: #475569;">Payment Method: Verified DocOp Portal (Pre-paid)</div>
            </div>
            <div class="col-sm-6 text-sm-right mb-2">
              <small class="text-muted text-uppercase font-weight-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Consulting Physician</small>
              <div style="font-weight: 700; font-size: 1.1rem; color: #0f172a;" id="invModalDocName">-</div>
              <div style="font-size: 0.85rem; color: #475569;">Slot Time: <span id="invModalTime">-</span></div>
              <div style="font-size: 0.85rem; color: #16a34a; font-weight: 600;"><i class="fa fa-check-circle"></i> Service Rendered</div>
            </div>
          </div>

          <div class="table-responsive mb-4">
            <table class="table table-bordered" style="font-size: 0.9rem;">
              <thead style="background: #f1f5f9; color: #1e293b;">
                <tr>
                  <th>#</th>
                  <th>Service Description</th>
                  <th>Category</th>
                  <th class="text-right">Unit Price</th>
                  <th class="text-right">Total ($)</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Specialist Physician Clinical Consultation</td>
                  <td>Professional OPD</td>
                  <td class="text-right" id="invModalDocFee">$0.00</td>
                  <td class="text-right font-weight-bold" id="invModalDocFeeTotal">$0.00</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Clinic OPD Facility, Vitals & Electronic Registration</td>
                  <td>Hospital Infrastructure</td>
                  <td class="text-right" id="invModalFacilityFee">$15.00</td>
                  <td class="text-right font-weight-bold">$15.00</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Formulary Dispensation & Clinical Pharmacy Charge</td>
                  <td>Pharmaceutical Services</td>
                  <td class="text-right" id="invModalMedFee">$0.00</td>
                  <td class="text-right font-weight-bold" id="invModalMedFeeTotal">$0.00</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.8rem; color: #64748b;">
                <strong>Terms & Notes:</strong>
                <div>&bull; Computer-generated tax invoice. No signature required.</div>
                <div>&bull; Eligible for Medical Insurance reimbursement & Health Savings.</div>
                <div>&bull; DocOp Support Helpline: +1 (800) 432-DOCTOR</div>
              </div>
            </div>
            <div class="col-md-6">
              <div style="display: flex; justify-content: space-between; padding: 0.35rem 0; font-size: 0.9rem; color: #475569;">
                <span>Subtotal:</span>
                <strong id="invModalSubtotal">$0.00</strong>
              </div>
              <div style="display: flex; justify-content: space-between; padding: 0.35rem 0; font-size: 0.9rem; color: #475569;">
                <span>Applicable Health Care GST (5%):</span>
                <strong id="invModalTax">$0.00</strong>
              </div>
              <div style="display: flex; justify-content: space-between; padding: 0.75rem 0; font-size: 1.15rem; font-weight: 800; color: #0f172a; border-top: 2px solid #e2e8f0; margin-top: 0.5rem;">
                <span>Grand Total Paid:</span>
                <span style="color: #2563eb;" id="invModalGrandTotal">$0.00</span>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer no-print" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
          <button type="button" class="btn-modern-outline" data-dismiss="modal">Close</button>
          <button type="button" class="btn-modern-primary" style="max-width: 220px;" onclick="window.print()">
            <i class="fa fa-print mr-1"></i> Print Tax Receipt
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection
