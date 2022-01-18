@php
    $robokassa = isset($content['robokassa']);
@endphp

<div class="checkbox mb-2">
    <label>
        <input type="checkbox" id="robokassa" name="robokassa"  @if($robokassa) checked @endif @if($show) disabled @endif>
        Тест имеет самостоятельную оплату, отличную от встроенной
    </label>
</div>

<div id="robokassa_panel" style="display: none">
    <div class="form-group">
        <label for="robokassa-merchant">Магазин Robokassa:</label>
        <input type="text" name="robokassa-merchant" id="robokassa-merchant"
               class="form-control @error('robokassa-merchant') is-invalid @enderror"
               value="{{ $robokassa ?  $content['robokassa']['merchant'] : '' }}">
    </div>

    <div class="form-group">
        <label for="robokassa-password">Пароль Robokassa:</label>
        <input type="password" name="robokassa-password" id="robokassa-password"
               class="form-control @error('robokassa-password') is-invalid @enderror"
               value="{{ $robokassa ?  $content['robokassa']['password'] : '' }}">
    </div>

    <div class="form-group">
        <label for="robokassa-sum">Сумма оплаты Robokassa:</label>
        <input type="text" name="robokassa-sum" id="robokassa-sum"
               class="form-control @error('robokassa-sum') is-invalid @enderror"
               value="{{ $robokassa ?  $content['robokassa']['sum'] : '500' }}">
    </div>
</div>

