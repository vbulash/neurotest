<div class="form-group mb-4"><h5>Информирование клиента о тестировании</h5></div>
<div class="checkbox mb-2">
	<label>
		<input type="checkbox" name="result[]" value="{{ \App\Models\Test::MAIL_CLIENT }}"
			   @if(intval($test->options) & \App\Models\Test::MAIL_CLIENT) checked @endif
			   @if($show) disabled @endif
		> Переслать клиенту письмо с итогами тестирования респондента
	</label>
</div>
<div class="form-group mt-2">
	@php
		$client_description = \App\Models\FMPType::all()->pluck('name', 'id');
	@endphp
	<label for="client_description">Для письма с результатом тестирования будет использован тип описания:</label>
	<select name="client_description" id="client_description" class="select2 form-control col-lg-6 col-xs-12"
			@if($show) disabled @endif
	>
		@foreach($client_description as $key => $option)
			<option value="{{ $key }}"
					@if(isset($content['descriptions']['client']))
						@if($content['descriptions']['client'] == $key) selected @endif
					@endif
			>{{ $option }}</option>
		@endforeach
	</select>
</div>
