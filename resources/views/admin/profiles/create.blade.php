@extends('admin.layouts.layout')

@push('title') - Новый нейропрофиль@endpush

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
                    <h1>Новый нейропрофиль</h1>
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
                        <form role="form" method="post" action="{{ route('neuroprofiles.store') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h3 class="card-title">
                                    Создание и привязка блоков станут доступны после сохранения нового нейропрофиля
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                @if($embedded)
                                    <div class="form-group col-lg-6 col-xs-12">
                                        <label for="fmptype_">Тип описания</label>
                                        <input type="text" id="fmptype_" name="fmptype_" class="form-control"
                                               value="{{ $fmptypes->name }}" disabled>
                                        <input type="hidden" id="fmptype" name="fmptype" value="{{ $fmptypes->id }}">
                                    </div>
                                @else
                                    <div class="form-group col-lg-6 col-xs-12">
                                        <label for="fmptype">Тип описания</label>
                                        <select name="fmptype" id="fmptype" class="select2 form-control"
                                                data-placeholder="Выбор типа описания">
                                            @foreach($fmptypes as $fmptype)
                                                <option value="{{ $fmptype->id }}"
                                                        @if($loop->first) selected @endif>{{ $fmptype->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
                                                    @if($loop->first) selected @endif>{{ $code }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-12">
                                    <label for="name">Наименование</label>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror" id="name">
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </form>

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

@push('scripts.injection')
    <script>
        $(function () {
            //
        });
    </script>
@endpush
