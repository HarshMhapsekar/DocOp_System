@extends('layouts.app')

@section('title', $title . ' - DocOp Healthcare')
@section('body-class', 'light-page-wrapper')

@section('navbar')
  <!-- Top Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top modern-navbar">
    <div class="container-fluid px-lg-4">
      <a class="navbar-brand" href="{{ $back_url }}">
        <span class="brand-icon-box"><i class="fa fa-search"></i></span>
        <span>DocOp <span style="font-weight: 400; opacity: 0.85; font-size: 0.85em;">Search Portal</span></span>
      </a>

      <div class="ml-auto">
        <a href="{{ $back_url }}" class="nav-link nav-btn-logout text-white" style="background: rgba(255,255,255,0.15) !important;">
          <i class="fa fa-arrow-left"></i> Return to Console
        </a>
      </div>
    </div>
  </nav>
@endsection

@section('content')
  <div class="dashboard-wrapper">
    <div class="container-fluid px-lg-4">

      <!-- Header Banner -->
      <div class="dashboard-header-banner" style="background: linear-gradient(135deg, #1e1b4b 0%, #1e3a8a 50%, #0284c7 100%);">
        <div class="row align-items-center">
          <div class="col-md-8">
            @if(!empty($badge_text))
              <span class="badge-modern badge-modern-primary mb-2" style="background: rgba(255,255,255,0.2); color: white; border: none;">
                {{ $badge_text }}
              </span>
            @endif
            <h1 class="dashboard-title">{{ $title }}</h1>
            <p class="dashboard-subtitle">
              Query: <strong>"{{ $query_term }}"</strong> &nbsp;|&nbsp; Matching Records: {{ count($rows) }}
            </p>
          </div>
          <div class="col-md-4 text-md-right mt-3 mt-md-0">
            <a href="{{ $back_url }}" class="btn btn-light font-weight-bold" style="border-radius: 10px;">
              <i class="fa fa-arrow-left mr-1"></i> Back to Dashboard
            </a>
          </div>
        </div>
      </div>

      <!-- Results Table Card -->
      <div class="modern-card">
        <div class="modern-card-header">
          <h4 class="modern-card-title"><i class="fa fa-list-alt text-primary"></i> Search Results Table</h4>
        </div>

        @if(!empty($error_msg))
          <div class="alert alert-warning" style="border-radius: 10px;">
            <i class="fa fa-exclamation-triangle mr-2"></i> {{ $error_msg }}
          </div>
        @else
          <div class="modern-table-card">
            <div class="table-responsive">
              <table class="modern-table">
                <thead>
                  <tr>
                    @foreach($columns as $col)
                      <th>{{ $col }}</th>
                    @endforeach
                  </tr>
                </thead>
                <tbody>
                  @forelse($rows as $row)
                    <tr>
                      @foreach($columns as $col)
                        <td>{!! $row[$col] ?? '-' !!}</td>
                      @endforeach
                    </tr>
                  @empty
                    <tr>
                      <td colspan="{{ count($columns) }}" class="text-center py-5 text-muted">
                        <i class="fa fa-info-circle fa-2x d-block mb-2 text-primary" style="opacity: 0.5;"></i>
                        No matching records found for query "<strong>{{ $query_term }}</strong>".
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        @endif
      </div>

    </div>
  </div>
@endsection
