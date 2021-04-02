@extends('admin.layouts.layout')

@push('title') - @if($show) Анкета @else Редактирование @endif модуля &laquo;{{ $block->name }}&raquo;@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@if($show) Анкета @else Редактирование @endif модуля &laquo;{{ $block->name }}&raquo;</h1>
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
                              action="{{ route('blocks.update', ['block' => $block->id]) }}"
                              @endif
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group col-lg-3 col-xs-6">
                                    <label for="sort_no">Номер по порядку</label>
                                    <input type="text" name="sort_no" id="sort_no"
                                           class="form-control"
                                           value="{{ $block->sort_no }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="title">Наименование модуля</label>
                                    <input type="text" name="title" id="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           value="{{ $block->name }}" @if($show) disabled @endif>
                                </div>

                                @yield('fieldset')
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
        });
    </script>
@endpush

