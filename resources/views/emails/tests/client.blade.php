@if($test->paid)
    @if($history->paid == '0')
        <h3>Это краткая бесплатная версия результата тестирования.<br/>
            Респондент может оплатить получение полного результата тестирования по ссылке из индивидуального письма.<br/>
        </h3>
    @endif
@endif

@if(isset($card['Пол']) || isset($card['Имя']) || isset($card['Фамилия']))
    @php
        if(!isset($card['Пол'])) $card['Пол'] = '';
        if(!isset($card['Имя'])) $card['Имя'] = '';
        if(!isset($card['Фамилия'])) $card['Фамилия'] = '';
    @endphp
    <h1>{{ $card['Имя'] . ' ' . $card['Фамилия'] }}</h1>
    <p>Респондент прошел тестирование по тесту &laquo;{{ $test->name }}&raquo; @if($history->paid == '1') и оплатил получение
        полных результатов тестирования @endif</p>
@endif

@if($test->options & \App\Models\Test::AUTH_FULL)
    <h1>Перед прохождением респондент ввел анкетные данные:</h1>
    <ul>
        @foreach($card as $key => $value)
            @if(!$value) @continue @endif
            <li>{{ $key }} : {{ $value }}</li>
        @endforeach
    </ul>
@endif

<h1>
    @if($test->paid)
        @if($history->paid == '1')
            Полный результат тестирования респондента:
        @else
            Краткий результат тестирования респондента:
        @endif
    @else
        Результат тестирования респондента:
    @endif
</h1>
<h4>Наименование нейропрофиля: {{ $profile_name }}</h4>

@foreach($blocks  as $block)
    <h2>{{ $block->description }}</h2>
    @if($test->paid)
        @if($history->paid == '1')
            <div style="margin-left: 20px;">{!! $block->content !!}</div>
        @else
            <div style="margin-left: 20px;">
                @if($block->free)
                    {{ $block->free }}
                @else
                    {{--                    Содержание краткого / бесплатного блока...--}}
                    Информация доступна в полной версии
                @endif
            </div>
        @endif
    @else
        <div style="margin-left: 20px;">{!! $block->content !!}</div>
    @endif
@endforeach

<p style="margin-top: 40px;">
    С уважением,<br/>
    <a href="{{ env('BRAND_URL') }}" target="_blank">{{ env('BRAND_NAME') }}</a>
</p>
