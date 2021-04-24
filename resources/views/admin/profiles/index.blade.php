@extends('admin.layouts.layout')

@push('title') - Нейропрофили @endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Нейропрофили</h1>
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
                        <div class="card-header">
                            <h3 class="card-title">Список нейропрофилей</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form
                                style="display:none"
                                action="{{ route('neuroprofiles.destroy', ['neuroprofile' => 0]) }}"
                                method="post" class="float-left">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="delete_id" id="delete_id" value="">
                                <button type="submit" id="delete_btn" name="delete_btn"
                                        onclick="return confirm('Подтвердите удаление');">&nbsp;
                                </button>
                            </form>

                            <a href="{{ route('neuroprofiles.create') }}" class="btn btn-primary mb-3">Добавить нейропрофиль</a>
                            @if (count($profiles) > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover text-nowrap" id="profiles_table">
                                        <thead>
                                        <tr>
                                            <th style="width: 30px">#</th>
                                            <th>Код</th>
                                            <th>Наименование</th>
                                            <th>ФМП / Нейрокластер</th>
                                            <th>Тип описания</th>
                                            <th>Действия</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            @else
                                <p>Нейропрофилей пока нет...</p>
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
    @if (count($profiles) > 0)
        @push('styles')
            <link rel="stylesheet" href="{{ asset('assets/admin/plugins/datatables/datatables.css') }}">
        @endpush

        @push('scripts')
            <script src="{{ asset('assets/admin/plugins/datatables/datatables.js') }}"></script>
        @endpush
    @endif
@endonce

@if(count($profiles) > 0)
    @push('scripts.injection')
        <script>
            function clickDelete(id) {
                document.getElementById('delete_id').value = id;
                document.getElementById('delete_btn').click();
            }

            $(function () {
                window.table = $('#profiles_table').DataTable({
                    language: {
                        "url": "{{ asset('assets/admin/plugins/datatables/lang/ru/Russian.json') }}"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('neuroprofiles.index.data', ['fmptype_id' => 0]) !!}',
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'code', name: 'code'},
                        {data: 'name', name: 'name'},
                        {data: 'cluster', name: 'cluster', render: (data) => {
                            if(data) {
                                return 'Нейрокластер';
                            } else {
                                return 'ФМП';
                            }
                            }},
                        {data: 'fmptype', name: 'fmptype'},
                        {data: 'action', name: 'action', sortable: false}
                    ]
                });
            });
        </script>
    @endpush
@endif