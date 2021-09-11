@extends('admin.layouts.layout')

@push('title') - Новый тест@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Новый тест</h1>
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
                            <h3 class="card-title">Создание и изменение модулей теста станут доступны после сохранения нового теста</h3>
                        </div>
                        <!-- /.card-header -->

                        <form role="form" method="post" action="{{ route('tests.store') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Наименование</label>
                                    <input type="text" name="title" id="title"
                                           class="form-control @error('title') is-invalid @enderror">
                                </div>

                                <div class="form-group" id="contract-div">
                                    <label>Выберите контракт для теста</label>
                                    <select name="contract" id="contract" class="select2 col-lg-6 col-xs-12"
                                            data-placeholder="Выбор контракта">
                                        @foreach($contracts as $contract)
                                            <option value="{{ $contract->id }}">{{ $contract->number }} ({{ $contract->client->name }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mt-4"><h4>Настройки</h4></div>

                                <div class="form-group mt-2">
                                    <label>Способ идентификации пользователя в начале теста</label>
                                    <select name="auth" id="auth" class="select2 form-control col-lg-6 col-xs-12">
                                        <option value="{{ \App\Models\Test::AUTH_GUEST }}" selected>Анонимное прохождение теста (только запрос разрешений)</option>
                                        <option value="{{ \App\Models\Test::AUTH_FULL }}">Полная идентификация (максимум информации о пользователе)</option>
                                        <option value="{{ \App\Models\Test::AUTH_PKEY }}">Идентификация по персональному ключу лицензии</option>
                                    </select>
                                </div>

                                <div class="form-group mt-2">
                                    <label>Способ предоставления результатов теста респонденту</label>
                                    <select name="result" id="result" class="select2 form-control col-lg-6 col-xs-12">
                                        <option value="{{ \App\Models\Test::SHOW_RESULTS }}">Показать результат тестирования на экране</option>
                                        <option value="{{ \App\Models\Test::MAIL_RESULTS }}">Переслать результат тестирования по электронной почте</option>
                                    </select>
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="submit">Сохранить</button>
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
            $("#submit").on("click", () => {
            });
        });
    </script>
@endpush
