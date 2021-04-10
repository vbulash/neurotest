<!-- Modal -->
<div class="modal fade" id="tests-create" tabindex="-1" aria-labelledby="tests-create-label" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form role="form" method="post" action="{{ route('tests.store') }}"
                  enctype="multipart/form-data"
                  class="needs-validation" novalidate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tests-create-label">Новый тест</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @php
                    $steps = [
                        ['name' => 'Основная информация', 'tab' => 'vp-essentials', 'view' => 'admin.tests.wizard.step1', 'role' => 'start'],
                        ['name' => 'Конструктор анкеты', 'tab' => 'vp-respondent', 'view' => 'admin.tests.wizard.step2'],
                        ['name' => 'Описание ФМП', 'tab' => 'vp-fmp-description', 'view' => ''],
                        ['name' => 'Механика нейротеста', 'tab' => 'vp-mechanics', 'view' => 'admin.tests.wizard.step4'],
                        ['name' => 'Набор вопросов', 'tab' => 'vp-set', 'view' => ''],
                        ['name' => 'Финал теста', 'tab' => 'vp-final', 'view' => 'admin.tests.wizard.step6', 'role' => 'finish']
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
                                <button class="nav-link"
                                        id="{{ $step['tab'] }}-tab" data-bs-toggle="pill"
                                        data-bs-target="#{{ $step['tab'] }}" type="button" role="tab"
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
                                </button>
                                @php($stepNo++)
                            @endforeach
                        </div>
                        <div class="tab-content" id="v-pills-tabContent">
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
                                class="fas fa-chevron-left"> Назад</i></button>
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
        $(function () {
            'use strict'

            let forms = document.querySelectorAll('.needs-validation');
            let submitButton = null;
            let activeTab = null;
            let allTabs = Array.from(document.querySelectorAll('button[data-bs-toggle="pill"]'));

            Array.prototype.slice.call(forms)
                .forEach((form) => {
                    form.addEventListener('submit', (event) => {
                        let valid = true;
                        if ((submitButton.id === 'forward_btn') || (submitButton.id === 'submit_btn')) {
                            valid = form.checkValidity();
                            if (!valid) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated')
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
                    }, false);
                });

            let backBtn = document.getElementById('back_btn');
            backBtn.addEventListener('click', (event) => {
                submitButton = backBtn;
            });

            let forwardBtn = document.getElementById('forward_btn');
            forwardBtn.addEventListener('click', (event) => {
                submitButton = forwardBtn;
            });

            let submitBtn = document.getElementById('submit_btn');
            submitBtn.addEventListener('click', (event) => {
                submitButton = submitBtn;
            });

            let tabBtns = document.querySelectorAll('button[data-bs-toggle="pill"]')
            Array.prototype.slice.call(tabBtns)
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
                                break;
                            default:
                                break;
                        }
                    }, false);
                });

            let firstTab = document.querySelector('#{{ $steps[0]['tab'] }}-tab')
            let tab = new bootstrap.Tab(firstTab)
            tab.show()

            $("#kind").on("change", (event) => {
                if ($('#kind').val() !== "{{ \App\Models\Test::TYPE_EXACT }}") {
                    $('#contract-div').hide();
                } else {
                    $('#contract-div').show();
                }
            });
            $('#kind').change();

            let wizardContentHeight = 0;
            let testsWizard = document.getElementById('tests-create')
            testsWizard.addEventListener('shown.bs.modal', (event) => {
                let body = testsWizard.querySelectorAll(".modal-body");
                Array.prototype.slice.call(body)
                    .forEach((element) => {
                        wizardContentHeight = element.clientHeight - 100;
                    });
            })
        })();
    </script>
@endpush
