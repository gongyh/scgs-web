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
  <script src="https://cdn.staticfile.org/jquery/3.6.0/jquery.min.js"></script>
  @stack('plotting-js')
  <script src="{!! mix('js/manifest.js')!!}"></script>
  <script src="{!! mix('js/vendor.js')!!}"></script>
  <script src="{!! mix('js/app.js')!!}"></script>
  <script src="{!! mix('js/layui_style.js')!!}"></script>

  <!-- Page specific styles -->
  @yield('style')
</head>

<body>
  <a id="back-top"></a>
  <ul class="header layui-nav" lay-filter="">
    <div class="container">
      <div class="row d-flex">
        <div class="d-flex flex-fill">
          <li class="layui-nav-item">
            <a href="/">
              <strong class="layui-font-18 text-white">Home</strong>
            </a>
          </li>
          <li class="layui-nav-item">
            <a href="/projects">
              <strong class="layui-font-18 text-white">Projects</strong>
            </a>
          </li>
          @if(Auth::check())
          <li class="layui-nav-item">
            <a class="text-white" href="javascript:;">
              <strong class="layui-font-18 text-white">Workspace</strong>
            </a>
            <dl class="layui-nav-child">
              <!-- 二级菜单 -->
              <dd>
                <a href="/workspace">
                  <div class="layui-font-18">Management Center</div>
                </a>
              </dd>
              <hr>
              @if(Auth::user()->name == 'admin')
              <dd>
                <a href="/workspace/institutions">
                  <div class="layui-font-18">Manage Institutions</div>
                </a>
              </dd>
              <dd>
                <a href="/workspace/species">
                  <div class="layui-font-18">
                    Manage Species
                  </div>
                </a>
              </dd>
              <dd>
                <a href="/workspace/applications">
                  <div class="layui-font-18">
                    Manage Applications
                  </div>
                </a>
              </dd>
              <dd>
                <a href="/workspace/manageRunning">
                  <div class="layui-font-18">
                    Manage Running Samples
                  </div>
                </a>
              </dd>
              <dd>
                <a href="/workspace/manageWaiting">
                  <div class="layui-font-18">
                    Manage Waiting Samples
                  </div>
                </a>
              </dd>
              <dd>
                <a href="/workspace/pipelineParams">
                  <div class="layui-font-18">
                    Basic Pipeline Params
                  </div>
                </a>
              </dd>
              <dd>
                <a href="/workspace/ncbifilesList">
                  <div class="layui-font-18">
                    NCBI Files List
                  </div>
                </a>
              </dd>
              @endif
              <hr>
              <dd>
                <a href="/workspace/myLab">
                  <div class="layui-font-18">
                    My labs
                  </div>
                </a>
              </dd>
              <dd>
                <a href="/workspace/myProject">
                  <div class="layui-font-18">
                    My projects
                  </div>
                </a>
              </dd>
              <dd>
                <a href="/workspace/runningSample">
                  <div class="layui-font-18">
                    Pipeline status
                  </div>
                </a>
              </dd>
              <dd>
                <a href="/workspace/addSampleFiles">
                  <div class="layui-font-18">
                    Upload sample files
                  </div>
                </a>
              </dd>
            </dl>
          </li>
          @endif
          <li class="layui-nav-item">
            <a class="text-white" href="javascript:;">
              <strong class="layui-font-18 text-white">Protocols</strong>
            </a>
            <dl class="layui-nav-child">
              <!-- 二级菜单 -->
              <dd>
                <a href="/">
                  <div class="layui-font-18">RAGE-Seq Seawater</div>
                </a>
              </dd>
              <dd>
                <a href="/">
                  <div class="layui-font-18">RAGE-Seq Soil</div>
                </a>
              </dd>
              <dd>
                <a href="/">
                  <div class="layui-font-18">RAGE-Seq Clinical</div>
                </a>
              </dd>
            </dl>
          </li>
          <li class="layui-nav-item">
            <a class="text-white" href="javascript:;">
              <strong class="layui-font-18 text-white">Tools</strong>
            </a>
            <dl class="layui-nav-child">
              <!-- 二级菜单 -->
              <dd>
                <a href="/apps/app/ramand2o">
                  <div class="layui-font-18">RamanD2O</div>
                </a>
              </dd>
              <dd>
                <a href="/visualization">
                  <div class="layui-font-18">Visualization</div>
                </a>
              </dd>
            </dl>
          </li>
          <li class="layui-nav-item">
            <a href="/aboutus">
              <strong class="layui-font-18 text-white">
                About
              </strong>
            </a>
          </li>
        </div>
        <div class="d-flex justify-content-end">
          @guest
          <li class="layui-nav-item">
            <a class="nav-link text-white" href="{{ route('login') }}">
              <strong class="layui-font-18">
                {{ __('Login') }}
              </strong>
            </a>
          </li>
          @if (Route::has('register'))
          <li class="layui-nav-item">
            <a class="nav-link text-white" href="{{ route('register') }}">
              <strong class="layui-font-18">
                {{ __('Register') }}
              </strong>
            </a>
          </li>
          @endif
          @else
          <li class="layui-nav-item">
            <a id="javascript:;">
              <svg class="bi bi-person-fill mb-1" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
              </svg>
              <span class="layui-font-16 login-name">{{ Auth::user()->name }}<span class="caret"></span></span>
            </a>
            <dl class="layui-nav-child">
              <!-- 二级菜单 -->
              <dd>
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                  <span class="layui-font-16">
                    {{ __('Logout') }}
                  </span>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </dd>
            </dl>
          </li>
          @endguest
        </div>
        <!-- Authentication Links -->
      </div>
  </ul>
  @if(!strpos(URL::current(),'/aboutus'))
  <div class="banner">
    <div class="banner-image"></div>
  </div>
  @endif

  <main class="main pt-4">
    <div class="text-center flash_msg">
      @include('flash::message')
    </div>
    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="footer py-3 mt-5 w-100 layui-bg-cyan">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; Single Cell Center, Qingdao Institute of Bioenergy and Bioprocess Technology, Chinese Academy of Sciences 2020-2022</p>
    </div>
  </footer>


  <!-- Page specific script -->
  @yield('script')

</body>

</html>
