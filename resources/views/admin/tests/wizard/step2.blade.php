<div class="form-group mb-4" id="auth-full" style="display:none;">
    <h5 class="mb-4">Отметьте сведения, которые будут отображаться в анкете в начале
        теста</h5>
    <div id="ident-area">
        @foreach(\App\Models\Test::$ident as $group)
            <div class="ident-group mb-4">
                @foreach($group as $control)
                    <div class="checkbox mb-2">
                        <label>
                            <input type="checkbox" id="ident_{{ $control['name'] }}" name="ident_{{ $control['name'] }}"
                                   @if($control['actual']) checked @endif>
                            {{ $control['label'] }}
                        </label>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
<div class="form-group mb-4" style="display:none;" id="auth-guest">
    Сбор сведений о респондентах не требуется.<br/>
    Перейдите к следующему шагу настройки нового теста
</div>
<div class="form-group mb-4" style="display:none;" id="auth-pkey">
    Сбор сведений о респондентах не требуется.<br/>
    В начале начале тестирования будет запрашиваться персональный ключ (pkey).<br/>
    Перейдите к следующему шагу настройки нового теста
</div>
