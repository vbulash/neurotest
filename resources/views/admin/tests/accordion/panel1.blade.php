<div class="form-group mb-4" id="auth-full" style="display:none;">
    <h5 class="mb-4">Сведения, которые будут отображаться в анкете в начале теста</h5>
    @foreach(\App\Models\Test::$ident as $group)
        <div class="mb-4">
            @foreach($group as $control)
                <div class="checkbox mb-2">
                    <label>
                        <input type="checkbox" id="ident_{{ $control['name'] }}" name="ident_{{ $control['name'] }}"
                               @if($show || $control['required']) disabled @endif
                               @if($control['required']) checked @endif
                               @if(array_key_exists($control['name'], $content['card'])) checked @endif
                        >
                        {{ $control['label'] }}
                    </label>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
<div class="form-group mb-4" style="display:none;" id="auth-guest">
    Сбор сведений о респондентах не требуется
</div>
<div class="form-group mb-4" style="display:none;" id="auth-pkey">
    Сбор сведений о респондентах не требуется.<br/>
    В начале начале тестирования будет запрашиваться персональный ключ (pkey)
</div>
