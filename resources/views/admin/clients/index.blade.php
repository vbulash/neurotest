@extends('admin.layouts.layout')

@push('title') - Список клиентов@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Клиенты</h1>
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
                            <h3 class="card-title">Список клиентов</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <a href="{{ route('clients.create', ['sid' => $sid]) }}" class="btn btn-primary mb-3">Добавить клиента</a>
                            @if ($count)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover text-nowrap" id="clients_table" style="width: 100%;">
                                        <thead>
                                        <tr>
                                            <th style="width: 30px">#</th>
                                            <th>Наименование</th>
                                            <th>Контракты</th>
                                            <th>Действия</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            @else
                                <p>Клиентов пока нет...</p>
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
    @if ($count)
        @push('styles')
            <link rel="stylesheet" href="{{ asset('assets/admin/plugins/datatables/datatables.css') }}">
        @endpush

        @push('scripts')
            <script src="{{ asset('assets/admin/plugins/datatables/datatables.js') }}"></script>
        @endpush
    @endif
@endonce

@if($count)
    @push('scripts.injection')
        <script>
            $(function () {
                $('#clients_table').DataTable({
                    language: {
                        "url": "{{ asset('assets/admin/plugins/datatables/lang/ru/Russian.json') }}"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('clients.index.data', ['sid' => $sid]) !!}',
                    responsive: true,
                    columns: [
                        {data: 'id', name: 'id', responsivePriority: 1},
                        {data: 'name', name: 'name', responsivePriority: 1},
                        {data: 'contracts', name: 'contracts', responsivePriority: 2, sortable: false},
                        {data: 'action', name: 'action', sortable: false, responsivePriority: 1, className: 'no-wrap dt-actions'}
                    ]
                });
            });
        </script>
    @endpush
@endif

