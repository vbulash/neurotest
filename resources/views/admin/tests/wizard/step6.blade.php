<div class="form-group mb-4"><h5>Способ предоставления результатов теста респонденту</h5></div>
<div class="checkbox mb-2">
    <label>
        <input type="checkbox" id="result1" name="result[]" value="{{ \App\Models\Test::SHOW_RESULTS }}" checked>
        Показать результат тестирования на экране
    </label>
</div>
<div class="form-group mt-2">
    <label>Размер результата тестирования при показе на экране</label>
    @php
        $show_options = [
            \App\Models\Test::SHOW_SHORT => 'Краткий текст',
            \App\Models\Test::SHOW_MIDDLE => 'Средний текст',
            \App\Models\Test::SHOW_FULL => 'Полный текст'
        ];
    @endphp
    <select name="show_options" id="show_options" class="select2 form-control col-lg-6 col-xs-12">
        @foreach($show_options as $key => $option)
            <option value="{{ $key }}" @if($loop->first) selected @endif>{{ $option }}</option>
        @endforeach
    </select>
</div>

<div class="checkbox">
    <label>
        <input type="checkbox" id="result2" name="result[]" value="{{ \App\Models\Test::MAIL_RESULTS }}"> Переслать
        результат тестирования по электронной почте
    </label>
</div>
<div class="form-group mt-2">
    <label>Размер информации в письме с результатом тестирования</label>
    @php
        $mail_options = [
            \App\Models\Test::MAIL_SHORT => 'Краткий текст',
            \App\Models\Test::MAIL_MIDDLE => 'Средний текст',
            \App\Models\Test::MAIL_FULL => 'Полный текст'
        ];
    @endphp
    <select name="mail_options" id="mail_options" class="select2 form-control col-lg-6 col-xs-12">
        @foreach($mail_options as $key => $option)
            <option value="{{ $key }}" @if($loop->first) selected @endif>{{ $option }}</option>
        @endforeach
    </select>
</div>
