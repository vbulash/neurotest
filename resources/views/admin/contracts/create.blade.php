@extends('admin.layouts.layout')

@section('back')
    <form action="{{ route('contracts.back', ['sid' => $sid]) }}" method="post">
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
                    <h1>Новый контракт
                        @if($client) клиента &laquo;{{ $client->name }}&raquo;@endif
                    </h1>
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
                            <h3 class="card-title">
                                При сохранении нового контракта произойдёт генерация мастер-ключа и лицензий с
                                персональными ключами
                            </h3>
                        </div>
                        <!-- /.card-header -->

                        <form role="form" method="post" action="{{ route('contracts.store', ['sid' => $sid]) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" name="client_id" id="client_id" value="{{ $client->id }}">
                                <div class="form-group col-6">
                                    <label for="number">Номер контракта</label>
                                    <input type="text" name="number" id="number"
                                           class="form-control @error('number') is-invalid @enderror">
                                </div>
                                <div class="row">
                                    <div class="form-group date col-6">
                                        <label for="end">Дата начала контракта</label>
                                        <input type="text" class="form-control @error('start') is-invalid @enderror"
                                               id="start" name="start">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                    <div class="form-group date col-6">
                                        <label for="end">Дата завершения контракта</label>
                                        <input type="text" class="form-control @error('end') is-invalid @enderror"
                                               id="end" name="end">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <label for="invoice">Номер оплаченного счета</label>
                                    <input type="text" name="invoice" id="invoice"
                                           class="form-control @error('invoice') is-invalid @enderror">
                                </div>
                                <div class="form-group col-6">
                                    <label for="license_count">Количество лицензий</label>
                                    <input type="number" name="license_count" id="license_count"
                                           class="form-control @error('name') is-invalid @enderror" min="1">
                                </div>
                                <div class="form-group col-10" data-toggle="tooltip"
                                     data-placement="top"
                                     title="Плеер теста будет работать только на указанном URL">
                                    <label for="url">URL страницы сайта клиента</label>
                                    <input type="text" name="url"
                                           class="form-control @error('url') is-invalid @enderror" id="url">
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
