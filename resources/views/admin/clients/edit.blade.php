@extends('admin.layouts.layout')

@section('back')
	<form action="{{ route('clients.back', ['sid' => $sid]) }}" method="post">
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
					<h1>@if($show) Анкета @else Редактирование @endif клиента &laquo;{{ $client->name }}&raquo;</h1>
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
								Анкета клиента
							</h3>
						</div>
						<!-- /.card-header -->

						<form role="form" method="post"
							  @if($show)
							  action=""
							  @else
							  action="{{ route('clients.update', ['client' => $client->id, 'sid' => $sid]) }}"
							  @endif
							  enctype="multipart/form-data">
							@csrf
							@method('PUT')
							<div class="card-body">
								<div class="row">
									<div class="col-2 col-xs-6">
										<div class="form-group">
											<label for="client_id">ID клиента</label>
											<input type="text" name="client_id"
												   class="form-control" id="client_id"
												   value="{{ $client->id }}" disabled>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="name">Наименование</label>
									<input type="text" name="name"
										   class="form-control @error('name') is-invalid @enderror" id="name"
										   value="{{ $client->name }}" @if($show) disabled @endif>
								</div>
								<div class="form-group">
									<label for="inn">ИНН</label>
									<input type="text" name="inn"
										   class="form-control @error('inn') is-invalid @enderror" id="inn"
										   value="{{ $client->inn }}" @if($show) disabled @endif>
								</div>
								<div class="form-group">
									<label for="ogrn">ОГРН / ОГРНИП</label>
									<input type="text" name="ogrn"
										   class="form-control @error('ogrn') is-invalid @enderror" id="ogrn"
										   value="{{ $client->ogrn }}" @if($show) disabled @endif>
								</div>
								<div class="form-group">
									<label for="address">Адрес</label>
									<textarea name="address" id="address" rows="3"
											  class="form-control @error('address') is-invalid @enderror"
											  @if($show) disabled @endif>{{ $client->address }}</textarea>
								</div>
								@if($count && $first)
									<div class="row">
										<div class="form-group col-6 col-xs-12">
											<label for="number">Номер контракта</label>
											<input type="text" name="number"
												   class="form-control" id="number"
												   value="{{ $first->number }}" disabled>
										</div>
										<div class="form-group col-6 col-xs-12">
											<label for="invoice">Номер счета</label>
											<input type="text" name="invoice"
												   class="form-control" id="invoice"
												   value="{{ $first->invoice }}" disabled>
										</div>
									</div>
								@endif
								<div class="form-group">
									<label for="client_manager">Клиентский менеджер (Persona)</label>
									<input type="text" name="client_manager"
										   class="form-control" id="client_manager"
										   disabled>
								</div>
								<div class="form-group">
									<label for="responsive">Менеджер со стороны клиента</label>
									<input type="text" name="responsive"
										   class="form-control" id="responsive"
										   disabled>
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

					<div class="card">
						<div class="card-header">
							<h3 class="card-title">
								Контракты клиента &laquo;{{ $client->name }}&raquo;
							</h3>
						</div>
						<!-- /.card-header -->
						<div class="card-body">
							<a href="{{ route('client.contracts.create', ['client' => $client->id, 'sid' => $sid]) }}"
							   class="btn btn-primary mb-3">Добавить контракт</a>
							@if ($count)
								<div class="table-responsive">
									<table class="table table-bordered table-hover text-nowrap" id="contracts_table"
										   style="width: 100%;">
										<thead>
										<tr>
											<th>Номер контракта</th>
											<th>Дата начала</th>
											<th>Дата завершения</th>
											<th>Количество лицензий</th>
											<th>Статус</th>
											<th>Действия</th>
										</tr>
										</thead>
									</table>
								</div>
							@else
								<p>Контрактов пока нет...</p>
							@endif
						</div>
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

@once
	@if ($count)
		@push('styles')
			<link rel="stylesheet" href="{{ asset('assets/admin/plugins/datatables/datatables.css') }}">
		@endpush

		@push('scripts')
			<script src="{{ asset('assets/admin/plugins/datatables/datatables.js') }}"></script>
		@endpush
	@endif
@endonce

@if($count)
	@push('scripts.injection')
		<script>
			$(function () {
				$('#contracts_table').DataTable({
					language: {
						"url": "{{ asset('assets/admin/plugins/datatables/lang/ru/Russian.json') }}"
					},
					processing: true,
					serverSide: true,
					ajax: '{!! route('contracts.index.data', ['client' => $client->id, 'sid' => $sid]) !!}',
					responsive: true,
					columns: [
						{data: 'number', name: 'number', responsivePriority: 1},
						{data: 'start', name: 'start', responsivePriority: 2},
						{data: 'end', name: 'end', responsivePriority: 2},
						{data: 'license_count', name: 'license_count', responsivePriority: 4},
						{data: 'status', name: 'status', responsivePriority: 3},
						{
							data: 'action',
							name: 'action',
							sortable: false,
							responsivePriority: 1,
							className: 'no-wrap dt-actions'
						}
					]
				});
			});
		</script>
	@endpush
@endif
