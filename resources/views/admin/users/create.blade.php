@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Новый пользователь</h1>
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
                            <h3 class="card-title">&nbsp;</h3>
                        </div>
                        <!-- /.card-header -->

                        <form role="form" method="post" action="{{ route('users.store') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">ФИО</label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}">
                                </div>
                                <div class="row">
                                    <div class="form-group col-6 col-xs-12">
                                        <label for="email">Электронная почта</label>
                                        <input type="email" name="email" id="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email') }}">
                                    </div>
                                    <div class="form-group col-6 col-xs-12">
                                        <label for="phone">Телефон</label>
                                        <input type="tel" name="phone" id="phone"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               value="{{ old('phone') }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="password">Пароль</label>
                                        <div class="row">
                                            <div class="col-6 col-xs-12">
                                                <input type="text" name="password" id="password"
                                                       class="form-control @error('password') is-invalid @enderror"
                                                       value="{{ old('password') }}">
                                            </div>
                                            <div class="col-6 col-xs-12">
                                                <button type="button" name="get-password" id="get-password" class="btn btn-primary mb-3">Создать пароль</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="password_confirmation">Подтверждение пароля</label>
                                        <input type="text" name="password_confirmation" id="password_confirmation"
                                               class="form-control @error('password_confirmation') is-invalid @enderror"
                                               value="{{ old('password_confirmation') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="roles">Роли</label>
                                    <select name="roles[]" id="roles" class="select2 @error('roles') is-invalid @enderror" multiple="multiple"
                                            data-placeholder="Выбор ролей" style="width: 100%;">
                                        @foreach($roles as $role)
                                            <option>{{ $role }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="clients">Клиенты</label>
                                    <p>Пользователь будет иметь доступ только к клиентам, указанным ниже.<br/>
                                    Для этого пользователь должен иметь соответствующую роль или роли</p>
                                    <select name="clients[]" id="clients @error('clients') is-invalid @enderror" class="select2" multiple="multiple"
                                            data-placeholder="Выбор клиентов" style="width: 100%;">
                                        @foreach($clients as $id => $name)
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

@push('scripts.injection')
    <script>
        $(function () {
            $("#get-password").on("click", (event) => {
                event.preventDefault();
                $.ajax({
                    url: "{{ route('api.get.password') }}",
                    datatype: "json",
                    success: (helper) => {
                        $("#password").val(helper.password);
                    }
                });
            });
        });
    </script>
@endpush
