@php
    $placeholder = "https://via.placeholder.com/300x300.png?text=Пусто";
@endphp

<!-- Modal -->
<div class="modal fade" id="tests-create" tabindex="-1" aria-labelledby="tests-create-label" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" method="post" action="{{ route('tests.store', ['sid' => $sid]) }}"
                  enctype="multipart/form-data"
                  id="wizard"
            >
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tests-create-label">Новый тест</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @php
                    $steps = [
                        ['name' => 'Основная информация', 'tab' => 'vp-essentials', 'view' => 'admin.tests.wizard.step1','role' => 'start'],
                        ['name' => 'Конструктор анкеты', 'tab' => 'vp-respondent', 'view' => 'admin.tests.wizard.step2'],
                        ['name' => 'Механика и набор вопросов', 'tab' => 'vp-mechanics', 'view' => 'admin.tests.wizard.step3'],
                        ['name' => 'Финал теста для респондента', 'tab' => 'vp-final-respondent', 'view' => 'admin.tests.wizard.step4'],
                        //['name' => 'Финал теста для клиента', 'tab' => 'vp-final-client', 'view' => 'admin.tests.wizard.step5'],
                        ['name' => 'Настраиваемый брендинг', 'tab' => 'vp-branding', 'view' => 'admin.tests.wizard.step6'],
                        ['name' => 'Настраиваемая оплата', 'tab' => 'vp-robokassa', 'view' => 'admin.tests.wizard.step7', 'role' => 'finish'],
                    ];
                @endphp
                <div class="modal-body">
                    <div class="d-flex align-items-start">
                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist"
                             aria-orientation="vertical">
                            @php
                                $stepNo = 0;
                            @endphp
                            @foreach($steps as $step)
                                <a class="nav-link"
                                   id="{{ $step['tab'] }}-tab" data-bs-toggle="pill"
                                   data-bs-target="#{{ $step['tab'] }}" type="link" role="tab"
                                   @if(!$loop->first)
                                   disabled
                                   @endif
                                   @if(key_exists('role', $step))
                                   data-role="{{ $step['role'] }}"
                                   @else
                                   data-role="body"
                                   @endif
                                   data-id="{{ $stepNo }}"
                                   aria-controls="{{ $step['tab'] }}"
                                   aria-selected="true">
                                    {{ $stepNo + 1 }}. {{ $step['name'] }}
                                </a>
                                @php($stepNo++)
                            @endforeach
                        </div>
                        <div class="tab-content container-lg" id="v-pills-tabContent">
                            @foreach($steps as $step)
                                <div class="tab-pane fade @if($loop->first) show active @endif" id="{{ $step['tab'] }}"
                                     role="tabpanel"
                                     aria-labelledby="{{ $step['tab'] }}-tab"
                                     @if(key_exists('role', $step))
                                     data-role="{{ $step['role'] }}"
                                     @else
                                     data-role="body"
                                    @endif
                                >
                                    @if($step['view'])
                                        @include($step['view'])
                                    @else
                                        В разработке...
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <div>
                        <button type="submit" id="back_btn" class="btn btn-primary" data-role="back"><i
                                class="fas fa-chevron-left"></i> Назад
                        </button>
                        <button type="submit" id="forward_btn" class="btn btn-primary" data-role="forward">Далее <i
                                class="fas fa-chevron-right"></i></button>
                        <button type="submit" id="submit_btn" class="btn btn-primary" data-role="submit">Сохранить
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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
            'use strict'

            //let forms = document.querySelectorAll('.needs-validation');
            let submitButton = null;
            let activeTab = null;
            let allTabs = Array.from(document.querySelectorAll('a[data-bs-toggle="pill"]'));

            $('#wizard').on('submit', (event) => {
                let valid = true;
                if ((submitButton.id === 'forward_btn') || (submitButton.id === 'submit_btn')) {
                    // let parsley = $('#wizard').parsley();
                    // if (!parsley.isValid()) {
                    //     valid = false;
                    //     event.preventDefault();
                    //     event.stopPropagation();
                    //     console.log(false);
                    // }
                    // event.target.classList.add('was-validated');
                }

                if ((submitButton.id === 'back_btn') || (submitButton.id === 'forward_btn')) {
                    event.preventDefault();
                    event.stopPropagation();
                    if (!valid) return;

                    if (submitButton.id === 'forward_btn') {
                        let stepId = activeTab.dataset.id;
                        let nextTab = allTabs[parseInt(stepId) + 1];
                        nextTab.disabled = false;
                        let tab = new bootstrap.Tab(nextTab);
                        tab.show();
                    }
                    if (submitButton.id === 'back_btn') {
                        let stepId = activeTab.dataset.id;
                        let nextTab = allTabs[parseInt(stepId) - 1];
                        nextTab.disabled = false;
                        let tab = new bootstrap.Tab(nextTab);
                        tab.show();
                    }
                }
            });

            let backBtn = document.getElementById('back_btn');
            backBtn.addEventListener('click', (event) => {
                submitButton = backBtn;
            }, false);

            let forwardBtn = document.getElementById('forward_btn');
            forwardBtn.addEventListener('click', (event) => {
                submitButton = forwardBtn;
            }, false);

            let submitBtn = document.getElementById('submit_btn');
            submitBtn.addEventListener('click', (event) => {
                submitButton = submitBtn;
            }, false);

            Array.prototype.slice.call(allTabs)
                .forEach((btn) => {
                    btn.addEventListener('shown.bs.tab', (event) => {
                        activeTab = event.target;
                        let role = activeTab.dataset.role;
                        switch (role) {
                            case 'start':
                                $('#back_btn').hide();
                                $('#forward_btn').show();
                                $('#submit_btn').hide();
                                break;
                            case 'body':
                                $('#back_btn').show();
                                $('#forward_btn').show();
                                $('#submit_btn').hide();
                                break;
                            case 'finish':
                                $('#back_btn').show();
                                $('#forward_btn').hide();
                                $('#submit_btn').show();
                                break;
                        }
                        switch (activeTab.id) {
                            case 'vp-respondent-tab':
                                let identArea = document.getElementById('ident-area');
                                identArea.setAttribute("style", "height: " + wizardContentHeight + "px;");

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
                                //case($('auth'))
                                break;
                            case 'vp-mechanics-tab':
                                $('#mechanics1').change();
                                break;
                            case 'vp-branding':
                                $('#branding').change();
                                break;
                            case 'vp-robokassa':
                                $('$robokassa').change();
                                break;
                            default:
                                break;
                        }
                    }, false);
                });

            let firstTab = document.querySelector('#{{ $steps[0]['tab'] }}-tab');
            let tab = new bootstrap.Tab(firstTab);
            tab.show();

            let wizardContentHeight = 0;
            let testsWizard = document.getElementById('tests-create')
            testsWizard.addEventListener('shown.bs.modal', (event) => {
                let body = testsWizard.querySelectorAll(".modal-body");
                Array.prototype.slice.call(body)
                    .forEach((element) => {
                        wizardContentHeight = element.clientHeight - 100;
                    });
            }, false);

            $("input[name='mechanics']").on("change", () => {
                let options =
                    (document.getElementById('mechanics1').checked ? {{ \App\Models\Test::IMAGES2 }} : 0) |
                    (document.getElementById('mechanics2').checked ? {{ \App\Models\Test::IMAGES4 }} : 0);// |
                {{--(document.getElementById('aux_mechanics1').checked ? {{ \App\Models\Test::EYE_TRACKING }} : 0) |--}}
                {{--(document.getElementById('aux_mechanics2').checked ? {{ \App\Models\Test::MOUSE_TRACKING }} : 0) |--}}
                {{--(document.getElementById('aux_mechanics3').checked ? {{ \App\Models\Test::EQUIPMENT_MONITOR }} : 0);--}}
                $.post({
                    url: "{{ route('sets.filterbytest', ['sid' => $sid]) }}",
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

            let backgroundColor = '#007bff';
            let fontColor = '#ffffff';

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
                    backgroundColor = selectedColor;
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
                    fontColor = selectedColor;
                });

            $('#company-name-changer').on('input change', (event) => {
                $('#company-name-demo').html(event.target.value);
            });

            $('.image-clear').on("click", event => {
                let target = event.currentTarget.id;
                let preview = $('#' + target).data('preview');
                $('#' + preview).attr('src', "{{ $placeholder }}");
                $('#' + target).hide();
            });

            $("input[name='branding']").on("change", () => {
                let custom = document.getElementById('branding');
                let branding_panel = document.getElementById('branding_panel');
                if (custom.checked) {
                    branding_panel.style.display = "block";

                    $('.image-clear').hide();
                    $('#company-name-changer').change();
                    $('#background-input').val(backgroundColor);
                    $('#font-color-input').val(fontColor);
                } else {
                    branding_panel.style.display = "none";
                }
            });

            $("input[name='robokassa']").on("change", () => {
                let custom = document.getElementById('robokassa');
                let branding_panel = document.getElementById('robokassa_panel');
                if (custom.checked) {
                    robokassa_panel.style.display = "block";
                } else {
                    robokassa_panel.style.display = "none";
                }
            });

            // document.querySelectorAll("input[name='mechanics']").forEach((input) => {
            //     input.addEventListener('change', () => {
            //     });
            // });
        });
    </script>
@endpush
