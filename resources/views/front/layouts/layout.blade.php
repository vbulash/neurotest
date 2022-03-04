<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ env('APP_NAME') }}@stack('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/front/css/front.css') }}">
{{--    https://realfavicongenerator.net/--}}
    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- Page styles -->
    @stack('styles')
</head>
<body>
<div class="container-fluid main-header pl-4">
    @php
        $navstyle = '';
        $textstyle = '';

        $content = json_decode($test->content, true);
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
    <nav class="navbar navbar-dark bg-primary d-lg-flex" @if($branding) style="{{ $navstyle }}" @endif>
        <div class="navbar-brand" @if($branding) style="{{ $textstyle }}" @endif>
            {{--            <a href="{{ route('admin.index') }}">--}}
            <i class="fas fa-home"></i>
            {{ $branding ? $content['branding']['company-name'] : env('APP_NAME') }}
            {{--            </a>--}}
        </div>
        <div class="navbar-text" @if($branding) style="{{ $textstyle }}" @endif>
            <p>@stack('testname')</p>
            <div>
                @stack('step_description') <span class="step-countdown"></span>
            </div>
        </div>
    </nav>

    {{--    <div class="container-fluid mt-2">--}}
    {{-- Область отображения сообщений --}}
    <div class="row mt-2" style="margin-left: 0; margin-right: 0;">
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
<script>
    // Инициализация pusher'а
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "0",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    // Broadcast
    let pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
    });

    let channel = pusher.subscribe('neurotest-channel-{!! $sid !!}');
    channel.bind('toast-event', (data) => {
        toastr[data.type](data.message, data.title);
    });

    // Ошибки и сообщения
    @if (isset($errors) && $errors->any())
        @foreach ($errors->all() as $error)
            toastr['error']("{!! $error !!}");
        @endforeach
        @elseif(session()->has('error'))
        toastr['error']("{!! session('error') !!}");
        @php
          session()->forget('error');
        @endphp
    @endif

    @if (session()->has('success'))
        toastr['success']("{!! session('success') !!}");
        @php
            session()->forget('success');
        @endphp
    @endif

    @if (session()->has('info'))
        toastr['success']("{!! session('info') !!}");
        @php
            session()->forget('info');
        @endphp
    @endif
</script>
<!-- Page file / URL scripts -->
@stack('scripts')
<!-- Page manual scripts -->
@stack('scripts.injection')

</body>
</html>
