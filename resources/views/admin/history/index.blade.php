@extends('admin.layouts.layout')

@push('title') - История прохождения тестов@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>История прохождения тестов</h1>
                </div>
{{--                <div class="col-sm-6">--}}
{{--                    <ol class="breadcrumb float-sm-right">--}}
{{--                        <li class="breadcrumb-item"><a href="#">Home</a></li>--}}
{{--                        <li class="breadcrumb-item active">Blank Page</li>--}}
{{--                    </ol>--}}
{{--                </div>--}}
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
{{--                        <div class="card-header">--}}
{{--                            <h3 class="card-title">История прохождения тестов</h3>--}}
{{--                        </div>--}}
                        <!-- /.card-header -->
                        <div class="card-body">
                            @if ($count)
                                <a href="{{ route('history.detail', ['sid' => $sid]) }}" class="btn btn-primary mb-3">Экспорт в Excel</a>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover text-nowrap" id="history_table" style="width: 100%;">
                                        <thead>
                                        <tr>
                                            <th style="width: 30px">#</th>
											<th>Лицензия</th>
                                            <th>Тест пройден</th>
                                            <th>Имя</th>
                                            <th>Фамилия</th>
                                            <th>Электронная почта</th>
                                            <th>Код нейропрофиля</th>
                                            <th>Оплата теста</th>
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
                if(window.confirm('Удалить запись истории № ' + id + ' ?')) {
                    $.ajax({
                        method: 'DELETE',
                        url: "{{ route('history.destroy', ['history' => 0, 'sid' => $sid]) }}",
                        data: {
                            id: id,
                        },
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: () => {
                            window.datatable.ajax.reload();
                        }
                    });
                }
            }

            function clickMail(id) {
                if(window.confirm('Повторить письмо с полными результатами тестирования ?')) {
                    $.ajax({
                        method: 'POST',
                        url: "{{ route('history.mail.repeat', ['sid' => $sid]) }}",
                        data: {
                            InvId: id,
                            Shp_Mail: '0',
                            List: '1'
                        },
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: () => {
                            window.datatable.ajax.reload();
                        }
                    });
                }
            }

            $(function () {
                window.datatable = $('#history_table').DataTable({
                    "language": {
                        "url": "{{ asset('assets/admin/plugins/datatables/lang/ru/Russian.json') }}"
                    },
                    "processing": true,
                    "serverSide": true,
                    "ajax": '{!! route('history.index.data', ['sid' => $sid]) !!}',
                    "responsive": true,
                    "pageLength": 50,
                    // "dom": 'Blfrtip',
                    // "buttons": [
                    //     {
                    //         "extend": 'excel',
                    //         "text": 'Экспорт в Excel',
                    //         "className": 'btn btn-info btn-sm float-left ml-5 mb-2'
                    //     }
                    // ],
                    "columns": [
                        {"data": 'id', "name": 'id', "responsivePriority": 1},
						{"data": 'license', "name": 'license', "responsivePriority": 1},
                        {"data": 'done', "name": 'done', "responsivePriority": 1},
                        {"data": 'first_name', "name": 'first_name', "responsivePriority": 1},
                        {"data": 'last_name', "name": 'last_name', "responsivePriority": 1},
                        {"data": 'email', "name": 'email', "responsivePriority": 1},
                        {"data": 'code', "name": 'code', "responsivePriority": 3, "className": 'dt-center'},
                        {
                            "data": 'paid', "name": 'paid', "responsivePriority": 2, "render": (data) => {
                                return (data === 1 ? 'Оплачен' : 'Не оплачен');
                            }
                        },
                        {"data": 'action', "name": 'action', "sortable": false, "responsivePriority": 1, "className": 'no-wrap dt-actions'}
                    ]
                });

                // window.datatable.buttons().container()
                //     .appendTo( $('.col-sm-6:eq(0)', window.datatable.table().container() ) );
            });
        </script>
    @endpush
@endif

