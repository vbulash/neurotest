<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ env('APP_NAME') }}@stack('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/front/css/front.css') }}">
    <!-- Page styles -->
    @stack('styles')
</head>
<body>
<div class="container-fluid main-header pl-4">
    <nav class="navbar navbar-dark bg-primary d-none d-lg-flex">
        <div class="navbar-brand">
            {{--            <a href="{{ route('admin.index') }}">--}}
            <i class="fas fa-home"></i>
            {{ env('APP_NAME') }}
            {{--            </a>--}}
        </div>
        <div class="navbar-text">
            <p>@stack('testname')</p>
            <div>
                @stack('step_description') <span class="step-countdown"></span>
            </div>
        </div>
    </nav>
    <div class="d-block d-lg-none">
        <div class="d-block p-1 bg-primary text-white">
            <i class="fas fa-home"></i>
            {{ env('APP_NAME') }}
        </div>
        <div class="d-block p-1 bg-primary text-white">
            @stack('testname')
        </div>
        <div class="d-block p-1 bg-primary text-white">
            @stack('step_description') <span class="step-countdown"></span>
        </div>
    </div>

    {{--    <div class="container-fluid mt-2">--}}
    {{-- Область отображения сообщений --}}
    <div class="row mt-2" style="margin-left: 0px; margin-right: 0px;">
        <div class="col-12">
            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {!! session('error') !!}
                </div>
                @php(session()->forget('error'))
            @endif

            @if (session()->has('success'))
                <div class="alert alert-success mb-0">
                    {!! session('success') !!}
                </div>
                @php(session()->forget('success'))
            @endif
        </div>
        {{--        </div>--}}

        {{-- Область тестирования --}}
        <div class="container">
            <div class="row module-wrapper">
                <div class="col-md-8">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/front/js/front.js') }}"></script>
<!-- Page file / URL scripts -->
@stack('scripts')
<!-- Page manual scripts -->
@stack('scripts.injection')

</body>
</html>
