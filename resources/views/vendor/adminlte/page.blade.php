@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} ">
    @stack('css')
    @yield('css')
@stop

@section('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : ''))

@section('body')
<style type="text/css">
    .box{
        color: #222;
    }
    .navbar-nav li{
        font-size: 12px;
    }
</style>
<style>
/* width */
::-webkit-scrollbar {
  width: 5px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey; 
  border-radius: 10px;
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #f39c12; 
  border-radius: 10px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #885d1a; 
}
</style>
    <div class="wrapper " >

        <!-- Main Header -->
        <header class="main-header">
            @if(config('adminlte.layout') == 'top-nav')
            <nav class="navbar navbar-fixed-top" style="background: #001f3f!important; ">
                <div class="container-fluid">
                    <div class="navbar-headertext-center">
                        <a href="{{ url('/') }}" class=" bg bg-primary  navbar-brand text-center">
                          <b>NUWSP</b>
                        </a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            @if(Auth::User())
                                   @each('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item')
                            @endif

                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
            @else
            <!-- Logo -->
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">{!! config('adminlte.logo_mini', '<b>A</b>LT') !!}</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle fa5" data-toggle="push-menu" role="button">
                    <span class="sr-only">{{ trans('adminlte::adminlte.toggle_navigation') }}</span>
                </a>
            @endif
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">

                    <ul class="nav navbar-nav" >
                        @if(Auth::user())
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                              <img src="{{asset('ava.png')}}" class="user-image" alt="User Image">
                              <span class="hidden-xs"> {{strtoupper(Auth::user()->name)}} ({{HP::fokus_tahun()}})</span>
                            </a>
                            <ul class="dropdown-menu">
                              <!-- User image -->
                              <li class="user-header">
                                <img src="{{asset('ava.png')}}" class="img-circle" alt="User Image">

                                <p>
                                {{strtoupper(Auth::user()->name)}}
                                  <small>{{Auth::user()->email}} </small>
                                  <b>{{HP::fokus_tahun()}}</b>
                                </p>
                              </li>
                              <!-- Menu Body -->
                              <li class="user-body">
                        
                              </li>
                              <!-- Menu Footer-->
                              <li class="user-footer">
                                <div class="pull-left">
                                  <a href="{{url('dash-admin')}}" class="btn btn-default btn-flat">ADMIN</a>
                                </div>
                                <div class="pull-right">
                                    <a class="btn btn-default btn-flat" href="{{ url(config('adminlte.logout_url', 'auth/logout')) }}">
                                        <i class="fa fa-fw fa-power-off"></i> 
                                  </a>
                                 
                                </div>
                              </li>
                            </ul>
                          </li>

                        @endif

                      <!--  -->
                        @if(config('adminlte.right_sidebar') and (config('adminlte.layout') != 'top-nav'))
                        <!-- Control Sidebar Toggle Button -->
                            <li>
                                <a href="#" data-toggle="control-sidebar" @if(!config('adminlte.right_sidebar_slide')) data-controlsidebar-slide="false" @endif>
                                    <i class="{{config('adminlte.right_sidebar_icon')}}"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
                @if(config('adminlte.layout') == 'top-nav')
                </div>
                @endif
            </nav>
        </header>

        @if(config('adminlte.layout') != 'top-nav')
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree">
                    @each('adminlte::partials.menu-item', $adminlte->menu(), 'item')
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        @endif

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="background:linear-gradient(120deg, rgba(0,0,36,1) 1%, rgba(9,72,121,1) 33%, rgba(20,151,218,1) 86%, rgba(10,228,236,0.6842087176667542) 98%); color:#fff; border-top:2px solid #fff; margin-top: 50px;">
            @if(config('adminlte.layout') == 'top-nav')
            <div class="container-fluid">
            @endif

            <!-- Content Header (Page header) -->
            <section class="content-header container-fluid" style="padding-left:0px;padding-right:0px; padding-top:0px; margin-bottom: 15px;">
                @yield('content_header')
            </section>

            <!-- Main content -->
            <section class="content-fuild">

                @yield('content')

            </section>
            <!-- /.content -->
            @if(config('adminlte.layout') == 'top-nav')
            </div>
            <!-- /.container -->
            @endif
        </div>
        <!-- /.content-wrapper -->

        @hasSection('footer')
        <footer class="main-footer">
            @yield('footer')
        </footer>
        @endif

        @if(config('adminlte.right_sidebar') and (config('adminlte.layout') != 'top-nav'))
            <aside class="control-sidebar control-sidebar-{{config('adminlte.right_sidebar_theme')}}">
                @yield('right-sidebar')
            </aside>
            <!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        @endif

    </div>
    <!-- ./wrapper -->
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @stack('js')
    @yield('js')
@stop
