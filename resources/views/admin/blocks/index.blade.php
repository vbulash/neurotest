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
                            <form
                                style="display:none"
                                action="{{ route('blocks.destroy', ['block' => 0]) }}"
                                method="post" class="float-left">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="delete_id" id="delete_id" value="">
                                <button type="submit" id="delete_btn" name="delete_btn"
                                        onclick="return confirm('Подтвердите удаление');">&nbsp;
                                </button>
                            </form>

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
                                    <table class="table table-bordered table-hover text-nowrap" id="blocks_table">
                                        <thead>
                                        <tr>
                                            <th style="width: 30px">#</th>
                                            <th>Краткое описание</th>
                                            <th>Тип блока</th>
                                            <th>Код нейропрофиля</th>
                                            <th>Наименование нейропрофиля</th>
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
                document.getElementById('delete_id').value = id;
                document.getElementById('delete_btn').click();
            }

            $(function () {
                window.table = $('#blocks_table').DataTable({
                    language: {
                        "url": "{{ asset('assets/admin/plugins/datatables/lang/ru/Russian.json') }}"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('blocks.index.data') !!}',
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'description', name: 'description'},
                        {
                            data: 'type', name: 'type', render: (data) => {
                                switch (data) {
                                    case {{ \App\Models\Block::TYPE_TEXT }}:
                                        return 'Текстовый блок';
                                    case {{ \App\Models\Block::TYPE_IMAGE }}:
                                        return 'Блок с изображением';
                                    case {{ \App\Models\Block::TYPE_VIDEO }}:
                                        return 'Блок с видео';
                                }
                            }
                        },
                        {data: 'profile_code', name: 'profile_code'},
                        {data: 'profile_name', name: 'profile_name'},
                        {data: 'action', name: 'action', sortable: false}
                    ]
                });
            });
        </script>
    @endpush
@endif

