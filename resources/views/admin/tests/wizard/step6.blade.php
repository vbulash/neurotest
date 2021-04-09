<div class="form-group mb-4"><h5>Способ предоставления результатов теста респонденту</h5></div>
<div class="checkbox mb-2">
    <label>
        <input type="checkbox" id="result1" name="result[]" value="{{ \App\Models\Test::SHOW_RESULTS }}" checked>
        Показать результат тестирования на экране
    </label>
</div>
<div class="checkbox">
    <label>
        <input type="checkbox" id="result2" name="result[]" value="{{ \App\Models\Test::MAIL_RESULTS }}"> Переслать
        результат тестирования по электронной почте
    </label>
</div>
