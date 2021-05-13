<div class="form-group mb-4"><h5>Способ предоставления результатов теста респонденту</h5></div>
<div class="checkbox mb-2">
    <label>
        <input type="checkbox" id="result1" name="result[]" value="{{ \App\Models\Test::SHOW_RESULTS }}"
               @if(intval($test->options) & \App\Models\Test::SHOW_RESULTS) checked @endif
               @if($show) disabled @endif
        > Показать результат тестирования на экране
    </label>
</div>
<div class="form-group mt-2">
    <label for="show_description">Для показа результата тестирования на экране будет использован тип описания</label>
    @php
        $show_description = \App\Models\FMPType::all()->pluck('name', 'id');
    @endphp
    <select name="show_description" id="show_description" class="select2 form-control col-lg-6 col-xs-12"
            @if($show) disabled @endif
    >
        @foreach($show_description as $key => $option)
            <option value="{{ $key }}"
                    @if($content['descriptions']['show'] == $key) selected @endif
            >{{ $option }}</option>
        @endforeach
    </select>
</div>

<div class="checkbox">
    <label>
        <input type="checkbox" id="result2" name="result[]" value="{{ \App\Models\Test::MAIL_RESULTS }}"
               @if(intval($test->options) & \App\Models\Test::MAIL_RESULTS) checked @endif
               @if($show) disabled @endif
        > Переслать результат тестирования по электронной почте
    </label>
</div>
<div class="form-group mt-2">
    <label for="mail_description">Для письма с результатом тестирования будет использован тип описания</label>
    <select name="mail_description" id="mail_description" class="select2 form-control col-lg-6 col-xs-12"
            @if($show) disabled @endif
    >
        @foreach($show_description as $key => $option)
            <option value="{{ $key }}"
                    @if($content['descriptions']['mail'] == $key) selected @endif
            >{{ $option }}</option>
        @endforeach
    </select>
</div>
