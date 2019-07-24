<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title') {{ config('app.name', '') }}</title>
  
  <link href="{{ URL::asset('vendor/gurudin/css/admin.css') }}" rel="stylesheet">
  <link href="{{ URL::asset('css/custom.css?v='. time()) }}" rel="stylesheet">

  @yield('css')
</head>
<body>
  <div id="lay-app">
    <div class="lay-header margin-left-220">
      <ul class="nav">
        <li class="nav-item float-left">
          <a class="nav-link text-secondary currsor" id="toggle-btn"><i class="fa fa-align-justify"></i></a>
        </li>
  
        <li class="nav-item float-left mt-2">
          <div class="input-group input-group-sm">
            <input type="text" class="form-control" placeholder="MeMe ID..." ref="searchUserId">
            <div class="input-group-append">
              <button class="btn btn-light-primary" type="button">
                <i class="fa fa-search"></i>
              </button>
            </div>
          </div>
        </li>
  
        <li class="nav-item float-right">
          <a class="nav-link text-secondary currsor" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Admin
            <i class="fa fa-caret-down"></i>
          </a>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="font-size: 13px;">
            <h6 class="dropdown-header text-dark">Welcome !</h6>
            <a class="dropdown-item text-secondary" href="#">
              <i class="fa fa-cog"></i>&nbsp;&nbsp;Settings
            </a>
            <a class="dropdown-item text-secondary" href="{{ route('admin.logout') }}">
              <i class="fa fa-sign-out-alt"></i>&nbsp;&nbsp;Logout
            </a>
          </div>
        </li>

        <li class="nav-item float-right">
          <a class="nav-link text-secondary currsor" data-toggle="modal" data-target="#selectRegionModal">
            <i class="fa fa-globe"></i>
            DEFAULT
            <span class="fa fa-angle-down"></span>
          </a>
        </li>

        <li class="nav-item float-right">
          <a class="nav-link text-secondary currsor">
            <i class="fa fa-expand-arrows-alt"></i>
            {{-- <i class="fa fa-compress-arrows-alt"></i> --}}
          </a>
        </li>
      </ul>
    </div>

    <div class="lay-side">
      <div class="lay-side-scroll">
        <p class="nav-hover"></p>
  
        <a href="/" class="lay-logo">
          <img width="34px" height="34px" style="margin-top: -10px;" src="http://cms-dev-admin.meme.chat/images/logo_grey.png" alt="">
          &nbsp;
          <span>MeMe Admin</span>
        </a>

        <div class="">
          <ul class="lay-menu">
            <li class="lay-menu-item">
              <a href="">
                <i class="fa fa-cubes"></i>
                <cite>App Config</cite>
                <span class="fa fa-caret-down"></span>
              </a>
              <dl class="lay-nav-child" style="display: none;">
                <dd>
                  <a href="javascript:;" class="uri-to">
                    <cite>Agents</cite>
                    <span></span>
                  </a>
                </dd>
                <dd>
                  <a href="javascript:;" class="uri-to">
                    <cite>Article Management</cite>
                    <span class="fa fa-caret-down"></span>
                  </a>
                  <dl class="lay-nav-child" style="display: none;">
                    <dd>
                      <a href="javascript:;" class="uri-to">
                        <cite>Agents</cite>
                        <span></span>
                      </a>
                    </dd>
                    <dd>
                      <a href="javascript:;" class="uri-to">
                        <cite>Article Management</cite>
                        <span></span>
                      </a>
                    </dd>
                  </dl>
                </dd>
              </dl>
            </li>
            
            <li class="lay-menu-item">
              <a href="">
                <i class="fa fa-cubes"></i>
                <cite>App Config</cite>
                <span class="fa fa-caret-down"></span>
              </a>
              <dl class="lay-nav-child" style="display: none;">
                <dd>
                  <a href="javascript:;" class="uri-to">
                    <cite>Agents</cite>
                    <span></span>
                  </a>
                </dd>
                <dd>
                  <a href="javascript:;" class="uri-to">
                    <cite>Article Management</cite>
                    <span></span>
                  </a>
                </dd>
              </dl>
            </li>

            <li class="lay-menu-item">
              <a href="">
                <i class="fa fa-cubes"></i>
                <cite>App Config</cite>
                <span class="fa fa-caret-down"></span>
              </a>
              <dl class="lay-nav-child" style="display: none;">
                <dd>
                  <a href="javascript:;" class="uri-to">
                    <cite>Agents</cite>
                    <span></span>
                  </a>
                </dd>
                <dd>
                  <a href="javascript:;" class="uri-to">
                    <cite>Article Management</cite>
                    <span></span>
                  </a>
                </dd>
              </dl>
            </li>

            <li class="lay-menu-item">
              <a href="">
                <i class="fa fa-sliders-h"></i>
                <cite>System & Permission</cite>
                <span class="fa fa-caret-down"></span>
              </a>
              <dl class="lay-nav-child" style="display: block;">
                <dd>
                  <a href="javascript:;" class="uri-to">
                    <i class="fa"></i>
                    <cite>Menu</cite>
                    <span class="fa fa-caret-down"></span>
                  </a>
                  <dl class="lay-nav-child" style="display: block;">
                    <dd>
                      <a href="{{ route('admin.menu') }}" class="uri-to">
                        <i class="fa"></i>
                        <cite>List</cite>
                      </a>
                    </dd>
                    <dd>
                      <a href="javascript:;" class="uri-to">
                        <i class="fa"></i>
                        <cite>Create</cite>
                      </a>
                    </dd>
                  </dl>
                </dd>
                <dd>
                  <a href="javascript:;" class="uri-to">
                    <i class="fa"></i>
                    <cite>Permissions</cite>
                    <span class="fa fa-caret-down"></span>
                  </a>

                  <dl class="lay-nav-child" style="display: block;">
                    <dd>
                      <a href="{{ route('admin.route') }}" class="uri-to">
                        <i class="fa"></i>
                        <cite>Routes</cite>
                      </a>
                    </dd>
                    <dd>
                      <a href="{{ route('admin.permission') }}" class="uri-to">
                        <i class="fa"></i>
                        <cite>Permission</cite>
                      </a>
                    </dd>
                    <dd>
                      <a href="javascript:;" class="uri-to">
                        <i class="fa"></i>
                        <cite>Role</cite>
                      </a>
                    </dd>
                  </dl>
                </dd>
              </dl>
            </li>
          </ul>  
        </div>
      </div>
    </div>

    <div class="lay-body padding-left-220">
      @yield('content')
    </div>
  </div>
  
  <script src="{{ URL::asset('vendor/gurudin/js/admin.js') }}"></script> 
  @yield('script')
</body>
</html>