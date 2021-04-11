@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Блоки описания ФМП</h1>
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
                            <h3 class="card-title">Список блоков</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @can('users.create')
                                <a href="{{ route('blocks.create') }}" class="btn btn-primary mb-3">Добавить блок</a>
                            @endcan
                            @if (count($blocks))
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover text-nowrap" id="blocks_table">
                                        <thead>
                                        <tr>
                                            <th style="width: 30px">#</th>
                                            <th>Краткое описание</th>
                                            <th>Действия</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            @else
                                <p>Блоков пока нет...</p>
                            @endif
                        </div>
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
            <script src="{{ asset('assets/admin/plugins/datatables/datatables.js') }}"></script>
        @endpush
    @endif
@endonce

@if(count($blocks))
    @push('scripts.injection')
        <script>
            $(function () {
                window.table = $('#roles_table').DataTable({
                    language: {
                        "url": "{{ asset('assets/admin/plugins/datatables/lang/ru/Russian.json') }}"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('blocks.index.data') !!}',
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'description', name: 'description'},
                        {data: 'action', name: 'action', sortable: false}
                    ]
                });
            });
        </script>
    @endpush
@endif

