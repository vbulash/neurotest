<div class="form-group mb-4"><h5>Информирование клиента о тестировании</h5></div>
<div class="checkbox mb-2">
	<label>
		<input type="checkbox" name="result[]" value="{{ \App\Models\Test::MAIL_CLIENT }}"
			   @if(intval($test->options) & \App\Models\Test::MAIL_CLIENT) checked @endif
			   @if($show) disabled @endif
		> Переслать клиенту письмо с итогами тестирования респондента
	</label>
</div>
