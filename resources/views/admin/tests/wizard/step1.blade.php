<div class="form-group has-validation">
    <label for="title">Наименование теста</label>
    <input type="text" name="title" id="title"
           class="form-control @error('title') is-invalid @enderror" required>
    <div class="invalid-feedback">Поле &laquo;Наименование теста&raquo; должно быть заполнено</div>
</div>

<div class="form-group">
    <label>Тип теста</label>
    <select name="kind" id="kind" class="select2 form-control col-lg-6 col-xs-12"
            data-placeholder="Выбор типа теста">
        @foreach(App\Models\Test::types as $key => $value)
            <option value="{{ $key }}"
                    @if($loop->first) selected @endif>{{ $value }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="timeout">Максимальное время прохождения теста, в минутах (0 - тест не ограничен по
        времени)</label>
    <input type="number" name="timeout" id="timeout" min="0"
           class="form-control @error('timout') is-invalid @enderror col-lg-6 col-xs-12"
           value="0">
</div>
<div class="form-group" id="contract-div">
    <label>Выберите контракт для теста</label>
    <select name="contract" id="contract" class="select2 col-lg-6 col-xs-12"
            data-placeholder="Выбор контракта">
        @foreach($contracts as $contract)
            <option value="{{ $contract->id }}">{{ $contract->number }} ({{ $contract->client->name }})</option>
        @endforeach
    </select>
</div>

<div class="form-group mt-4"><h4>Настройки</h4></div>

<div class="form-group mt-2">
    <label>Анкетирование в начале теста</label>
    <select name="auth" id="auth" class="select2 form-control col-lg-6 col-xs-12">
        <option value="{{ \App\Models\Test::AUTH_GUEST }}" selected>Нет анкеты, только запрос разрешений</option>
        <option value="{{ \App\Models\Test::AUTH_FULL }}">Полная анкета, максимум информации о респонденте</option>
        <option value="{{ \App\Models\Test::AUTH_PKEY }}">Анкета не применима, запрашивается персональный ключ лицензии</option>
    </select>
</div>
