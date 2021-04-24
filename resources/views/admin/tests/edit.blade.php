@extends('admin.layouts.layout')

@push('title') - Новый тест@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@if($show) Анкета @else Редактирование @endif теста &laquo;{{ $test->name }}&raquo;</h1>
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
                            <h3 class="card-title"></h3>
                        </div>
                        <!-- /.card-header -->

                        <form role="form" method="post"
                              @if($show)
                              action=""
                              @else
                              action="{{ route('tests.update', ['test' => $test->id]) }}"
                              @endif
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Наименование</label>
                                    <input type="text" name="title" id="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           value="{{ $test->name }}" @if($show) disabled @endif>
                                </div>

                                <div class="form-group">
                                    <label>Тип теста</label>
                                    <select name="kind" id="kind" class="select2 form-control col-lg-6 col-xs-12"
                                            data-placeholder="Выбор типа теста" @if($show) disabled @endif>
                                        @foreach(App\Models\Test::types as $key => $value)
                                            <option value="{{ $key }}"
                                                    @if($key == $test->type) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" id="contract-div">
                                    <label>Контракт теста</label>
                                    <select name="contract" id="contract" class="select2 col-lg-6 col-xs-12"
                                            data-placeholder="Выбор контракта" disabled>
                                        @foreach($contracts as $contract)
                                            <option value="{{ $contract->id }}"
                                                    @if($contract->id == $test->contract_id) selected @endif>
                                                {{ $contract->number }} ({{ $contract->client->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mt-5"><h4>Настройки</h4></div>

                                <div class="form-group mt-2">
                                    <label for="auth">Способ идентификации пользователя в начале теста</label>
                                    <select name="auth" id="auth" class="select2 form-control col-lg-6 col-xs-12" disabled>
                                        @php
                                            $auth = [
                                                App\Models\Test::AUTH_GUEST => 'Анонимное прохождение теста',
                                                App\Models\Test::AUTH_FULL => 'Полная идентификация (максимум информации о пользователе)',
                                                App\Models\Test::AUTH_PKEY => 'Идентификация по персональному ключу лицензии'
                                            ];
                                        @endphp
                                        @foreach($auth as $key => $value)
                                            <option value="{{ $key }}"
                                                    @if($key & $test->options) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mt-2">
                                    <label for="result">Способ предоставления результатов теста респонденту</label>
                                    <select name="result" id="result" class="select2 form-control col-lg-6 col-xs-12" disabled>
                                        @php
                                            $results = [
                                                \App\Models\Test::SHOW_RESULTS=> 'Показать результат тестирования на экране',
                                                \App\Models\Test::MAIL_RESULTS => 'Переслать результат тестирования по электронной почте'
                                            ];
                                        @endphp
                                        @foreach($results as $key => $value)
                                            <option value="{{ $key }}"
                                                    @if($key & $test->options) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            @if(!$show)
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" id="submit">Сохранить</button>
                                </div>
                            @endif
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
            $("#kind").on("change", (event) => {
                if ($('#kind').val() == "{{ \App\Models\Test::TYPE_TEMPLATE }}") {
                    $('#contract-div').hide();
                } else {
                    $('#contract-div').show();
                }
            });
            $("#submit").on("click", () => {
                if ($('#kind').val() == "{{ \App\Models\Test::TYPE_TEMPLATE }}") {
                    // Preprocessor for template before server
                }
            });
        });
    </script>
@endpush

