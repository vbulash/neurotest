@extends('admin.layouts.layout')

@push('title') - Новый блок описания ФМП@endpush

@section('body-params')
    data-editor="DecoupledDocumentEditor" data-collaboration="false"
@endsection

@section('back')
	<a href="{{ url()->previous() }}" class="btn btn-primary">
		<i class="fas fa-chevron-left"></i> Назад
	</a>
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
                            <h3 class="card-title">Блок JavaScript</h3>
                        </div>
                        <!-- /.card-header -->

                        <form role="form" method="post" name="blocks-create" id="blocks-create"
                              action="{{ route('blocks.store', ['sid' => $sid]) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="kind" name="kind" value="{{ \App\Models\Block::TYPE_TEXT }}">
                            <input type="hidden" id="content" name="content">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="description">Краткое наименование</label>
                                    <input type="text" name="description" id="description"
                                           class="form-control @error('description') is-invalid @enderror">
                                </div>
                                <div class="">
                                    <div class="form-group">
                                        <label for="editor" class="mb-2">Скрипт</label>
                                        <div class="centered">
                                            <div class="row">
                                                <div class="document-editor__toolbar"></div>
                                            </div>
                                            <div class="row row-editor">
                                                <div class="editor"></div>
                                            </div>
                                        </div>
                                    </div>
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
                        '|',
                        'alignment',
                        '|',
                        'numberedList',
                        'bulletedList',
                        '|',
                        'outdent',
                        'indent',
                        '|',
                        'todoList',
                        'link',
                        'blockQuote',
                        //'imageUpload',
                        'insertTable',
                        'mediaEmbed',
                        '|',
                        'undo',
                        'redo'
                    ]
                },
                language: 'ru',
                image: {
                    toolbar: [
                        'imageTextAlternative',
                        'imageStyle:full',
                        'imageStyle:side'
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
                placeholder: 'Введите текст...',
            })
            .then(editor => {
                window.editor = editor;

                document.querySelector('.document-editor__toolbar').appendChild(editor.ui.view.toolbar.element);
                document.querySelector('.ck-toolbar').classList.add('ck-reset_all');

                //editor.isReadOnly = true;
            })
            .catch(error => {
                console.error('Oops, something went wrong!');
                console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
                console.warn('Build id: ebmrqv6vfk7t-u9490jx48w7r');
                console.error(error);
            });

        document.getElementById('blocks-create').addEventListener('submit', event => {
            document.getElementById('content').value = editor.getData();
        }, false);
    </script>
@endpush
