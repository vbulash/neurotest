@extends('admin.layouts.layout')

@push('title') - @if($show) Карточка @else Редактирование @endif блока описания ФМП № {{ $block->id }}@endpush

@section('back')
    <form action="{{ route('blocks.back') }}" method="post">
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
                    <h1>@if($show) Карточка @else Редактирование @endif блока описания ФМП № {{ $block->id }}</h1>
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
                            <h3 class="card-title">Блок с видео</h3>
                        </div>
                        <!-- /.card-header -->

                        <form role="form" method="post" name="blocks-edit" id="blocks-edit"
                              action="{{ route('blocks.update', ['block' => $block->id]) }}"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                @include('blocks.partials.edit')

                                <div class="form-group">
                                    <div class="col-lg-3 col-xs-12">
                                        <label
                                            for="clip">Видео</label>
                                        @if(!$show)
                                            <input type="file" id="cip" name="clip"
                                                   accept="video/*"
                                                   class="video-file mb-4 form-control"
                                                   onchange="readMedia(this)">
                                        @endif
                                        <div>
                                            <video controls id="video-tag" style="width:100%;">
                                                <source id="preview_clip" src="/uploads/{{ $block->content }}">
                                                Ваш интернет-браузер не поддерживает видео
                                            </video>
                                        </div>
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
        function readMedia(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (event) {
                    $('#preview_clip').attr('src', event.target.result);
                    $('#preview_clip').show();
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(function () {
            $('#clear_image').on("click", (event) => {
                $('#image').attr('src', '');
                $('#preview_image').attr('src', '');
                $('#preview_image').hide();
                // $('#clear_image').hide();
            });
        });
    </script>
@endpush
