@extends('front.layouts.layout')

@push('title') - Тест &laquo;{{ $test->name }}&raquo;@endpush

@push('testname'){{ $test->name }}@endpush

@section('content')
    <form method="get" action="{{ route('player.full') }}">
        @csrf
        <input type="hidden" name="sid" value="{{ $sid }}">
        <h5 class="mb-4">Информация для анкеты тестируемого</h5>
        <p>Звездочкой (*) выделены поля, обязательные для заполнения</p>
        @php
            $content = json_decode($test->content, true);
            $test_content = $content['card'];
        @endphp
        @foreach(\App\Models\Test::$ident as $group)
            <div class="form-group mb-4">
            @foreach($group as $element)
                @php
                    $name = $element['name'];
                    $title = $element['label'];
                    $type = $element['type'];
                    $value = $element['value'];
                    $required = $element['required'];

                    $actual = ($required | key_exists($name, $test_content));
                @endphp
                @if(!$actual)
                    @continue
                @endif
                @switch($type)
                    @case("text")
                    @case("number")
                    @case("email")
                    @case("phone")
                        <div class="form-group col-md-6">
                            <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
                                   class="form-control mb-2 @error("{{ $name }}") is-invalid @enderror"
                                   placeholder="{{ $title }}@if($required) * @endif"
                                   @if($required) required @endif
                                   @if($value) value="{{ $value }}" @endif>
                        </div>
                        @break
                    @case("date")
                        <div class="input-group date col-md-6" data-provide="datepicker" style="width: 50%;">
                            <input type="text" name="{{ $name }}" id="{{ $name }}"
                                   class="form-control mb-2 @error("{{ $name }}") is-invalid @enderror"
                                   placeholder="{{ $title }}@if($required) * @endif"
                                   @if($required) required @endif
                                   @if($value) value="{{ $value }}" @endif>
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                        @break
                    @case("select")
                        <div class="form-group col-md-6">
                            <label for="{{ $name }}">{{ $title }} @if($required) * @endif</label>
                            <select name="{{ $name }}" id="{{ $name }}" class="select2"
                                    @if($required) required @endif
                                    style="width: 100%;">
                                @foreach($element['cases'] as $option)
                                    <option value="{{ $option['value'] }}"
                                            @if($option['value'] == $element['value']) selected @endif>
                                        {{ $option['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @break
                @endswitch
            @endforeach
            </div>
        @endforeach

        <div class="form-group mt-4 mb-2">
            <p>Для начала тестирования вам необходимо установить отметки по 2 политикам ниже:</p>
        </div>

        <div class="checkbox mt-2 mb-2">
            <label>
                <input type="checkbox" class="policy" id="policy_privacy" name="privacy_policy">
                Я принимаю положения <a href="{{ route('player.policy', ['document' => 'privacy']) }}">Политики конфиденциальности</a>
            </label>
        </div>
        <div class="checkbox mt-2 mb-2">
            <label>
                <input type="checkbox" class="policy" id="policy_personal" name="privacy_personal">
                Я согласна / согласен на обработку моих персональных данных в соответствии с <a href="{{ route('player.policy', ['document' => 'personal']) }}">Политикой в отношении обработки персональных данных</a>
            </label>
        </div>

        <button type="submit" id="start_test" class="btn btn-primary btn-lg mt-2"  disabled>Начать тестирование</button>
    </form>

@endsection

@push('scripts.injection')
    <script>
        document.querySelectorAll(".policy").forEach((doc) => {
            doc.addEventListener('change', event => {
                let privacy = document.getElementById('policy_privacy');
                let personal = document.getElementById('policy_personal');

                let allowed = (privacy.checked && personal.checked);

                let submit = document.getElementById('start_test');
                submit.disabled = !allowed;
            });
        });

        //document.addEventListener("DOMContentLoaded", () => {
            //
        //});
    </script>
@endpush
