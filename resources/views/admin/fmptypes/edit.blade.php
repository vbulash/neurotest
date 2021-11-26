@extends('admin.layouts.layout')

@push('title') - @if($show) Просмотр @else Редактирование @endif типа описания@endpush

@section('back')
    <form action="{{ route('fmptypes.back') }}" method="post">
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
                    <h1>@if($show) Карточка @else Редактирование @endif типа описания
                        &laquo;{{ $fmptype->name }}&raquo;</h1>
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
                            <h3 class="card-title">Тип описания</h3>
                        </div>
                        <!-- /.card-header -->

                        <form role="form" method="post"
                              action="{{ route('fmptypes.update', ['fmptype' => $fmptype->id]) }}"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Наименование</label>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror" id="name"
                                           value="{{ $fmptype->name }}" @if($show) disabled @endif
                                    >
                                </div>
                                <div class="form-group btn-group mt-3 col-lg-3 col-xs-12" role="group"
                                     aria-label="Тип описания">
                                    <input type="radio" class="btn-check" name="profiletype" id="profiletype1"
                                           autocomplete="off" value="1"
                                           @if($fmptype->cluster) checked @endif
                                           @if($show) disabled @endif
                                    >
                                    <label class="btn btn-outline-primary" for="profiletype1">Нейрокластер</label>

                                    <input type="radio" class="btn-check" name="profiletype" id="profiletype2"
                                           autocomplete="off" value="0"
                                           @if(!$fmptype->cluster) checked @endif
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

                    <!-- Нейропрофили -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Список нейропрофилей</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <a href="{{ route('neuroprofiles.create', ['fmptype_id' => $fmptype->id]) }}"
                               class="btn btn-primary mb-3">Добавить нейропрофиль</a>
                            @if (count($profiles) > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover text-nowrap" id="profiles_table" style="width: 100%;">
                                        <thead>
                                        <tr>
                                            <th style="width: 30px">#</th>
                                            <th>Код</th>
                                            <th>Наименование</th>
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
                    <!-- .Нейропрофили -->
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
                if(window.confirm('Удалить нейропрофиль № ' + id + ' ?')) {
                    $.ajax({
                        method: 'DELETE',
                        url: "{{ route('neuroprofiles.destroy', ['neuroprofile' => 0]) }}",
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
                window.datatable = $('#profiles_table').DataTable({
                    language: {
                        "url": "{{ asset('assets/admin/plugins/datatables/lang/ru/Russian.json') }}"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('neuroprofiles.index.data', ['fmptype_id' => $fmptype->id, 'sid' => $sid]) !!}',
                    responsive: true,
                    columns: [
                        {data: 'id', name: 'id', responsivePriority: 1},
                        {data: 'code', name: 'code', responsivePriority: 1},
                        {data: 'name', name: 'name', responsivePriority: 2},
                        {data: 'fmptype', name: 'fmptype', visible: false},
                        {data: 'action', name: 'action', sortable: false, responsivePriority: 1, className: 'no-wrap dt-actions'}
                    ]
                });
            });
        </script>
    @endpush
@endif
