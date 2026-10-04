<nav class="navbar navbar-expand-lg navbar-dark fixed-top modern-navbar">
  <div class="container">
    <a class="navbar-brand" href="{{ route('home') }}">
      <span class="brand-icon-box"><i class="fa fa-heartbeat"></i></span>
      <span>DocOp <span style="font-weight: 400; opacity: 0.85; font-size: 0.9em;">System</span></span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto align-items-lg-center">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
            <i class="fa fa-home"></i> Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('services') ? 'active' : '' }}" href="{{ route('services') }}">
            <i class="fa fa-stethoscope"></i> Services
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
            <i class="fa fa-info-circle"></i> About Us
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
            <i class="fa fa-envelope-o"></i> Contact
          </a>
        </li>
        <li class="nav-item ml-lg-2">
          <a class="nav-link {{ request()->routeIs('staff.login.view') ? 'active' : '' }}" href="{{ route('staff.login.view') }}" style="color: #cbd5e1;">
            <i class="fa fa-shield"></i> Staff Portal
          </a>
        </li>
        <li class="nav-item ml-lg-2 mt-2 mt-lg-0">
          <button type="button" class="btn-theme-toggle" onclick="toggleTheme()" title="Switch Light/Dark Mode">
            <i class="fa fa-moon-o"></i>
          </button>
        </li>
        <li class="nav-item ml-lg-2 mt-2 mt-lg-0">
          <a class="nav-link nav-btn-logout" href="{{ route('patient.login.view') }}" style="background: rgba(37,99,235,0.2) !important; color: #93c5fd !important; border-color: rgba(96,165,250,0.3) !important;">
            <i class="fa fa-user"></i> Patient Portal
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
