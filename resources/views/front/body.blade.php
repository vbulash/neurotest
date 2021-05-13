@extends('front.layouts.layout')

@push('title') - Тест &laquo;{{ $testname }}&raquo;@endpush

@push('testname'){{ $testname }}@endpush

@section('content')
    <form method="get" action="#" enctype="multipart/form-data">
        @csrf
        @php
            if(!isset($step)) return;

            $rows = 1;
            $columns = 2;
            if($step->set->quantity == \App\Models\QuestionSet::IMAGES2) {
                $rows = 1;
                $columns = 2;
            } elseif($step->set->quantity == \App\Models\QuestionSet::IMAGES4) {
                $rows = 2;
                $columns = 2;
            }
            $grid = 3;
            $imageNo = 0;

            $letters = ['A', 'B', 'C', 'D'];
            $labels = ['А', 'Б', 'В', 'Г'];
            $qa = $step->toArray();
        @endphp
        @for ($row = 0; $row < $rows; $row++)
            <div class="row mb-4">
                @for ($column = 0; $column < $columns; $column++)
                    <div class="col-lg-{{ $grid }} col-xs-12">
                        <div class="form-group">
                            <div>
                                <img id="image{{ $letters[$imageNo] }}"
                                     src="/uploads/{{ $qa['image' . $letters[$imageNo]] }}"
                                     alt=""
                                     class="step-image">
                            </div>
                        </div>
                    </div>
                    @php($imageNo++)
                @endfor
            </div>
        @endfor
        <button type="submit" style="display:none;" name="answer" id="answer" value="timeout"></button>
    </form>

@endsection

@push('scripts.injection')
    <script>
        $(function () {
            $('.step-image').on('click', () => {
                alert();
            });
        });
    </script>
@endpush
