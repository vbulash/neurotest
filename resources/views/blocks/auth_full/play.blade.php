@extends('front.layouts.layout')

@push('title') - Тест &laquo;{{ $test->name }}&raquo;@endpush

@section('content')
    <form role="form" method="post" action="{{ route('player.step') }}"
          enctype="multipart/form-data">
        @csrf
        <p>Это первый модуль, анкетный...</p>
{{--        <button type="submit" class="btn btn-primary btn-lg">Начать тест</button>--}}
    </form>
@endsection
