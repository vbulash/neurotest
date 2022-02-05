@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@if($show)
                            Карточка
                        @else
                            Редактирование
                        @endif роли &laquo;{{ $role->name }}&raquo;</h1>
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
                                @if(in_array($role->name, $predefined) && !$show)
                                    В предопределенных ролях допустимо изменять только набор прав; переименовывать
                                    предопределенные роли нельзя
                                @endif
                            </h3>
                        </div>
                        <!-- /.card-header -->

                        <form role="form" method="post" action="{{ route('roles.update', ['role' => $role->id, 'sid' => $sid]) }}"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="guard_name" id="guard_name" value="web">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Наименование роли</label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ $role->name }}"
                                           @if(in_array($role->name, $predefined)) disabled @endif>
                                </div>
                                <div class="form-group">
                                    <label for="permissions">Разрешения роли (права)</label>
                                    <select name="permissions[]" id="permissions" class="select2" multiple="multiple"
                                            data-placeholder="Выбор разрешений" style="width: 100%;"
                                            @if($show) disabled @endif>
                                        @php
                                            $prevChapter = '';
                                        @endphp
                                        @foreach($permissions as $permission)
                                            @if($permission->chapter != $prevChapter)
                                                @if($prevChapter)
                                                    @php
                                                        echo "</optgroup>";
                                                    @endphp
                                                @endif
                                                <optgroup label="{{ $permission->chapter }}">
                                                    @php
                                                        /** @noinspection PhpUndefinedVariableInspection */ $prevChapter = $permission->chapter;
                                                    @endphp;
                                                    @endif
                                                    <option
                                                        value="{{ $permission->name }}"
                                                        @if(in_array($permission->name, $role_permissions)) selected @endif
                                                    >{{ $permission->description }}</option>
                                                    @endforeach
                                                </optgroup>
                                    </select>
                                </div>
                                <div class="checkbox col-4">
                                    <label>
                                        <input type="checkbox" id="wildcards" name="wildcards"
                                               @if($role->wildcards) checked @endif
                                               @if(in_array($role->name, $predefined)) disabled @endif> Права распространяются
                                        на работу с конкретными клиентами
                                    </label>
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
