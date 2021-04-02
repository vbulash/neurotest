@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@if($show) Просмотр @else Редактирование или пролонгация @endif контракта &laquo;{{ $contract->number }}&raquo;
                        клиента &laquo;{{ $contract->client->name }}&raquo;</h1>
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
                            <h3 class="card-title">Статус контракта: <strong>{{ $status }}</strong></h3>
                        </div>
                        <!-- /.card-header -->

                        <form role="form" method="post"
                              @if($show)
                              action=""
                              @else
                              action="{{ route('contracts.update', ['contract' => $contract->id]) }}"
                              @endif
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <input type="hidden" name="contract_id" id="contract_id" value="{{ $contract->id }}">
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="number">Номер контракта</label>
                                        <input type="text" name="number" id="number"
                                               class="form-control @error('number') is-invalid @enderror"
                                               value="{{ $contract->number }}" @if($show) disabled @endif>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group date col-6">
                                        <label for="start">Дата начала контракта</label>
                                        <input type="text" class="form-control @error('start') is-invalid @enderror"
                                               id="start" name="start" value="{{ $contract->start }}"
                                               @if($show) disabled @endif>
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                    <div class="form-group date col-6">
                                        <label for="end">Дата завершения контракта</label>
                                        <input type="text" class="form-control @error('end') is-invalid @enderror"
                                               id="end" name="end" value="{{ $contract->end }}"
                                               @if($show) disabled @endif>
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <label for="invoice">Номер оплаченного счета</label>
                                    <input type="text" name="invoice" id="invoice"
                                           class="form-control @error('invoice') is-invalid @enderror"
                                           value="{{ $contract->invoice }}" @if($show) disabled @endif>
                                </div>
                                <div class="card mt-4 mb-4 p-4">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="mkey">Мастер-ключ</label>
                                                <input type="text" name="mkey" id="mkey"
                                                       class="form-control @error('mkey') is-invalid @enderror"
                                                       value="{{ $contract->mkey }}" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label for="license_count">Количество лицензий</label>
                                                <input type="number" name="license_count" id="license_count"
                                                       class="form-control @error('license_count') is-invalid @enderror"
                                                       min="1"
                                                       value="{{ $contract->license_count }}"
                                                       @if($show) disabled @endif>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group ml-4">
                                                <label>Статистика по лицензиям:</label>
                                                <table class="licenses_statistics">
                                                    <tr>
                                                        <td class="key">Свободные лицензии:</td>
                                                        <td class="value">{{ $statistics['free'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="key">Используемые лицензии:</td>
                                                        <td class="value">{{ $statistics['using'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="key">Использованные лицензии:</td>
                                                        <td class="value">{{ $statistics['used'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="key">Поврежденные лицензии:</td>
                                                        <td class="value">{{ $statistics['broken'] }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-10" data-toggle="tooltip"
                                         data-placement="top"
                                         title="Плеер теста будет работать только на указанном URL">
                                        <label for="url">URL страницы сайта клиента</label>
                                        <input type="text" name="url"
                                               class="form-control @error('url') is-invalid @enderror" id="url"
                                               value="{{ $contract->url }}" @if($show) disabled @endif>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            @if(!$show)
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                </div>
                            @endif
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
