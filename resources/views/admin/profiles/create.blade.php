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
                                @endif
                                <div class="form-group col-lg-3 col-xs-6">
                                    <label for="code">Код нейропрофиля</label>
                                    <input type="text" name="code"
                                           class="form-control @error('code') is-invalid @enderror" id="code">
                                </div>

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
                                                    @if($loop->first) selected @endif>{{ $code }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-12">
                                    <label for="name">Наименование</label>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror" id="name">
                                </div>
                                @if(!$embedded)
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
                                <div class="form-group btn-group mt-3 col-lg-3 col-xs-12" role="group"
                                     aria-label="Тип нейропрофиля">
                                    <input type="radio" class="btn-check" name="profiletype" id="profiletype1"
                                           autocomplete="off" checked value="1">
                                    <label class="btn btn-outline-primary" for="profiletype1">Нейрокластер</label>

                                    <input type="radio" class="btn-check" name="profiletype" id="profiletype2"
                                           autocomplete="off" value="0">
                                    <label class="btn btn-outline-primary" for="profiletype2">ФМП</label>
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
