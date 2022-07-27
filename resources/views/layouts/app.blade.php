<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->

  <!-- Styles -->
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  @stack('plotting-js')
  <script src="{!! mix('js/manifest.js')!!}"></script>
  <script src="{!! mix('js/vendor.js')!!}"></script>
  <script src="{!! mix('js/app.js')!!}"></script>

  <!-- Page specific styles -->
  @yield('style')
</head>

<body>
  <a id="back-top"></a>
  <div id="app">
    <nav class="navbar navbar-expand-lg bg-light top-nav">
      <div class="container">
        <a id="nav-title" href="/">
          {{ config('app.name', 'Laravel') }}
        </a>
        <button style="color:#fff;font-size:20px" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
          <span class="navbar-toggler-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
            </svg>
          </span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Left Side Of Navbar -->
          <ul class="nav navbar-nav mr-auto">
            <li class="nav-item">
              <a href="/projects" class="nav-link nav-menu">Projects</a>
            </li>
            @if(Auth::check())
            <li class="nav-item dropdown">
              <div class="btn-group workspace">
                <a class="nav-link nav-menu dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Workspace
                </a>
                <div class="dropdown-menu workspace-dropdown">
                  <a class="dropdown-item" href="/workspace">Management Center</a>
                  @if(Auth::user()->name == 'admin')
                  <hr>
                  <a class="dropdown-item" href="/workspace/institutions">Manage Institutions</a>
                  <a class="dropdown-item" href="/workspace/species">Manage Species</a>
                  <a class="dropdown-item" href="/workspace/applications">Manage Applications</a>
                  <a class="dropdown-item" href="/workspace/manageRunning">Manage Running Samples</a>
                  <a class="dropdown-item" href="/workspace/manageWaiting">Manage Waiting Samples</a>
                  <a class="dropdown-item" href="/workspace/pipelineParams">Basic Pipeline Params</a>
                  <a class="dropdown-item" href="/workspace/ncbifilesList">NCBI Files List</a>
                  @endif
                  <hr>
                  <a class="dropdown-item" href="/workspace/myLab">My Labs</a>
                  <a class="dropdown-item" href="/workspace/myProject">My Projects</a>
                  <a class="dropdown-item" href="/workspace/runningSample">Pipeline Status</a>
                  <a class="dropdown-item" href="/workspace/addSampleFiles">Add Sample Files</a>
                </div>
              </div>
            </li>
            @endif
            <li class="nav-item dropdown">
              <div class="btn-group workspace">
                <a class="nav-link nav-menu dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Protocols
                </a>
                <div class="dropdown-menu workspace-dropdown">
                  <a class="dropdown-item" href="/">RAGE-Seq Seawater</a>
                  <a class="dropdown-item" href="/">RAGE-Seq Soil</a>
                  <a class="dropdown-item" href="/">RAGE-Seq Clinical</a>
                </div>
              </div>
            </li>
            <li class="nav-item dropdown">
              <div class="btn-group workspace">
                <a class="nav-link nav-menu dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Tools
                </a>
                <div class="dropdown-menu workspace-dropdown">
                  <a class="dropdown-item" href="/apps/app/ramand2o">RamanD2O</a>
                  <a class="dropdown-item" href="/visualization">Visualization</a>
                </div>
              </div>
            </li>
            <li class="nav-item">
              <a href="/aboutus" class="nav-link nav-menu">About</a>
            </li>
          </ul>

          <!-- Right Side Of Navbar -->
          <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
            @if (Route::has('register'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
            @endif
            @else
            <li class="nav-item dropdown">
              <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <svg class="bi bi-person-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                </svg>
                <span>{{ Auth::user()->name }}<span class="caret"></span></span>
              </a>

              <div id="Logout" class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </div>
            </li>
            @endguest
          </ul>
        </div>
      </div>
    </nav>
    @if(!strpos(URL::current(),'/aboutus'))
    <div class="banner">
      <div class="banner-image"></div>
    </div>
    @endif

    <main class="pt-4">
      <div class="text-center flash_msg">
        @include('flash::message')
      </div>
      @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-3 mt-3 bg-secondary w-100">
      <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; Single Cell Center, Qingdao Institute of Bioenergy and Bioprocess Technology, Chinese Academy of Sciences 2020-2022</p>
      </div>
    </footer>

  </div>

  <!-- Page specific script -->
  @yield('script')

</body>

</html>
