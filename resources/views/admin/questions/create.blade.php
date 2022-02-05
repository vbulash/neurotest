@extends('admin.layouts.layout')

@section('back')
    <form action="{{ route('questions.back', ['sid' => $sid]) }}" method="post">
        @csrf
        <button type="submit" id="back_btn" name="back_btn" class="btn btn-primary">
            <i class="fas fa-chevron-left"></i> Назад
        </button>
    </form>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Новый вопрос для набора &laquo;{{ $set->name }}&raquo;</h1>
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
                    {{--                        <div class="card-header">--}}
                    {{--                            <h3 class="card-title">&nbsp;</h3>--}}
                    {{--                        </div>--}}
                    <!-- /.card-header -->

                        <form role="form" method="post" action="{{ route('questions.store', ['sid' => $sid]) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="questionset_id" name="questionset_id" value="{{ $set->id }}">
                            <input type="hidden" id="sort_no" name="sort_no" value="0">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="checkbox col-4 mb-2">
                                        <label>
                                            <input type="checkbox" id="learning" name="learning"> Учебный режим
                                            прохождения вопроса
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="timeout">Таймаут вопроса в секундах (0 - вопрос не ограничен по
                                        времени)</label>
                                    <input type="number" name="timeout" id="timeout" min="0"
                                           class="form-control col-lg-6 col-xs-12"
                                           value="0"
                                    >
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
                                            $grid = 3;
                                            $imageNo = 0;

                                            $letters = ['A', 'B', 'C', 'D'];
                                            $labels = ['А', 'Б', 'В', 'Г'];
                                            $placeholder = "https://via.placeholder.com/300x300.png?text=Пусто";
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
                                                                   onchange="readImage(this)">
                                                            <div>
                                                                <a href="javascript:void(0)" class="preview_anchor"
                                                                   data-toggle="lightbox"
                                                                   data-title="Изображение { $labels[$imageNo] }}">
                                                                    <img id="preview_image{{ $letters[$imageNo] }}"
                                                                         src="{{ $placeholder }}" alt=""
                                                                         class="image-preview">
                                                                </a>
                                                                <a href="javascript:void(0)"
                                                                   id="clear_image{{ $letters[$imageNo] }}"
                                                                   data-preview="preview_image{{ $letters[$imageNo] }}"
                                                                   class="btn btn-primary mb-3 image-clear">Очистить</a>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="weight{{ $letters[$imageNo] }}">Ключ
                                                                изображения {{ $labels[$imageNo] }}</label>
                                                            <select name="value{{ $letters[$imageNo] }}"
                                                                    id="value{{ $letters[$imageNo] }}"
                                                                    class="select2 form-control image-weight col-6"
                                                                    data-placeholder="Выбор ключа изображения {{ $labels[$imageNo] }}">
                                                                @foreach(App\Models\Question::$values as $value)
                                                                    <option value="{{ $value }}" @if($loop->first) selected @endif >{!! $value !!}</option>
                                                                @endforeach
                                                            </select>
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
                                <button type="submit" class="btn btn-primary">Сохранить</button>
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

        $(function () {
            // $('.image-file').on("change", (event) => {
            //     debugger;
            //     let target = event.currentTarget.name;
            //     $('#preview_' + target).attr('src', $('#' + target).val());
            // });
            $('.image-clear').hide();

            $('.image-clear').on("click", (event) => {
                let target = event.currentTarget.id;
                let preview = $('#' + target).data('preview');
                $('#' + preview).attr('src', "{{ $placeholder }}");
                $('#' + target).hide();
            });
        });
    </script>
@endpush
