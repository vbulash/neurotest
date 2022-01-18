@extends('admin.layouts.layout')

@push('title') - Новый блок описания ФМП@endpush

@section('back')
    <form action="{{ route('blocks.back', ['sid' => $sid]) }}" method="post">
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
                            <h3 class="card-title">Блок с видео</h3>
                        </div>
                        <!-- /.card-header -->

                        <form role="form" method="post" action="{{ route('blocks.store', ['sid' => $sid]) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="kind" name="kind" value="{{ \App\Models\Block::TYPE_VIDEO }}">
                            <div class="card-body">
                                @include('blocks.partials.create')

                                <div class="form-group">
                                    <div class="col-lg-3 col-xs-12">
                                        <label
                                            for="clip">Видео</label>
                                        <input type="file" id="clip" name="clip"
                                               accept="video/*"
                                               class="video-file mb-4 form-control"
                                               onchange="readMedia(this)">
                                        <div>
                                            <video controls id="video-tag" style="width:100%;display:none;">
                                                <source id="preview_clip" src="">
                                                Ваш интернет-браузер не поддерживает видео
                                            </video>
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
        function readMedia(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (event) {
                    $('#preview_clip').attr('src', event.target.result);
                    $('#video-tag').show();
                    $('#preview_clip').show();
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(function () {
            $('#video-tag').hide();
        });
    </script>
@endpush
