<div class="form-group">
    <label for="title">Наименование теста</label>
    <input type="text" name="title" id="title"
           class="form-control @error('title') is-invalid @enderror" data-parsley-required>
    <div class="invalid-feedback">Поле &laquo;Наименование теста&raquo; должно быть заполнено</div>
</div>

<div class="form-group" id="contract-div">
    <label for="contract">Выберите контракт для теста</label>
    <select name="contract" id="contract" class="select2"
            data-placeholder="Выбор контракта">
        @foreach($contracts as $contract)
            <option value="{{ $contract->id }}"
            @if($loop->first) selected @endif>{{ $contract->number }} ({{ $contract->client->name }})</option>
        @endforeach
    </select>
</div>

<div class="form-group mt-4"><h4>Настройки</h4></div>

<div class="form-group mt-2">
    <label for="auth">Анкетирование в начале теста</label>
    <select name="auth" id="auth" class="select2 form-control">
        <option value="{{ \App\Models\Test::AUTH_GUEST }}" selected>Нет анкеты, только запрос разрешений</option>
        <option value="{{ \App\Models\Test::AUTH_FULL }}">Полная анкета, максимум информации о респонденте</option>
        <option value="{{ \App\Models\Test::AUTH_PKEY }}">Анкета не применима, запрашивается персональный ключ лицензии</option>
    </select>
</div>

<div class="checkbox mb-2">
    <label>
        <input type="checkbox" id="paid" name="paid">
        Результат тестирования имеет платную расширенную версию
    </label>
</div>
