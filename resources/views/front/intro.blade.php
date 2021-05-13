@extends('front.layouts.layout')

@push('title') - Тест &laquo;{{ $test->name }}&raquo;@endpush

@push('testname'){{ $test->name }}@endpush

@section('content')
    <form role="form" method="get"
          action="{{ route('player.card', ['mkey' => $test->contract->mkey]) }}">
        @csrf
        <input type="hidden" name="nextblock" value="card">
        <p>Здесь описание теста или напутствие тестируемому...</p>
        <button type="submit" class="btn btn-primary btn-lg">
            @if($test->options & \App\Models\Test::AUTH_GUEST)
                Начать тест
            @elseif($test->options & \App\Models\Test::AUTH_FULL)
                Перейти к анкете
            @elseif($test->options & \App\Models\Test::AUTH_PKEY)
                Ввести персональный ключ
            @endif
        </button>
    </form>
@endsection
