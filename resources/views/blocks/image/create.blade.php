@extends('admin.layouts.layout')

@push('title') - Новый блок описания ФМП@endpush

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
                    <h1>Новый блок описания ФМП</h1>
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
                            <h3 class="card-title">Блок с изображением</h3>
                        </div>
                        <!-- /.card-header -->

                        <form role="form" method="post" action="{{ route('blocks.store') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="kind" name="kind" value="{{ \App\Models\Block::TYPE_IMAGE }}">
                            <div class="card-body">
                                <div class="form-group col-lg-3 col-xs-6">
                                    <label for="code">Код нейропрофиля</label>
                                    <input type="text" name="code" id="code"
                                           class="form-control @error('code') is-invalid @enderror">
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="description">Краткое наименование</label>
                                        <input type="text" name="description" id="description"
                                               class="form-control @error('description') is-invalid @enderror">
                                    </div>
                                    <div class="col-lg-3 col-xs-12">
                                        <label
                                            for="image">Изображение</label>
                                        <input type="file" id="image" name="image"
                                               accept="image/*"
                                               class="image-file mb-4 form-control"
                                               onchange="readImage(this)">
                                        <div>
                                            <img id="preview_image"
                                                 src="" alt=""
                                                 class="image-preview">
                                            <a href="javascript:void(0)"
                                               id="clear_image"
                                               data-preview="preview_image"
                                               class="btn btn-primary mb-3 image-clear"
                                               style="display:none;">Очистить</a>
                                        </div>
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
                    $('#preview_image').attr('src', event.target.result);
                    $('#preview_image').show();
                    //$('#clear_image').show();
                    //$('#content').val(event.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(function () {
            $('#preview_image').hide();

            $('#clear_image').on("click", (event) => {
                $('#image').val('');
                $('#preview_image').attr('src', '');
                $('#preview_image').hide();
                //$('#content').val('');
                $('#clear_image').hide();
            });
        });
    </script>
@endpush
