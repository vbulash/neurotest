@extends('admin.layouts.layout')

@push('title') - Новый тип описания@endpush

@section('back')
	<a href="{{ url()->previous() }}" class="btn btn-primary">
		<i class="fas fa-chevron-left"></i> Назад
	</a>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Новый тип описания</h1>
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
                        <form role="form" method="post" action="{{ route('fmptypes.store', ['sid' => $sid]) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h3 class="card-title">
                                    Создание и привязка нейропрофилей станут доступны после сохранения типа описания
                                </h3>
                            </div>
                            <!-- /.card-header -->

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Наименование</label>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror" id="name">
                                </div>
                                <div class="form-group btn-group mt-3 col-lg-3 col-xs-12" role="group"
                                     aria-label="Тип описания">
                                    <input type="radio" class="btn-check" name="profiletype" id="profiletype1"
                                           autocomplete="off" checked value="1">
                                    <label class="btn btn-outline-primary" for="profiletype1">Нейрокластер</label>

                                    <input type="radio" class="btn-check" name="profiletype" id="profiletype2"
                                           autocomplete="off" value="0">
                                    <label class="btn btn-outline-primary" for="profiletype2">ФМП</label>
                                </div>
								<div class="form-group">
									<label for="active">Статус</label>
									<input type="text" class="form-control col-sm-3" id="active" name="active"
										   value="Неактивный" disabled
									>
								</div>
								<div class="form-group">
									<label for="limit">Необходимо нейропрофилей</label>
									<input type="number" class="form-control col-sm-3" id="limit" name="limit"
										   value="16"
									>
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
