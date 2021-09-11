@extends('front.layouts.layout')

@push('title') - Тест &laquo;{{ $test->name }}&raquo;@endpush

@push('testname') Тест &laquo;{{ $test->name }}&raquo;@endpush

@push('step_description')
    Результат тестирования
@endpush

@section('content')
    @if($test->paid)
        <h5 class="mt-4">Вы видите краткую бесплатную версию результатов тестирования.<br/>
            Оплатите полный результат тестирования и получите его по электронной почте.<br/>
        </h5>
        @php
            $rk = new \App\Http\Payment\Robokassa();
            $rk->setMail(false);
            $rk->setInvoice($history->id);
            $rk->setSum(500);
            $rk->setEmail($card->email);
            $rk->setTest(false);
            $rk->setDescription('Оплата полного результата нейротестирования');
            $button = $rk->getHTMLButton();
            //$button = $rk->getFrameButton();
        @endphp
        <p>Нажимая кнопку оплаты ниже и выполняя оплату, вы соглашаетесь с условиями <a href="{{ route('player.policy', ['document' => 'oferta']) }}" target="_blank">публичного договора-оферты</a></p>
        {!! $button !!}
    @endif

    <h1>Результат тестирования</h1>
    @forelse($blocks  as $block)
        <h2>{{ $block->description }}</h2>
        @if($test->paid)
            <div style="margin-left: 20px;">
                @if($block->free)
                    {{ $block->free }}
                @else
{{--                    Содержание краткого / бесплатного блока...--}}
                    Информация доступна в полной версии
                @endif
            </div>
        @else
            <div style="margin-left: 20px;">{!! $block->content !!}</div>
        @endif
    @empty
        <h2>Настройка теста не завершена.<br/>
            Нет блоков описаний, соответствующих коду нейропрофиля &laquo;{{ $profile_code }}&raquo;</h2>
    @endforelse
@endsection

@push('scripts.injection')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            //
        });
    </script>
@endpush
