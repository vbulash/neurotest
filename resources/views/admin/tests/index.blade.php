@extends('admin.layouts.layout')

@push('title') - Список тестов@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Тесты</h1>
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
                            <h3 class="card-title">Список тестов</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form
                                style="display:none"
                                action="{{ route('tests.destroy', ['test' => 0]) }}"
                                method="post" class="float-left">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="delete_id" id="delete_id" value="">
                                <button type="submit" id="delete_btn" name="delete_btn"
                                        onclick="return confirm('Подтвердите удаление');">&nbsp;
                                </button>
                            </form>

                            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                    data-bs-target="#tests-create">
                                Добавить тест
                            </button>
                            {{--                            <a href="{{ route('tests.create') }}" class="btn btn-primary mb-3">Добавить тест</a>--}}
                            @if ($count)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover text-nowrap" id="tests_table" style="width: 100%;">
                                        <thead>
                                        <tr>
                                            <th style="width: 30px">#</th>
                                            <th>Наименование</th>
                                            <th>Статус</th>
                                            <th>Привязка к контракту клиента</th>
                                            <th>Действия</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            @else
                                <p>Тестов пока нет...</p>
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

@section('modals')
    @include('admin.tests.create-modal')
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
            function clickDelete(id) {
                document.getElementById('delete_id').value = id;
                document.getElementById('delete_btn').click();
            }

            $(function () {
                $('#tests_table').DataTable({
                    language: {
                        "url": "{{ asset('assets/admin/plugins/datatables/lang/ru/Russian.json') }}"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('tests.index.data') !!}',
                    responsive: true,
                    columns: [
                        {data: 'id', name: 'id', responsivePriority: 1},
                        {data: 'name', name: 'name', responsivePriority: 1},
                        {
                            data: 'type', name: 'type', responsivePriority: 2, render: (data) => {
                                switch (data) {
                                    case {{ \App\Models\Test::TYPE_DRAFT }}:
                                        return 'Черновик';
                                    case {{ \App\Models\Test::TYPE_ACTIVE }}:
                                        return 'Активный';
                                    case {{ \App\Models\Test::TYPE_TEMPLATE }}:
                                        return 'Шаблон';
                                    case {{ \App\Models\Test::TYPE_TEST }}:
                                        return 'Тестовый';
                                    default:
                                        return 'Ошибка';
                                }
                            }
                        },
                        {
                            data: 'contract', name: 'contract', responsivePriority: 3, render: (data) => {
                                return data != null ? data : 'Не применимо';
                            }
                        },
                        {data: 'action', name: 'action', sortable: false, responsivePriority: 1, className: 'no-wrap dt-actions'}
                    ]
                });
            });
        </script>
    @endpush
@endif

