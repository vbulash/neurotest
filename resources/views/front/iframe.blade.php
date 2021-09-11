@extends('front.layouts.layout')

@push('title') - Тестрование IFrame @endpush

@section('content')
    <h1 class="mb-5">Проверка IFrame</h1>
    <!-- IFrame -->
    <iframe
        src="https://psycho.bulash.ru/player.play/mkey_60c45f1aeaece2.64555759*1160066438/test_610554455c4906.82439038"
        width="1000px"
        height="600px"
        allow="fullscreen">
    </iframe>
    <!-- /.IFrame -->
@endsection

@push('scripts.injection')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            //
        });
    </script>
@endpush
