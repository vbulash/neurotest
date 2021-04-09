@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Новый набор вопросов</h1>
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
                            <h3 class="card-title">
                                Создание вопросов станет доступным после сохранения набора вопросов
                            </h3>
                        </div>
                        <!-- /.card-header -->

                        <form role="form" method="post" action="{{ route('sets.store') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Наименование</label>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror" id="name">
                                </div>
                                <div class="form-group col-lg-6 col-xs-12">
                                    <label for="quantity">Состав каждого вопроса</label>
                                    <select name="quantity" id="quantity" class="select2 col-lg-6 col-xs-12"
                                            style="width: 100%;">
                                        <option value="{{ \App\Models\QuestionSet::IMAGES2 }}">2 изображения</option>
                                        <option value="{{ \App\Models\QuestionSet::IMAGES4 }}">4 изображения</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-xs-12">
                                    <label for="type">Тип набора вопросов</label>
                                    <select name="type" id="type" class="select2"
                                            style="width: 100%;">
                                        @foreach(\App\Models\QuestionSet::types as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-xs-12" id="contract-div">
                                    <label for="client_id">Клиент для набора вопросов (имеет смысл только для типа &laquo;Исключительный&raquo;)</label>
                                    <select name="client_id" id="client_id" class="select2"
                                            data-placeholder="Выбор клиента"
                                            style="width: 100%;">
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mt-4"><h5>Настройки дополнительных методов тестирования</h5></div>
                                <div class="checkbox col-4 mb-2">
                                    <label>
                                        <input type="checkbox" id="eye-tracking" name="eye-tracking"> Eye-tracking
                                    </label>
                                </div>
                                <div class="checkbox col-4">
                                    <label>
                                        <input type="checkbox" id="mouse-tracking" name="mouse-tracking"> Mouse-tracking
                                    </label>
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
        $(function () {
            $('#contract-div').hide();

            $("#type").on("change", (event) => {
                if ($('#type').val() == "{{ \App\Models\QuestionSet::TYPE_EXACT }}") {
                    $('#contract-div').show();
                } else {
                    $('#contract-div').hide();
                }
            });
        });
    </script>
@endpush
