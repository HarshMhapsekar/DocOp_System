<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('title', 'DocOp Healthcare - Modern Hospital Management System')</title>
  <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.png" />

  <!-- Modern Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap & FontAwesome -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Flatpickr Modern Date Picker -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <!-- Modern UI Design System -->
  <link rel="stylesheet" href="/css/modern-ui.css?v=10">

  <script>
    (function() {
      var savedTheme = localStorage.getItem('docop_theme');
      if (savedTheme === 'dark' || (!savedTheme && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark-mode');
      }
    })();
  </script>

  @stack('styles')
</head>

<body class="@yield('body-class', 'light-page-wrapper')">
  @yield('navbar')

  <!-- Floating Modern Toast Notification System -->
  <div class="toast-notification-container" id="globalToastContainer">
    @if(session('success'))
      <div class="toast-card toast-success" id="toastSuccess">
        <div class="toast-card-icon">
          <i class="fa fa-check"></i>
        </div>
        <div class="toast-card-content">
          <div class="toast-card-title">Success</div>
          <p class="toast-card-desc">{{ session('success') }}</p>
        </div>
        <button type="button" class="toast-card-close" onclick="dismissToast('toastSuccess')">
          <i class="fa fa-times"></i>
        </button>
      </div>
    @endif

    @if(session('error'))
      <div class="toast-card toast-error" id="toastError">
        <div class="toast-card-icon">
          <i class="fa fa-exclamation"></i>
        </div>
        <div class="toast-card-content">
          <div class="toast-card-title">Notice</div>
          <p class="toast-card-desc">{{ session('error') }}</p>
        </div>
        <button type="button" class="toast-card-close" onclick="dismissToast('toastError')">
          <i class="fa fa-times"></i>
        </button>
      </div>
    @endif

    @if(isset($errors) && $errors->any())
      <div class="toast-card toast-error" id="toastValidation">
        <div class="toast-card-icon">
          <i class="fa fa-exclamation-triangle"></i>
        </div>
        <div class="toast-card-content">
          <div class="toast-card-title">Attention Needed</div>
          <div class="toast-card-desc">
            @foreach($errors->all() as $err)
              <div>&bull; {{ $err }}</div>
            @endforeach
          </div>
        </div>
        <button type="button" class="toast-card-close" onclick="dismissToast('toastValidation')">
          <i class="fa fa-times"></i>
        </button>
      </div>
    @endif
  </div>

  @yield('content')

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  <script>
    function dismissToast(id) {
      var toast = document.getElementById(id);
      if (!toast) return;
      toast.classList.add('toast-hide');
      setTimeout(function() {
        if (toast.parentNode) toast.parentNode.removeChild(toast);
      }, 250);
    }

    function showToast(title, message, type) {
      type = type || 'success';
      var container = document.getElementById('globalToastContainer');
      if (!container) return;
      var toastId = 'toast_' + Date.now();
      var iconClass = type === 'success' ? 'fa-check' : (type === 'error' ? 'fa-exclamation' : 'fa-info');
      var toastCardClass = type === 'success' ? 'toast-success' : (type === 'error' ? 'toast-error' : 'toast-info');

      var toast = document.createElement('div');
      toast.className = 'toast-card ' + toastCardClass;
      toast.id = toastId;
      toast.innerHTML = '<div class="toast-card-icon"><i class="fa ' + iconClass + '"></i></div>' +
        '<div class="toast-card-content">' +
        '<div class="toast-card-title">' + title + '</div>' +
        '<p class="toast-card-desc">' + message + '</p>' +
        '</div>' +
        '<button type="button" class="toast-card-close" onclick="dismissToast(\'' + toastId + '\')">' +
        '<i class="fa fa-times"></i>' +
        '</button>';

      container.appendChild(toast);
      setTimeout(function() {
        dismissToast(toastId);
      }, 4000);
    }

    function copyToClipboard(text, successMsg) {
      if (!text) return;
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(function() {
          showToast('Copied', successMsg || 'Copied to clipboard!', 'success');
        });
      } else {
        var textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.opacity = '0';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
          document.execCommand('copy');
          showToast('Copied', successMsg || 'Copied to clipboard!', 'success');
        } catch (err) {
          showToast('Notice', 'Unable to copy text', 'error');
        }
        document.body.removeChild(textArea);
      }
    }

    function toggleTheme() {
      var isDark = document.documentElement.classList.toggle('dark-mode');
      if (document.body) {
        document.body.classList.toggle('dark-mode', isDark);
      }
      localStorage.setItem('docop_theme', isDark ? 'dark' : 'light');
      updateThemeButtons(isDark);
    }

    function updateThemeButtons(isDark) {
      var btns = document.querySelectorAll('.btn-theme-toggle');
      btns.forEach(function(btn) {
        btn.innerHTML = isDark ? '<i class="fa fa-sun-o" style="color: #fbbf24;"></i>' : '<i class="fa fa-moon-o"></i>';
        btn.setAttribute('title', isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode');
      });
    }

    function togglePasswordVisibility(inputId, btn) {
      var input = document.getElementById(inputId);
      if (!input) return;
      var icon = btn.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        if (icon) {
          icon.classList.remove('fa-eye');
          icon.classList.add('fa-eye-slash');
        }
      } else {
        input.type = 'password';
        if (icon) {
          icon.classList.remove('fa-eye-slash');
          icon.classList.add('fa-eye');
        }
      }
    function exportTableToCSV(tableId, filename) {
      var table = document.getElementById(tableId);
      if (!table) return;
      var rows = table.querySelectorAll('tr');
      var csv = [];
      for (var i = 0; i < rows.length; i++) {
        if (rows[i].classList.contains('no-filter-row')) continue;
        var row = [], cols = rows[i].querySelectorAll('td, th');
        for (var j = 0; j < cols.length; j++) {
          if (cols[j].querySelector('button') || cols[j].querySelector('a.btn') || cols[j].querySelector('form')) continue;
          var text = (cols[j].innerText || cols[j].textContent || '').replace(/(\r\n|\n|\r)/gm, ' ').replace(/"/g, '""').trim();
          row.push('"' + text + '"');
        }
        if (row.length > 0) csv.push(row.join(','));
      }
      var csvFile = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
      var downloadLink = document.createElement('a');
      downloadLink.download = (filename || 'export') + '_' + new Date().toISOString().slice(0,10) + '.csv';
      downloadLink.href = window.URL.createObjectURL(csvFile);
      downloadLink.style.display = 'none';
      document.body.appendChild(downloadLink);
      downloadLink.click();
      document.body.removeChild(downloadLink);
      showToast('Export Complete', 'Exported data to ' + downloadLink.download, 'success');
    }

    document.addEventListener('DOMContentLoaded', function() {
      // Synchronize theme on both html and body
      var savedTheme = localStorage.getItem('docop_theme');
      var isDark = savedTheme === 'dark' || (!savedTheme && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
      if (isDark) {
        document.documentElement.classList.add('dark-mode');
        if (document.body) document.body.classList.add('dark-mode');
      } else {
        document.documentElement.classList.remove('dark-mode');
        if (document.body) document.body.classList.remove('dark-mode');
      }
      updateThemeButtons(isDark);

      // Auto dismiss blade toasts
      var toasts = document.querySelectorAll('.toast-card');
      toasts.forEach(function(toast) {
        setTimeout(function() {
          dismissToast(toast.id);
        }, 4500);
      });

      // Global keyboard shortcut: Press '/' or 'Ctrl+K' to focus active search box
      document.addEventListener('keydown', function(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.isContentEditable) {
          return;
        }
        if (e.key === '/' || ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k')) {
          e.preventDefault();
          var searchInputs = document.querySelectorAll('input[type="text"][placeholder*="Search"], input[type="search"], .modern-search-box input');
          for (var i = 0; i < searchInputs.length; i++) {
            if (searchInputs[i].offsetParent !== null) { // is visible
              searchInputs[i].focus();
              searchInputs[i].select();
              break;
            }
          }
        }
      });
    });
  </script>

  <!-- Flatpickr Date Picker JS -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  @stack('scripts')
</body>
</html>
