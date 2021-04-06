@extends('front.layouts.layout')

@push('title') - Тест &laquo;{{ $test->name }}&raquo;@endpush

@section('content')
    <form role="form" method="post" action="{{ route('player.step') }}"
          enctype="multipart/form-data">
        @csrf
        <h1>Hello, world!</h1>
        <p>Здесь описание теста или напутствие тестируемому...</p>
        <button type="submit" class="btn btn-primary btn-lg">Начать тест</button>
    </form>
@endsection
