<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ asset('img/sabong-aficionado.ico') }}" type="image/x-icon">
  <title>{{ config('app.name', 'Sabong Aficionado') }}</title>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">

  <!-- Scripts -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('css/sweetalert.min.css') }}"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}

  @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/app.css'])

  <!-- Custom -->
  <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}" type="text/css">
  @yield('additional-styles')
  <style>
    .active-nav {
      color: var(--bs-link-hover-color);
      font-weight: 700;
    }
    .icon-logo {
      max-height: 3rem;
    }
    .logo-container {
      display: flex;
      flex-direction: row;
      flex-wrap: nowrap;
      align-items: center;
    }
    .dataTables_wrapper .dataTables_paginate a.paginate_button {
      margin: 0px;
    }
    #dropdown-menu {
      inset:unset !important;
      transform: none !important;
    }
  </style>

</head>
<body class="dark-mode">
  <header>
    <nav class="flex flex-wrap items-center justify-between w-full py-2 md:py-0 px-4 text-lg text-gray-700 bg-white">
      <div >
        <a href="{{ url('/') }}" class="logo-container" class="flex">
          <img class="icon-logo" src="{{ asset('img/sabong-aficionado-icon.png') }}" alt="Sabong Aficionado">
          <p class="pl-2 font-medium font-mono text-xl">SABONG AFICIONADO</p>
        </a>
      </div>
      <svg xmlns="http://www.w3.org/2000/svg" id="menu-button" class="h-6 w-6 cursor-pointer md:hidden block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
      <div class="hidden w-full md:flex md:items-center md:w-auto" id="menu">
        <ul class="text-base md:flex md:justify-between md:pt-0 uppercase">
          @guest
          @if (Route::has('login'))
          <li><a class="md:p-4 py-2 block" href="{{ route('login') }}">{{ __('Login') }}</a></li>
          @endif

          @if (Route::has('register'))
          <li><a class="md:p-4 py-2 block" href="{{ route('register') }}">{{ __('Register') }}</a></li>
          @endif
          @else
          @if (session('role') == 'Admin')
          <li><a class="md:p-4 py-2 block" href="{{ route('users.index') }}">Manage Users</a></li>
          <li><a class="md:p-4 py-2 block" href="{{ route('roles.index') }}">Manage Role</a></li>
          @endif
          @if (session('role') == 'Operator')
          <li><a class="md:pl-4 py-2 block" href="{{ route('operator.derby.event') }}">EVENT</a></li>
          <li><a class="md:pl-4 py-2 block" href="{{ route('operator.fight') }}">FIGHT</a></li>
          <li><a class="md:pl-4 py-2 block" href="{{ route('operator.transactions') }}">TRANSACTIONS</a></li>
          @endif
          @if (session('role') == 'Player')
          <li>
            <a class="@if(Route::current()->getName() == 'play') {{ 'active-nav' }} @endif py-2 block" 
              href="{{ route('play') }}">Play</a>
          </li>
          <li>
            <a class="@if(Route::current()->getName() == 'player.bethistory') {{ 'active-nav' }} @endif md:pl-4 py-2 block" 
              href="{{ route('player.bethistory') }}">Bet History</a>
          </li>
          @endif
          <li>
            <a id="navbarDropdown" class="md:p-4 py-2 block dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
              {{ Auth::user()->username }}
            </a>
            <div class="dropdown-menu dropdown-menu-end" id="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="@if(Route::current()->getName() == 'users.profile') {{ 'active-nav' }} @endif dropdown-item md:pl-4 py-2 block" 
              href="{{ route('users.profile') }}">My Profile</a>
              <a class="@if(Route::current()->getName() == 'deposit') {{ 'active-nav' }} @endif dropdown-item md:pl-4 py-2 block" 
                href="{{ route('deposit') }}">Deposit</a>
              <a class="@if(Route::current()->getName() == 'player.withdraw') {{ 'active-nav' }} @endif dropdown-item md:pl-4 py-2 block" 
              href="{{ route('player.withdraw') }}">Withdraw</a>
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); 
                document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
              </a>
  
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </div>
          </li>
          @endguest
        </ul>
      </div>
    </nav>
  </header>
  <div id="app">
    <main class="py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-12">
            <div class="card border-none" style="background-color: #454d55 !important">
              <div class="card-body">
                @yield('content')
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

  </div>
</body>
</html>
{{-- <script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript" ></script> --}}
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js" type="text/javascript" ></script>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> --}}
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js" type="text/javascript" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" type="text/javascript"></script>
@yield('additional-scripts')
<script>
  const button = document.querySelector('#menu-button');
  const menu = document.querySelector('#menu');
  button.addEventListener('click', () => {
    menu.classList.toggle('hidden');
  });

  $(function(){
    setTimeout(function() {
      $('.fade-message').slideUp();
    }, 5000);
  });
</script>
