@extends('front.layouts.layout')

@push('title') - Предварительные проверки вход в тестирование@endpush

@push('testname') Тест &laquo;{{ $test->name }}&raquo;@endpush

@section('content')
    <p>Обнаружены ошибки проверки, обратитесь к администратору теста</p>
@endsection
