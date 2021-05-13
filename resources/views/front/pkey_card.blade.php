@extends('front.layouts.layout')

@push('title') - Тест &laquo;{{ $test->name }}&raquo;@endpush

@push('testname'){{ $test->name }}@endpush

@section('content')
    <form method="get" action="{{ route('player.pkey') }}">
        @csrf
        <input type="hidden" name="mkey" value="{{ $mkey }}">
        <div class="form-group">
            <label for="pkey">Введите персональный ключ для начала тестирования</label>
            <input type="text" name="pkey" id="pkey"
                   class="form-control col-lg-4 col-md-4 @error('pkey') is-invalid @enderror mt-2"
            >
        </div>
        <button type="submit" class="btn btn-primary btn-lg mt-2">Проверить персональный ключ</button>
    </form>

@endsection
