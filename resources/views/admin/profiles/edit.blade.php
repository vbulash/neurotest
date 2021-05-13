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
                                                'OV', 'OJ', 'OA', 'OO',
                                                'PA', 'PP', 'PK', 'PR',
                                                'CS', 'CJ', 'CO', 'CV',
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
                                <div class="form-group btn-group mt-3 col-lg-3 col-xs-12" role="group"
                                     aria-label="Тип нейропрофиля">
                                    <input type="radio" class="btn-check" name="profiletype" id="profiletype1"
                                           autocomplete="off" value="1"
                                           @if($profile->cluster) checked @endif
                                           @if($show) disabled @endif
                                    >
                                    <label class="btn btn-outline-primary" for="profiletype1">Нейрокластер</label>

                                    <input type="radio" class="btn-check" name="profiletype" id="profiletype2"
                                           autocomplete="off" value="0"
                                           @if(!$profile->cluster) checked @endif
                                           @if($show) disabled @endif
                                    >
                                    <label class="btn btn-outline-primary" for="profiletype2">ФМП</label>
                                </div>
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
                    ajax: '{!! route('blocks.index.data', ['profile_id' => $profile->id]) !!}',
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
