<div class="form-group mb-4"><h5>Отметьте сведения, которые будут отображаться в анкете в начале теста</h5></div>
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
