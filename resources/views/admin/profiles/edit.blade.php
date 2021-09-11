@extends('admin.layouts.layout')

@push('title') - @if($show) Просмотр @else Редактирование @endif нейропрофиля &laquo;{{ $profile->name }}&raquo;@endpush

@section('back')
    <form action="{{ route('neuroprofiles.back') }}" method="post">
        @csrf
        <button type="submit" id="back_btn" name="back_btn" class="btn btn-primary">
            <i class="fas fa-chevron-left"></i> Назад
        </button>
    </form>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@if($show) Карточка @else Редактирование @endif нейропрофиля
                        &laquo;{{ $profile->name }}&raquo;</h1>
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
                        <form role="form" method="post"
                              action="{{ route('neuroprofiles.update', ['neuroprofile' => $profile->id]) }}"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <input type="hidden" value="{{ $profile->id }}" name="id">
                                @if($embedded)
                                    <div class="form-group col-lg-3 col-xs-6">
                                        <label for="fmptype_">Тип описания</label>
                                        <input type="text" name="fmptype_"
                                               class="form-control" id="fmptype_"
                                               value="{{ $fmptypes->name }}"
                                               disabled>
                                    </div>
                                    <input type="hidden" id="fmptype" name="fmptype" value="{{ $fmptypes->id }}">
                                @endif
                                <div class="form-group col-lg-6 col-xs-12">
                                    <label for="code">Код нейропрофиля</label>
                                    <select name="code" id="code" class="select2 form-control"
                                            data-placeholder="Выбор кода нейропрофиля">
                                        @php
                                            $codes = [
                                                'OV', 'OI', 'OA', 'OO',
                                                'PA', 'PP', 'PK', 'PR',
                                                'CS', 'CI', 'CO', 'CV',
                                                'BD', 'BH', 'BP', 'BO'
                                            ];
                                        @endphp
                                        @foreach($codes as $code)
                                            <option value="{{ $code }}"
                                                    @if($profile->code == $code) selected @endif>{{ $code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label for="name">Наименование</label>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror" id="name"
                                           value="{{ $profile->name }}"
                                           @if($show) disabled @endif
                                    >
                                </div>
                                @if(!$embedded)
                                    <div class="form-group col-lg-6 col-xs-12">
                                        <label for="fmptype">Тип описания</label>
                                        <select name="fmptype" id="fmptype" class="select2 form-control"
                                                data-placeholder="Выбор типа описания" @if($show) disabled @endif>
                                            @foreach($fmptypes as $fmptype)
                                                <option value="{{ $fmptype->id }}"
                                                        @if($profile->fmptype_id == $fmptype->id) selected @endif>{{ $fmptype->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                @if(!$show)
                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                @endif
                            </div>
                        </form>

                    </div>
                    <!-- /.card -->

                    <!-- Блоки -->
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
                                           href="{{ route('blocks.create',
                                                ['type' => \App\Models\Block::TYPE_TEXT, 'profile_id' => $profile->id, 'embedded' => true]) }}">Текстовый
                                            блок</a></li>
                                    <li><a class="dropdown-item"
                                           href="{{ route('blocks.create',
                                                ['type' => \App\Models\Block::TYPE_IMAGE, 'profile_id' => $profile->id, 'embedded' => true]) }}">Блок
                                            с изображением</a></li>
                                    <li><a class="dropdown-item"
                                           href="{{ route('blocks.create',
                                                ['type' => \App\Models\Block::TYPE_VIDEO, 'profile_id' => $profile->id, 'embedded' => true]) }}">Блок
                                            с видео</a></li>
                                </ul>
                            </div>
                            @if (count($blocks) > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover text-nowrap" id="blocks_table" style="width: 100%;">
                                        <thead>
                                        <tr>
                                            <th style="width: 30px">#</th>
                                            <th>Наименование</th>
                                            <th>Тип</th>
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
                    <!-- .Блоки -->

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@once
    @if (count($blocks) > 0)
        @push('styles')
            <link rel="stylesheet" href="{{ asset('assets/admin/plugins/datatables/datatables.css') }}">
        @endpush

        @push('scripts')
            <script src="{{ asset('assets/admin/plugins/datatables/datatables.js') }}"></script>
        @endpush
    @endif
@endonce

@if(count($blocks) > 0)
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
                    ajax: '{!! route('blocks.index.data', ['profile_id' => $profile->id]) !!}',
                    responsive: true,
                    columns: [
                        {data: 'id', name: 'id', responsivePriority: 1},
                        {data: 'description', name: 'description', responsivePriority: 1},
                        {
                            data: 'type', name: 'type', responsivePriority: 3, render: (data) => {
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
                        {data: 'profile', name: 'profile', responsivePriority: 2},
                        {data: 'action', name: 'action', sortable: false, responsivePriority: 1, className: 'no-wrap dt-actions'}
                    ]
                });
            });
        </script>
    @endpush
@endif
