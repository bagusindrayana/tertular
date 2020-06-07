<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Admin For Goblog - Simple Blog System With Laravel" />
        <meta name="author" content="Bagus Indrayana" />
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('admin/css/styles.css') }}" rel="stylesheet" />
        @stack('styles')

        
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a><button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button
            ><!-- Navbar Search-->
            {{-- <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" />
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form> --}}
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto mr-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        {{-- <a class="dropdown-item" href="#">Settings</a><a class="dropdown-item" href="#">Activity Log</a> --}}
                        {{-- <div class="dropdown-divider"></div> --}}
                        <a class="dropdown-item" href="{{ route('admin.logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('admin.logout') }}" method="Wilayah" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="{{ route('admin.home') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            @if (Auth::user()->level == "Admin")
                                <div class="sb-sidenav-menu-heading">Master Data</div>
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseWilayah" aria-expanded="false" aria-controls="collapseWilayah"
                                    ><div class="sb-nav-link-icon"><i class="fas fa-map"></i></div>
                                    Wialayah
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div
                                ></a>
                                <div class="collapse" id="collapseWilayah" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="{{ route('admin.provinsi.index') }}">Provinsi</a>
                                        <a class="nav-link" href="{{ route('admin.kota.index') }}">Kota</a>
                                        <a class="nav-link" href="{{ route('admin.kecamatan.index') }}">Kecamatan</a>
                                        <a class="nav-link" href="{{ route('admin.kelurahan.index') }}">Kelurahan</a>
                                    </nav>
                                </div>
                                <a class="nav-link" href="{{ route('admin.klaster.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fa fa-sign-in-alt"></i></div>
                                    Klaster
                                </a>
                            @endif
                            <div class="sb-sidenav-menu-heading">Main Data</div>
                            <a class="nav-link" href="{{ route('admin.pasien.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Pasien
                            </a>
                            @if (Auth::user()->level == "Admin")
                                <div class="sb-sidenav-menu-heading">Setting</div>
                                <a class="nav-link" href="{{ route('admin.user.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                    User
                                </a>
                            @endif
                            
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        {{ Auth::user()->nama }}
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success" role="alert">
                                {{ $message }}
                            </div>
                        @endif

                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @endif

                        @if ($message = Session::get('warning'))
                            <div class="alert alert-warning" role="alert">
                                {{ $message }}
                            </div>
                        @endif
                        
                        <ol class="breadcrumb mb-4 mt-4">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                            @yield('breadcrumb')
                        </ol>

                       
                        @yield('content')
                        
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; {{ config('app.name', 'Laravel') }} {{ date('Y') }}</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>


        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" ></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('admin/js/scripts.js') }}"></script>
        @stack('scripts')
        
    </body>
</html>