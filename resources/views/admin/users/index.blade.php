@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Пользователи</h1>
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
                            <h3 class="card-title">Список пользователей</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @can('users.create')
                                <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Добавить
                                    пользователя</a>
                            @endcan
                            @if (count($users))
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover text-nowrap" id="users_table" style="width: 100%;">
                                        <thead>
                                        <tr>
                                            <th style="width: 30px">#</th>
                                            <th>ФИО</th>
                                            <th>Электронная почта</th>
                                            <th>Телефон</th>
                                            <th>Роли</th>
                                            <th>Работа с клиентами</th>
                                            <th>Действия</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            @else
                                <p>Пользователей пока нет...</p>
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
    @if (count($users) > 0)
        @push('styles')
            <link rel="stylesheet" href="{{ asset('assets/admin/plugins/datatables/datatables.css') }}">
        @endpush

        @push('scripts')
            <script src="{{ asset('assets/admin/plugins/datatables/datatables.js') }}"></script>
        @endpush
    @endif
@endonce

@if(count($users) > 0)
    @push('scripts.injection')
        <script>
            $(function () {
                window.table = $('#users_table').DataTable({
                    language: {
                        "url": "{{ asset('assets/admin/plugins/datatables/lang/ru/Russian.json') }}"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('users.index.data') !!}',
                    responsive: true,
                    columns: [
                        {data: 'id', name: 'id', responsivePriority: 1},
                        {data: 'name', name: 'name', responsivePriority: 1},
                        {data: 'email', name: 'email', responsivePriority: 2},
                        {data: 'phone', name: 'phone', responsivePriority: 2},
                        {
                            data: 'roles', name: 'roles', responsivePriority: 3, render: (data) => {
                                return data.join(",<br/>");
                            }
                        },
                        {
                            data: 'clients', name: 'clients', sortable: false, responsivePriority: 4, render: (data) => {
                                return data.join(",<br/>");
                            }
                        },
                        {data: 'action', name: 'action', sortable: false, responsivePriority: 1, className: 'no-wrap dt-actions'}
                    ]
                });
            });
        </script>
    @endpush
@endif

