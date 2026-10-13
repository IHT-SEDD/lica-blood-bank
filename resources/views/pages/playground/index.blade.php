@extends('layouts.vertical', ['title' => __('Dev Playground')])

@section('styles')
@endsection

@section('content')
<div class="row py-3">
 <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
  {{-- Title --}}
  <h1 class="fw-bold uppercase">{{ __('Developer Playground') }}</h1>
 </div>

 <div class="col-2">
  <ul class="side-nav">
   <li class="side-nav-title mt-2">API Endpoints</li>
   <li class="side-nav-item">
    <a data-bs-toggle="collapse" href="#sidebarApis" aria-expanded="false" aria-controls="sidebarApis"
     class="side-nav-link {{ request()->Is('master*') ? 'active' : '' }}">
     <span class="menu-icon"><i data-lucide="notebook-text"></i></span>
     <span class="menu-text">SIMRS</span>
     <span class="menu-arrow"></span>
    </a>

    <div class="collapse" id="sidebarApis">
     <ul class="sub-menu">
      <li class="side-nav-item">
       <a href="#" class="side-nav-link">
        <span class="menu-icon"><i data-lucide="dot"></i></span>
        <span class="menu-text">{{ Str::headline('New Blood Request') }}</span>
       </a>
      </li>
      <li class="side-nav-item">
       <a href="#" class="side-nav-link">
        <span class="menu-icon"><i data-lucide="dot"></i></span>
        <span class="menu-text">{{ Str::headline('Send Result') }}</span>
       </a>
      </li>
     </ul>
    </div>
   </li>
  </ul>
 </div>
</div>
@endsection

@section('scripts')
@endsection