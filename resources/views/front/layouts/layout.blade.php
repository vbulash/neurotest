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
    <nav class="navbar navbar-dark bg-primary">
        <div class="navbar-brand ml-4">{{ env('APP_NAME') }}</div>
        <div class="navbar-text">@stack('testname')</div>
    </nav>

    {{--    <div class="container-fluid mt-2">--}}
    {{-- Область отображения сообщений --}}
    <div class="row mt-2">
        <div class="col-12">
            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {!! session('error') !!}
                </div>
                @php(session()->forget('error'))
            @elseif ($errors->any())
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
        {{--        </div>--}}

        {{-- Область тестирования --}}
        <div class="container">
            <div class="row module-wrapper">
                <div class="offset-lg-2 col-lg-8">
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
