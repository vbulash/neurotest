@if($test->paid)
    @if($history->paid == '0')
        <h3>Вы получили краткую бесплатную версию результата тестирования.<br/>
            Оплатите полный результат тестирования и получите его по электронной почте.<br/>
            Ссылка оплаты:
        </h3>
        @php
            $rk = new \App\Http\Payment\Robokassa();
            $rk->setMail(true);
            $rk->setInvoice($history->id);
            $rk->setSum(500);
            $rk->setEmail($card['Электронная почта']);
            $rk->setTest(false);
            $rk->setDescription('Оплата полного результата нейротестирования');
            $button = $rk->getHTMLLink();
        @endphp
        <p>Нажимая ссылку оплаты ниже и выполняя оплату, вы соглашаетесь с условиями <a href="{{ route('player.policy', ['document' => 'oferta', 'mail' => false]) }}">публичного договора-оферты</a></p>
        {!! $button !!}
    @endif
@endif

@php
    switch ($card['Пол']) {
        case 'М':
            $greeting = 'Уважаемый';
            break;
        case 'Ж':
            $greeting = 'Уважаемая';
            break;
        default:
            $greeting = '';
    }
@endphp
<h1>{{ $greeting }} {{ $card['Имя'] . ' ' . $card['Фамилия'] }}</h1>
<p>Вы прошли тестирование по тесту &laquo;{{ $test->name }}&raquo; @if($history->paid == '1') и оплатили получение
    полных
    результатов тестирования @endif</p>

@if($test->options & \App\Models\Test::AUTH_FULL)
    <h1>Перед прохождением теста вы ввели анкетные данные:</h1>
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
            Полный результат вашего тестирования:
        @else
            Краткий результат вашего тестирования:
        @endif
    @else
        Результат вашего тестирования:
    @endif
</h1>

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
