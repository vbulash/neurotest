@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Блоки описаний</h1>
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
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle mb-3" type="button" id="blocks-create"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    Добавить блок
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="blocks-create">
                                    <li><a class="dropdown-item"
                                           href="{{ route('blocks.create', ['type' => \App\Models\Block::TYPE_TEXT]) }}">Текстовый
                                            блок</a></li>
                                    <li><a class="dropdown-item"
                                           href="{{ route('blocks.create', ['type' => \App\Models\Block::TYPE_IMAGE]) }}">Блок
                                            с изображением</a></li>
                                    <li><a class="dropdown-item"
                                           href="{{ route('blocks.create', ['type' => \App\Models\Block::TYPE_VIDEO]) }}">Блок
                                            с видео</a></li>
                                </ul>
                            </div>
                            @if ($blocks)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover text-nowrap" id="blocks_table" style="width: 100%;">
                                        <thead>
                                        <tr>
                                            <th style="width: 30px">#</th>
                                            <th>Наименование</th>
                                            <th>Тип блока</th>
                                            <th>Нейропрофиль / тип описания</th>
                                            <th>Действия</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            @else
                                <p>Блоков описаний пока нет...</p>
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
            function clickDelete(id) {
                if(window.confirm('Удалить блок № ' + id + ' ?')) {
                    $.ajax({
                        method: 'DELETE',
                        url: "{{ route('blocks.destroy', ['block' => '0']) }}",
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

            $(function () {
                window.datatable = $('#blocks_table').DataTable({
                    language: {
                        "url": "{{ asset('assets/admin/plugins/datatables/lang/ru/Russian.json') }}"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('blocks.index.data') !!}',
                    responsive: true,
                    columns: [
                        {data: 'id', name: 'id', responsivePriority: 1},
                        {data: 'description', name: 'description', responsivePriority: 1},
                        {
                            data: 'type', name: 'type', responsivePriority: 3, render: (data) => {
                                switch (parseInt(data)) {
                                    case {{ \App\Models\Block::TYPE_TEXT }}:
                                        return 'Текстовый блок';
                                    case {{ \App\Models\Block::TYPE_IMAGE }}:
                                        return 'Блок с изображением';
                                    case {{ \App\Models\Block::TYPE_VIDEO }}:
                                        return 'Блок с видео';
                                }
                            }
                        },
                        {data: 'profile', name: 'profile', responsivePriority: 2},
                        {data: 'action', name: 'action', sortable: false, responsivePriority: 1, className: 'no-wrap dt-actions'}
                    ]
                });
            });
        </script>
    @endpush
@endif

