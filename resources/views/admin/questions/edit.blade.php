@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@if($show) Карточка @else Редактирование @endif вопроса № {{ $question->sort_no }} для набора
                        &laquo;{{ $set->name }}&raquo;</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Blank Page</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">&nbsp;</h3>
                        </div>
                        <!-- /.card-header -->

                        <form role="form" method="post" action="{{ route('questions.update', ['question' => $question->id]) }}"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="questionset_id" name="questionset_id" value="{{ $set->id }}">
                            <div class="card-body">
                                <div class="form-group col-lg-3 col-xs-12">
                                    <label for="sort_no">Номер по порядку</label>
                                    <input type="text" name="sort_no" id="sort_no"
                                           class="form-control"
                                           value="{{ $question->sort_no }}" disabled>
                                </div>
                                <div class="form-group">
                                    <div class="container-fluid">
                                        <label class="mb-4">Изображения вопроса</label>
                                        @php
                                            if(!isset($set)) return;

                                            $rows = 1;
                                            $columns = 2;
                                            if($set->quantity == \App\Models\QuestionSet::IMAGES2) {
                                                $rows = 1;
                                                $columns = 2;
                                            } elseif($set->quantity == \App\Models\QuestionSet::IMAGES4) {
                                                $rows = 2;
                                                $columns = 2;
                                            }
                                            $grid = 12 / $columns;
                                            $imageNo = 0;

                                            $letters = ['A', 'B', 'C', 'D'];
                                            $labels = ['А', 'Б', 'В', 'Г'];
                                            $qa = $question->toArray();
                                        @endphp
                                        @for ($row = 0; $row < $rows; $row++)
                                            <div class="row mb-4">
                                                @for ($column = 0; $column < $columns; $column++)
                                                    <div class="col-lg-{{ $grid }} col-xs-12">
                                                        <div class="form-group">
                                                            <label
                                                                for="image{{ $letters[$imageNo] }}">Изображение {{ $labels[$imageNo] }}</label>
                                                            <input type="file" id="image{{ $letters[$imageNo] }}"
                                                                   name="image{{ $letters[$imageNo] }}"
                                                                   class="image-file mb-4 form-control"
                                                                   @if($show) disabled @endif
                                                                   onchange="readImage(this)">
                                                            <div>
                                                                <img id="preview_image{{ $letters[$imageNo] }}"
                                                                     src="/uploads/{{ $qa['image' . $letters[$imageNo]] }}"
                                                                     alt=""
                                                                     class="image-preview">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php($imageNo++)
                                                @endfor
                                            </div>
                                        @endfor
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                @if(!$show)
                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                @endif
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@push('scripts.injection')
    <script>
        function readImage(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (event) {
                    $('#preview_' + input.id).attr('src', event.target.result);
                    $('#clear_' + input.id).show()
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
