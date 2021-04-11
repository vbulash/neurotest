@extends('admin.layouts.layout')

@push('title') - Новый тест@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Новый вопрос описания ФМП</h1>
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
                            <h3 class="card-title">&nbsp;</h3>
                        </div>
                        <!-- /.card-header -->

                        <form role="form" method="post" action="{{ route('blocks.store') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Наименование</label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror">
                                </div>
                                <div class="form-group">
                                    <label for="description">Краткое описание</label>
                                    <input type="text" name="description" id="description"
                                           class="form-control @error('descriptions') is-invalid @enderror">
                                </div>
                                <div class="form-group">
                                    <label for="content-editor">Наименование</label>
                                    <div id="toolbar-container"></div>
                                    <div id="content-editor">
                                        <p>This is the initial editor content.</p>
                                    </div>
                                </div>
                                <div class="all-editor">

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
    @if (count($blocks))
        @push('styles')
            <link rel="stylesheet" href="{{ asset('assets/admin/plugins/datatables/datatables.css') }}">
        @endpush

        @push('scripts')
            <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
        @endpush
    @endif
@endonce

@push('scripts.injection')
    <script>
        (async function() {
            try {
                const editor = await DecoupledEditor
                    .create(document.querySelector('#content-editor'))
                    .then( editor => {
                        const toolbarContainer = document.querySelector('#toolbar-container');
                        toolbarContainer.appendChild( editor.ui.view.toolbar.element );
                    } )
                    .catch( error => {
                        console.error( error );
                    });
                editor.setData('This text is generated from setdata() method.');
            } catch(e) {
                console.error(e);
            }
        })();
    </script>
@endpush
