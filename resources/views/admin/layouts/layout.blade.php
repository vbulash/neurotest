<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }}@stack('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin.css') }}">
    <!-- Page styles -->
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini" @yield('body-params')>
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            {{--            <li class="nav-item d-none d-sm-inline-block">--}}
            {{--                <a href="../../index3.html" class="nav-link">Home</a>--}}
            {{--            </li>--}}
            {{--            <li class="nav-item d-none d-sm-inline-block">--}}
            {{--                <a href="#" class="nav-link">Contact</a>--}}
            {{--            </li>--}}
            @yield('back')
        </ul>

        <!-- SEARCH FORM -->
        <form class="form-inline ml-3">
            {{--            <div class="input-group input-group-sm">--}}
            {{--                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">--}}
            {{--                <div class="input-group-append">--}}
            {{--                    <button class="btn btn-navbar" type="submit">--}}
            {{--                        <i class="fas fa-search"></i>--}}
            {{--                    </button>--}}
            {{--                </div>--}}
            {{--            </div>--}}
        </form>

        <!-- Right navbar links -->
        {{--        <ul class="navbar-nav ml-auto">--}}
        {{--            <!-- Messages Dropdown Menu -->--}}
        {{--            <li class="nav-item dropdown">--}}
        {{--                <a class="nav-link" data-toggle="dropdown" href="#">--}}
        {{--                    <i class="far fa-comments"></i>--}}
        {{--                    <span class="badge badge-danger navbar-badge">3</span>--}}
        {{--                </a>--}}
        {{--                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">--}}
        {{--                    <a href="#" class="dropdown-item">--}}
        {{--                        <!-- Message Start -->--}}
        {{--                        <div class="media">--}}
        {{--                            <img src="{{ asset('assets/admin/img/user1-128x128.jpg') }}" alt="User Avatar"--}}
        {{--                                 class="img-size-50 mr-3 img-circle">--}}
        {{--                            <div class="media-body">--}}
        {{--                                <h3 class="dropdown-item-title">--}}
        {{--                                    Brad Diesel--}}
        {{--                                    <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>--}}
        {{--                                </h3>--}}
        {{--                                <p class="text-sm">Call me whenever you can...</p>--}}
        {{--                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                        <!-- Message End -->--}}
        {{--                    </a>--}}
        {{--                    <div class="dropdown-divider"></div>--}}
        {{--                    <a href="#" class="dropdown-item">--}}
        {{--                        <!-- Message Start -->--}}
        {{--                        <div class="media">--}}
        {{--                            <img src="{{ asset('assets/admin/img/user8-128x128.jpg') }}" alt="User Avatar"--}}
        {{--                                 class="img-size-50 img-circle mr-3">--}}
        {{--                            <div class="media-body">--}}
        {{--                                <h3 class="dropdown-item-title">--}}
        {{--                                    John Pierce--}}
        {{--                                    <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>--}}
        {{--                                </h3>--}}
        {{--                                <p class="text-sm">I got your message bro</p>--}}
        {{--                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                        <!-- Message End -->--}}
        {{--                    </a>--}}
        {{--                    <div class="dropdown-divider"></div>--}}
        {{--                    <a href="#" class="dropdown-item">--}}
        {{--                        <!-- Message Start -->--}}
        {{--                        <div class="media">--}}
        {{--                            <img src="{{ asset('assets/admin/img/user3-128x128.jpg') }}" alt="User Avatar"--}}
        {{--                                 class="img-size-50 img-circle mr-3">--}}
        {{--                            <div class="media-body">--}}
        {{--                                <h3 class="dropdown-item-title">--}}
        {{--                                    Nora Silvester--}}
        {{--                                    <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>--}}
        {{--                                </h3>--}}
        {{--                                <p class="text-sm">The subject goes here</p>--}}
        {{--                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                        <!-- Message End -->--}}
        {{--                    </a>--}}
        {{--                    <div class="dropdown-divider"></div>--}}
        {{--                    <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>--}}
        {{--                </div>--}}
        {{--            </li>--}}
        {{--            <!-- Notifications Dropdown Menu -->--}}
        {{--            <li class="nav-item dropdown">--}}
        {{--                <a class="nav-link" data-toggle="dropdown" href="#">--}}
        {{--                    <i class="far fa-bell"></i>--}}
        {{--                    <span class="badge badge-warning navbar-badge">15</span>--}}
        {{--                </a>--}}
        {{--                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">--}}
        {{--                    <span class="dropdown-item dropdown-header">15 Notifications</span>--}}
        {{--                    <div class="dropdown-divider"></div>--}}
        {{--                    <a href="#" class="dropdown-item">--}}
        {{--                        <i class="fas fa-envelope mr-2"></i> 4 new messages--}}
        {{--                        <span class="float-right text-muted text-sm">3 mins</span>--}}
        {{--                    </a>--}}
        {{--                    <div class="dropdown-divider"></div>--}}
        {{--                    <a href="#" class="dropdown-item">--}}
        {{--                        <i class="fas fa-users mr-2"></i> 8 friend requests--}}
        {{--                        <span class="float-right text-muted text-sm">12 hours</span>--}}
        {{--                    </a>--}}
        {{--                    <div class="dropdown-divider"></div>--}}
        {{--                    <a href="#" class="dropdown-item">--}}
        {{--                        <i class="fas fa-file mr-2"></i> 3 new reports--}}
        {{--                        <span class="float-right text-muted text-sm">2 days</span>--}}
        {{--                    </a>--}}
        {{--                    <div class="dropdown-divider"></div>--}}
        {{--                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>--}}
        {{--                </div>--}}
        {{--            </li>--}}
        {{--            <li class="nav-item">--}}
        {{--                <a class="nav-link" data-widget="fullscreen" href="#" role="button">--}}
        {{--                    <i class="fas fa-expand-arrows-alt"></i>--}}
        {{--                </a>--}}
        {{--            </li>--}}
        {{--            <li class="nav-item">--}}
        {{--                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">--}}
        {{--                    <i class="fas fa-th-large"></i>--}}
        {{--                </a>--}}
        {{--            </li>--}}
        {{--        </ul>--}}
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
    @include('admin.partials.brand')
    <!-- .Brand Logo -->

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user (optional) -->
        @include('admin.partials.userpanel')
        <!-- .Sidebar user (optional) -->

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Spatie menu -->
                @php
                    use Spatie\Menu\Laravel\Menu;
                    Menu::macro('main', function() {
                        return Menu::new()
                            ->route('admin.index', 'Главная')
                            ->route('users.index', 'Пользователи')
                            ->route('clients.index', 'Клиенты')
                            ->setActiveFromRequest()
                            ->submenu('Конструктор тестов', function (Menu $menu) {
                            	$menu
                                    ->link('/your-first-menu', 'Your First Menu')
                                    ->link('/working-with-items', 'Working With Items')
                                    ->link('/adding-sub-menus', 'Adding Sub Menus');
                            });
                    });
                @endphp
{{--                {!! \Spatie\Menu\Laravel\Menu::main() !!}--}}
                <!-- .Spatie menu -->

                    <li class="nav-item">
                        <a href="{{ route('admin.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Главная</p>
                        </a>
                    </li>

                    <!-- Сотрудники -->
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-id-card"></i>
                            <p>Пользователи</p>
                        </a>
                    </li>
                    <!-- .Сотрудники -->

                    <!-- Клиенты -->
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-building"></i>
                            <p>Клиенты
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('clients.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-building"></i>
                                    <p>Клиенты</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link"> {{-- route('contracts.index') --}}
                                    <i class="nav-icon fas fa-file-signature"></i>
                                    <p>Контракты</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- .Клиенты -->

                    <!-- Конструктор тестов -->
                    <li class="nav-item has-treeview">
                        <a href="javascript:void(0)" class="nav-link">
                            <i class="nav-icon fas fa-drafting-compass"></i>
                            <p>Конструктор тестов
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('sets.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-question-circle"></i>
                                    <p>Наборы вопросов</p>
                                </a>
                            </li>

                            <!-- Описания ФМП -->
                            <a href="javascript:void(0)" class="nav-link">
                                <i class="nav-icon fas fa-drafting-compass"></i>
                                <p>Описания ФМП
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav-item has-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('fmptypes.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-file-alt"></i>
                                        <p>Типы описаний</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('neuroprofiles.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-file-alt"></i>
                                        <p>Нейропрофили</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('blocks.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-file-alt"></i>
                                        <p>Блоки описаний</p>
                                    </a>
                                </li>
                            </ul>
                            <!-- .Описания ФМП -->

                            <li class="nav-item">
                                <a href="{{ route('tests.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-drafting-compass"></i>
                                    <p>Тесты</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- .Конструктор тестов -->

                    <!-- Прохождение тестов -->
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-play-circle"></i>
                            <p>Прохождение тестов
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                {{-- TODO: сделать нормальный вызов после отладки --}}
                                <a href="{{ route('player.play', ['mkey' => 'mkey_60501e2ae282a2.08095650']) }}"
                                   class="nav-link">
                                    <i class="nav-icon fas fa-play-circle"></i>
                                    <p>Плеер</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link"> {{-- route('history.index') --}}
                                    <i class="nav-icon fas fa-file-video"></i>
                                    <p>История прохождения</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- .Прохождение тестов -->

                    <!-- Настройки -->
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Настройки
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('roles.index') }}"
                                   class="nav-link">
                                    <i class="nav-icon fas fa-mask"></i>
                                    <p>Роли</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- .Настройки -->

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <div class="container-fluid mt-2">
            <div class="row">
                <div class="col-12">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="list-unstyled mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{!!  $error !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session()->has('success'))
                        <div class="alert alert-success mb-0">
                            {!! session('success') !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @yield('content')
        @yield('modals')
    </div>
    <!-- /.content-wrapper -->

@include('admin.partials.footer')

<!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<script src="{{ asset('assets/admin/js/admin.js') }}"></script>
<script>
    String.prototype.trimRight = function (charlist) {
        if (charlist === undefined)
            charlist = "\s";
        return this.replace(new RegExp("[" + charlist + "]+$"), "");
    };

    $(`.nav-sidebar a`).each(function () {
        let location = window.location.protocol + '//' + window.location.host + window.location.pathname;
        let link = this.href;
        //let cleared = link.trimRight("#");
        if (link === location) {
            $(this).addClass('active');
            $(this).parents('.has-treeview').addClass('menu-open');
        }
    });
</script>
<!-- Page file / URL scripts -->
@stack('scripts')
<!-- Page manual scripts -->
@stack('scripts.injection')

</body>
</html>
