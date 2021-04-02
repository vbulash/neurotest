@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@if($show)
                            Анкета
                        @else
                            Редактирование
                        @endif пользователя &laquo;{{ $user->name }}&raquo;</h1>
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

                        <form role="form" method="post" action="{{ route('users.update', ['user' => $user->id]) }}"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">ФИО</label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ $user->name }}"
                                           @if($show) disabled @endif>
                                </div>
                                <div class="row">
                                    <div class="form-group col-6 col-xs-12">
                                        <label for="email">Электронная почта</label>
                                        <input type="email" name="email" id="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               value="{{ $user->email }}"
                                               @if($show) disabled @endif>
                                    </div>
                                    <div class="form-group col-6 col-xs-12">
                                        <label for="phone">Телефон</label>
                                        <input type="tel" name="phone" id="phone"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               value="{{ $user->phone }}"
                                               @if($show) disabled @endif>
                                    </div>
                                </div>
                                @if(!$show)
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <div class="mt-3 mb-3">
                                                <p>Оставьте поля пароля и подтверждения пароля пустыми, если не хотите
                                                    изменить пароль</p>
                                            </div>
                                            <label for="password">Пароль</label>
                                            <div class="row">
                                                <div class="col-6 col-xs-12">
                                                    <input type="text" name="password" id="password"
                                                           class="form-control @error('password') is-invalid @enderror"
                                                           @if($show) disabled @endif>
                                                </div>
                                                @if(!$show)
                                                    <div class="col-6 col-xs-12">
                                                        <button type="button" name="get-password"
                                                                id="get-password" class="btn btn-primary mb-3">
                                                            Перегенерировать пароль
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label for="password_confirmation">Подтверждение пароля</label>
                                            <input type="text" name="password_confirmation" id="password_confirmation"
                                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                                   @if($show) disabled @endif>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="roles">Роли</label>
                                    <select name="roles[]" id="roles"
                                            class="select2 @error('roles') is-invalid @enderror" multiple="multiple"
                                            style="width: 100%;" @if($show) disabled @endif>
                                        @foreach($roles as $role)
                                            <option
                                                @if(in_array($role, $user_roles->all())) selected @endif>{{ $role }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="clients">Клиенты</label>
                                    <p>Пользователь будет иметь доступ только к клиентам, указанным ниже.<br/>
                                        Для этого пользователь должен иметь соответствующую роль или роли</p>
                                    <select name="clients[]" id="clients"
                                            class="select2 @error('clients') is-invalid @enderror" multiple="multiple"
                                            style="width: 100%;" @if($show) disabled @endif>
                                        @foreach($clients as $id => $name)
                                            <option
                                                @if(array_key_exists($id, $user_clients->toArray())) selected @endif value="{{ $id }}">{{ $name }}</option>
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
