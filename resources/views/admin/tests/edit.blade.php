@extends('admin.layouts.layout')

@push('title') - Новый тест@endpush

@section('back')
    <form action="{{ route('tests.back') }}" method="post">
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
                                    <label for="kind">Статус теста</label>
                                    <select name="kind" id="kind" class="select2 form-control col-lg-6 col-xs-12"
                                            data-placeholder="Выбор статуса теста" @if($show) disabled @endif>
                                        @foreach(App\Models\Test::types as $key => $value)
                                            <option value="{{ $key }}"
                                                    @if($key == $test->type) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt-4 mb-4" id="contract-div">
                                    <label for="contract">Контракт теста</label>
                                    <select name="contract" id="contract" class="select2 col-lg-6 col-xs-12"
                                            data-placeholder="Выбор контракта" @if($show) disabled @endif>
                                        @foreach($contracts as $contract)
                                            <option value="{{ $contract->id }}"
                                                    @if($contract->id == $test->contract_id) selected @endif>
                                                {{ $contract->number }} ({{ $contract->client->name }})
                                            </option>
                                        @endforeach
                                    </select>

{{--                                    <label for="mkey">Мастер-ключ</label>--}}
{{--                                    <input type="text" id="mkey" name="mkey" class="form-control col-lg-6 col-xs-12" disabled--}}
{{--                                           value="{{ $test->contract->mkey }}">--}}
                                </div>
                                <div class="form-group mt-2">
                                    <label for="auth">Анкетирование в начале теста</label>
                                    @php
                                        $auth_config = [
                                            \App\Models\Test::AUTH_GUEST => 'Нет анкеты, только запрос разрешений',
                                            \App\Models\Test::AUTH_FULL => 'Полная анкета, максимум информации о респонденте',
                                            \App\Models\Test::AUTH_PKEY => 'Анкета не применима, запрашивается персональный ключ лицензии'
                                        ];
                                    @endphp
                                    <select name="auth" id="auth" class="select2 form-control"
                                            @if($show) disabled @endif>
                                        @foreach($auth_config as $key => $value)
                                            <option value="{{ $key }}"
                                                    @if(intval($test->options) & intval($key)) selected @endif>
                                                {{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @php
                                    $steps = [
                                        ['name' => 'Конструктор анкеты', 'pane' => 'ac-respondent', 'view' => 'admin.tests.accordion.panel1'],
                                        ['name' => 'Механика и набор вопросов', 'pane' => 'ac-mechanics', 'view' => 'admin.tests.accordion.panel2'],
                                        ['name' => 'Финал теста для респондента', 'pane' => 'ac-final-respondent', 'view' => 'admin.tests.accordion.panel3'],
                                        ['name' => 'Финал теста для клиента', 'pane' => 'ac-final-client', 'view' => 'admin.tests.accordion.panel4'],
                                    ];
                                @endphp
                                <label for="accordionTest">Настройки</label>
                                <div class="accordion" id="accordionTest">
                                    @php
                                        $stepNo = 0;
                                    @endphp
                                    @foreach($steps as $step)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="{{ $step['pane'] }}-header">
                                                <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#{{ $step['pane'] }}-body" aria-expanded="true"
                                                        aria-controls="{{ $step['pane'] }}-body">
                                                    {{ $step['name'] }}
                                                </button>
                                            </h2>
                                            <div id="{{ $step['pane'] }}-body"
                                                 class="accordion-collapse collapse"
                                                 aria-labelledby="{{ $step['pane'] }}-header"
                                                 data-bs-parent="#accordionTest">
                                                <div class="accordion-body">
                                                    @if($step['view'])
                                                        @include($step['view'])
                                                    @else
                                                        В разработке...
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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
        let accordion = document.getElementById('accordionTest')
        accordion.addEventListener('shown.bs.collapse', (event) => {
            let activePanel = event.target.id;
            switch (activePanel) {
                case 'ac-respondent-body':
                    let auth_mode = document.getElementById('auth').value;
                    switch (parseInt(auth_mode)) {
                        case {{ \App\Models\Test::AUTH_FULL }}:
                            $('#auth-guest').hide();
                            $('#auth-pkey').hide();
                            $('#auth-full').show();
                            break;
                        case {{ \App\Models\Test::AUTH_PKEY }}:
                            $('#auth-guest').hide();
                            $('#auth-pkey').show();
                            $('#auth-full').hide();
                            break;
                        case {{ \App\Models\Test::AUTH_GUEST }}:
                            $('#auth-guest').show();
                            $('#auth-pkey').hide();
                            $('#auth-full').hide();
                            break;
                    }
                    break;
                case 'ac-mechanics-body':
                    $('#mechanics1').change();
                    break;
                default:
                    break;
            }
        });

        $("input[name='mechanics']").on("change", () => {
            let options =
                (document.getElementById('mechanics1').checked ? {{ \App\Models\Test::IMAGES2 }} : 0) |
                (document.getElementById('mechanics2').checked ? {{ \App\Models\Test::IMAGES4 }} : 0);// |
            {{--(document.getElementById('aux_mechanics1').checked ? {{ \App\Models\Test::EYE_TRACKING }} : 0) |--}}
            {{--(document.getElementById('aux_mechanics2').checked ? {{ \App\Models\Test::MOUSE_TRACKING }} : 0) |--}}
            {{--(document.getElementById('aux_mechanics3').checked ? {{ \App\Models\Test::EQUIPMENT_MONITOR }} : 0);--}}
            $.post({
                url: "{{ route('sets.filterbytest') }}",
                data: {
                    options: options.toString(),
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: (data) => {
                    let sets = JSON.parse(data);
                    if (sets.length === 0) {
                        $('#no-sets').show();
                        $('#sets').hide();
                    } else {
                        $('#no-sets').hide();
                        let first = true;
                        let html = '';
                        $.each(sets, function (key, value) {
                            html = html + "<option " + (first ? "selected " : "") + "value=\"" + key + "\">" + value + "</option>";
                            if (first) first = false;
                        });
                        $('#sets').html(html);
                        $('#sets').show();
                    }
                }
            });
        });

        $(function () {
            $('#mechanics1').change();

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

