@extends('front.layouts.layout')

@push('title') - Тест &laquo;{{ $test->name }}&raquo;@endpush

@push('testname') Тест &laquo;{{ $test->name }}&raquo;@endpush

@section('content')
    <form role="form" method="get"
          action="{{ route('player.card', ['sid' => $sid]) }}">
        @csrf
        <input type="hidden" name="sid" value="{{ $sid }}">
        <input type="hidden" name="nextblock" value="card">
        <p>В нейротесте нет правильных и неправильных ответов.<br/>
            Каждый ответ правильный!<br/>
            <strong>Время на вопрос - 5 секунд.</strong><br/>
            Выберите изображение, которое привлеко Ваше внимание первым!</p>

		@php($branding = session('branding'))

        <button type="submit" @if(isset($branding)) class="btn btn-lg" style="{{ $branding['buttonstyle'] }}" @else class="btn btn-primary btn-lg" @endif>
            @if($test->options & \App\Models\Test::AUTH_GUEST)
                Начать тест
            @elseif($test->options & \App\Models\Test::AUTH_FULL)
                Перейти к анкете
            @elseif($test->options & \App\Models\Test::AUTH_PKEY)
                Ввести персональный ключ
			@elseif($test->options & \App\Models\Test::AUTH_MIX)
				Перейти к анкете и ввести персональный ключ
            @endif
        </button>
    </form>
@endsection
