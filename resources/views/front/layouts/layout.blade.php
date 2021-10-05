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
    @php
        $navstyle = '';
        $textstyle = '';

        $content = $content = json_decode($test->content, true);
        $branding = isset($content['branding']);
        session()->put('branding', $branding);

        if($branding) {
            $navstyle = sprintf("background-color: %s!important; color: %s !important",
                $content['branding']['background'],  $content['branding']['fontcolor']);
            $textstyle = sprintf("color: %s !important", $content['branding']['fontcolor']);
            $buttonStyle = sprintf("background-color: %s!important; color: %s !important; border-color: %s !important",
                $content['branding']['background'],  $content['branding']['fontcolor'], $content['branding']['background']);

            session()->put('navstyle', $navstyle);
            session()->put('textstyle', $textstyle);
            session()->put('buttonstyle', $buttonStyle);
        }
    @endphp
    <nav class="navbar navbar-dark bg-primary d-none d-lg-flex" @if($branding) style="{{ $navstyle }}" @endif>
        <div class="navbar-brand" @if($branding) style="{{ $textstyle }}" @endif>
            {{--            <a href="{{ route('admin.index') }}">--}}
            <i class="fas fa-home"></i>
            {{ env('APP_NAME') }}
            {{--            </a>--}}
        </div>
        <div class="navbar-text" @if($branding) style="{{ $textstyle }}" @endif>
            <p>@stack('testname')</p>
            <div>
                @stack('step_description') <span class="step-countdown"></span>
            </div>
        </div>
    </nav>
    <div class="d-block d-lg-none" @if($branding) style="{{ $navstyle }}" @endif>
        <div class="d-block p-1 bg-primary text-white" @if($branding) style="{{ $navstyle }}" @endif>
            <i class="fas fa-home"></i>
            {{ env('APP_NAME') }}
        </div>
        <div class="d-block p-1 bg-primary text-white" @if($branding) style="{{ $navstyle }}" @endif>
            @stack('testname')
        </div>
        <div class="d-block p-1 bg-primary text-white" @if($branding) {{ $navstyle }} @endif>
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
