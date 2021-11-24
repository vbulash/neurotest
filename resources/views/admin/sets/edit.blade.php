@extends('admin.layouts.layout')

@push('title') - @if($show) Просмотр @else Редактирование @endif набора вопросов &laquo;{{ $set->name }}&raquo;@endpush

@section('body-params')
    data-editor="DecoupledDocumentEditor" data-collaboration="false"
@endsection

@section('back')
    <form action="{{ route('sets.back') }}" method="post">
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
                    <h1>@if($show) Карточка @else Редактирование @endif набора вопросов
                        &laquo;{{ $set->name }}&raquo;</h1>
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
                            <h3 class="card-title">Набор вопросов</h3>
                        </div>
                        <!-- /.card-header -->

                        <form role="form" method="post" name="set-edit" id="set-edit"
                              action="{{ route('sets.update', ['set' => $set->id]) }}"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Наименование</label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ $set->name }}" @if($show) disabled @endif>
                                </div>
                                <div class="form-group col-lg-6 col-xs-12">
                                    <label for="quantity">Состав каждого вопроса</label>
                                    <select name="quantity" id="quantity" class="select2 col-lg-6 col-xs-12"
                                            style="width: 100%;" disabled>
                                        <option value="{{ \App\Models\QuestionSet::IMAGES2 }}"
                                                @if($set->quantity == \App\Models\QuestionSet::IMAGES2) selected @endif>
                                            2
                                            изображения
                                        </option>
                                        <option value="{{ \App\Models\QuestionSet::IMAGES4 }}"
                                                @if($set->quantity == \App\Models\QuestionSet::IMAGES4) selected @endif>
                                            4
                                            изображения
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-xs-12">
                                    <label for="type">Статус набора вопросов</label>
                                    <select name="type" id="type" class="select2"
                                            style="width: 100%;" @if($show) disabled @endif>
                                        @foreach(\App\Models\QuestionSet::types as $key => $value)
                                            <option value="{{ $key }}"
                                                    @if($set->type == $key) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" id="content" name="content">
                                    <label for="editor" class="mb-2">Скрипт обработки ключей</label>
                                    <div class="">
                                        <div class="row">
                                            <div class="document-editor__toolbar"></div>
                                        </div>
                                        <div class="row row-editor">
                                            <div class="editor"></div>
                                        </div>
                                    </div>
                                </div>

{{--                                <div class="form-group col-lg-6 col-xs-12" id="contract-div">--}}
{{--                                    <label for="client_id">Клиент для набора вопросов (имеет смысл только для типа--}}
{{--                                        &laquo;Исключительный&raquo;)</label>--}}
{{--                                    <select name="client_id" id="client_id" class="select2"--}}
{{--                                            data-placeholder="Выбор клиента"--}}
{{--                                            style="width: 100%;" @if($show) disabled @endif>--}}
{{--                                        @foreach($clients as $client)--}}
{{--                                            <option value="{{ $client->id }}"--}}
{{--                                                    @if($set->client_id == $client->id) selected @endif>{{ $client->name }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
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

                    @include('admin.questions.embedded')

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
            // $('#contract-div').hide();
        });
    </script>
@endpush

@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/admin/plugins/ckeditor5/ckeditor.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/admin/plugins/ckeditor5/ckeditor.js') }}"></script>
    @endpush
@endonce

@push('scripts.injection')
    <script>
        DecoupledDocumentEditor
            .create(document.querySelector('.editor'), {
                toolbar: {
                    items: [
                        'heading',
                        '|',
                        'fontSize',
                        'fontFamily',
                        '|',
                        'fontColor',
                        'fontBackgroundColor',
                        '|',
                        'bold',
                        'italic',
                        'underline',
                        'strikethrough',
                        'subscript',
                        'superscript',
                        'highlight',
                        '|',
                        'alignment',
                        '|',
                        'numberedList',
                        'bulletedList',
                        '|',
                        'outdent',
                        'indent',
                        'codeBlock',
                        '|',
                        'todoList',
                        'link',
                        'blockQuote',
                        'insertTable',
                        '|',
                        'undo',
                        'redo'
                    ]
                },
                language: 'ru',
                codeBlock: {
                    languages: [
                        {language: 'php', label: 'PHP'}
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn',
                        'tableRow',
                        'mergeTableCells',
                        'tableCellProperties',
                        'tableProperties'
                    ]
                },
                licenseKey: '',
            })
            .then(editor => {
                window.editor = editor;

                document.querySelector('.document-editor__toolbar').appendChild(editor.ui.view.toolbar.element);
                document.querySelector('.ck-toolbar').classList.add('ck-reset_all');

                editor.setData('{!! str_replace("\r\n", "<br/>", $set->content) !!}');
                @if($show)
                    editor.isReadOnly = true;
                @endif
            })
            .catch(error => {
                console.error('Oops, something went wrong!');
                console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
                console.warn('Build id: bfknlbbh0ej1-27rpc1i5joqr');
                console.error(error);
            });

        document.getElementById('set-edit').addEventListener('submit', () => {
            document.getElementById('content').value = editor.getData();
        }, false);
    </script>
@endpush
