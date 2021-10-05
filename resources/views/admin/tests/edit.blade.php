@extends('admin.layouts.layout')

@push('title') - Редактирование теста &laquo;{{ $test->name }}&raquo;@endpush

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
                    {{--                        <div class="card-header">--}}
                    {{--                            <h3 class="card-title"></h3>--}}
                    {{--                        </div>--}}
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
                                    <label for="key">Ключ теста</label>
                                    <input type="text" name="key" id="key"
                                           class="form-control @error('key') is-invalid @enderror"
                                           value="{{ $test->key }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="title">Наименование</label>
                                    <input type="text" name="title" id="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           value="{{ $test->name }}" @if($show) disabled @endif>
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

                                <div class="checkbox mb-2">
                                    <label>
                                        <input type="checkbox" id="paid" name="paid"
                                               @if($test->paid) checked @endif
                                        >
                                        Результат тестирования имеет платную расширенную версию
                                    </label>
                                </div>

                                @php
                                    $steps = [
                                        ['name' => 'Конструктор анкеты', 'pane' => 'ac-respondent', 'view' => 'admin.tests.accordion.panel1'],
                                        ['name' => 'Механика и набор вопросов', 'pane' => 'ac-mechanics', 'view' => 'admin.tests.accordion.panel2'],
                                        ['name' => 'Финал теста для респондента', 'pane' => 'ac-final-respondent', 'view' => 'admin.tests.accordion.panel3'],
                                        //['name' => 'Финал теста для клиента', 'pane' => 'ac-final-client', 'view' => 'admin.tests.accordion.panel4'],
                                        ['name' => 'Настраиваемый брендинг', 'pane' => 'ac-branding', 'view' => 'admin.tests.accordion.panel5'],
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

@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/admin/plugins/pickr/classic.min.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/admin/plugins/pickr/pickr.min.js') }}"></script>
    @endpush
@endonce

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
                case 'ac-branding-body':
                    $('#branding').change();
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
                        $('#sets').val('{{ $test->qset->id }}');
                        $('#sets').show();
                    }
                }
            });
        });



        @if(isset($content['branding']))
        let backgroundColor = '{{ $content['branding']['background'] }}';
        let fontColor = '{{ $content['branding']['fontcolor'] }}';
        @else
        let backgroundColor = '#007bff';
        let fontColor = '#ffffff';
        @endif

        let pickrOptions = {
            el: '',
            theme: 'classic',

            default: '',

            swatches: [
                'rgba(244, 67, 54, 1)',
                'rgba(233, 30, 99, 1)',
                'rgba(156, 39, 176, 1)',
                'rgba(103, 58, 183, 1)',
                'rgba(63, 81, 181, 1)',
                'rgba(33, 150, 243, 1)',
                'rgba(3, 169, 244, 1)',
                'rgba(0, 188, 212, 1)',
                'rgba(0, 150, 136, 1)',
                'rgba(76, 175, 80, 1)',
                'rgba(139, 195, 74, 1)',
                'rgba(205, 220, 57, 1)',
                'rgba(255, 235, 59, 1)',
                'rgba(255, 193, 7, 1)'
            ],

            i18n: {
                'btn:save': 'Сохранить',
                'btn:cancel': 'Отменить',
                'btn:clear': 'Очистить',
            },

            components: {
                preview: true,
                opacity: false,
                hue: false,

                interaction: {
                    hex: true,
                    rgba: false,
                    hsla: false,
                    hsva: false,
                    cmyk: false,
                    input: true,
                    clear: true,
                    save: true
                }
            }
        };

        let backPickrOptions = pickrOptions;
        backPickrOptions.el = '#back-picker';
        backPickrOptions.default = backgroundColor;

        const backgroundColorPickr = Pickr.create(backPickrOptions);

        backgroundColorPickr
            .on('save', instance => {
                let selectedColor = instance.toHEXA().toString();
                $('.custom-color').attr('style', 'color: ' + fontColor + ' !important');
                $('#preview_nav').attr('style', 'background-color: ' + selectedColor + ' !important');
                $('#preview_button').attr('style',
                    'background-color: ' + selectedColor + ';' +
                    'border-color: ' + selectedColor + ';' +
                    'color: ' + fontColor + ' !important'
                );

                $('#background-input').val(selectedColor);
            });

        let fontPickrOptions = pickrOptions;
        fontPickrOptions.el = '#font-picker';
        fontPickrOptions.default = fontColor;

        const fontColorPickr = Pickr.create(fontPickrOptions);
        fontColorPickr
            .on('save', instance => {
                let selectedColor = instance.toHEXA().toString();
                $('.custom-color').attr('style', 'color: ' + selectedColor + ' !important');
                $('#preview_nav').attr('style', 'background-color: ' + backgroundColor + ' !important');
                $('#preview_button').attr('style',
                    'background-color: ' + backgroundColor + ';' +
                    'border-color: ' + backgroundColor + ';' +
                    'color: ' + selectedColor + ' !important'
                );

                $('#font-color-input').val(selectedColor);
            });

        $('#company-name-changer').on('input change', (event) => {
            $('#company-name-demo').html(event.target.value);
        });

        $("input[name='branding']").on("change", () => {
            let custom = document.getElementById('branding');
            let branding_panel = document.getElementById('branding_panel');
            if (custom.checked) {
                branding_panel.style.display = "block";
                $('#company-name-changer').change();
                backgroundColorPickr.applyColor(false);
                fontColorPickr.applyColor(false);
            } else {
                branding_panel.style.display = "none";
            }
        });

        $(function () {
            $('#mechanics1').change();

            $("#submit").on("click", () => {
            });
        });
    </script>
@endpush

