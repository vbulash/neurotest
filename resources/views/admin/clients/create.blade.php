@extends('admin.layouts.layout')

@section('back')
    <form action="{{ route('clients.back') }}" method="post">
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
                    <h1>Новый клиент</h1>
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
                            Создание и привязка контрактов станут доступны после сохранения клиента.
                            </h3>
                        </div>
                        <!-- /.card-header -->

                        <form role="form" method="post" action="{{ route('clients.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Наименование</label>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror" id="name"
                                           placeholder="Наименование клиента">
                                </div>
                                <div class="form-group">
                                    <label for="inn">ИНН</label>
                                    <input type="text" name="inn"
                                           class="form-control @error('inn') is-invalid @enderror" id="inn"
                                           placeholder="ИНН клиента">
                                </div>
                                <div class="form-group">
                                    <label for="ogrn">ОГРН / ОГРНИП</label>
                                    <input type="text" name="ogrn"
                                           class="form-control @error('ogrn') is-invalid @enderror" id="ogrn"
                                           placeholder="ОГРН / ОГРНИП клиента">
                                </div>
                                <div class="form-group">
                                    <label for="address">Адрес</label>
                                    <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror" placeholder="Юридический адрес клиента"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="manager">Клиентский менеджер (Persona)</label>
                                    <select name="manager" id="manager" class="select2"
                                            style="width: 100%;">
                                        @foreach($managers as $name => $id)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
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
