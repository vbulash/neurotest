@extends('admin.layouts.layout')

@push('title') - @if($show) Анкета @else Редактирование @endif истории {{ $history->id }}@endpush

@section('back')
    <form action="{{ route('history.back', ['sid' => $sid]) }}" method="post">
        @csrf
        <button type="submit" id="back_btn" name="back_btn" class="btn btn-primary">
            <i class="fas fa-chevron-left"></i> Назад
        </button>
    </form>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@if($show) Анкета @else Редактирование @endif истории {{ $history->id }}</h1>
                </div>
                {{--                <div class="col-sm-6">--}}
                {{--                    <ol class="breadcrumb float-sm-right">--}}
                {{--                        <li class="breadcrumb-item"><a href="#">Home</a></li>--}}
                {{--                        <li class="breadcrumb-item active">Blank Page</li>--}}
                {{--                    </ol>--}}
                {{--                </div>--}}
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                    {{--                        <div class="card-header">--}}
                    {{--                            <h3 class="card-title"></h3>--}}
                    {{--                        </div>--}}
                    <!-- /.card-header -->

                        <form role="form" method="post"
                              @if($show)
                              action=""
                              @else
                              action="{{ route('history.update', ['history' => $history->id, 'sid' => $sid]) }}"
                              @endif
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                @if(!$show) <p>Звездочкой (*) выделены поля, обязательные для заполнения</p> @endif
                                @php
                                    $card = json_decode($history->card, true);
                                @endphp
                                @foreach(\App\Models\Test::$ident as $group)
                                    <div class="form-group mb-4">
                                        @foreach($group as $element)
                                            @php
                                                $name = $element['name'];
                                                $title = $element['label'];
                                                $type = $element['type'];
                                                $required = ($show ? false : $element['required']);
                                                $actual = key_exists($name, $card);

                                                if($actual) {
                                                    $value = $card[$name];
                                                    $element['value'] = $value;
                                                    if(!$value) $actual = false;
                                                }
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
                                                    <label for="{{ $name }}">{{ $title }} @if($required)
                                                            * @endif</label>
                                                    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
                                                           class="form-control mb-2 @error("{{ $name }}") is-invalid @enderror"
                                                           placeholder="{{ $title }}@if($required) * @endif"
                                                           @if($required) required @endif
                                                           @if($value) value="{{ $value }}" @endif
                                                           @if($show) disabled @endif
                                                    >
                                                </div>
                                                @break
                                                @case("date")
                                                <div class="input-group date col-md-6" data-provide="datepicker"
                                                     style="width: 50%;">
                                                    <label for="{{ $name }}">{{ $title }} @if($required)
                                                            * @endif</label>
                                                    <input type="text" name="{{ $name }}" id="{{ $name }}"
                                                           class="form-control mb-2 @error("{{ $name }}") is-invalid @enderror"
                                                           placeholder="{{ $title }}@if($required) * @endif"
                                                           @if($required) required @endif
                                                           @if($value) value="{{ $value }}" @endif
                                                           @if($show) disabled @endif
                                                    >
                                                    <div class="input-group-addon">
                                                        <span class="glyphicon glyphicon-th"></span>
                                                    </div>
                                                </div>
                                                @break
                                                @case("select")
                                                <div class="form-group col-md-6">
                                                    <label for="{{ $name }}">{{ $title }} @if($required)
                                                            * @endif</label>
                                                    <select name="{{ $name }}" id="{{ $name }}" class="select2"
                                                            @if($required) required @endif
                                                            style="width: 100%;"
                                                            @if($show) disabled @endif
                                                    >
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

                                <div class="form-group col-md-6">
                                    <label for="code">Вычисленный код нейропрофиля</label>
                                    <input type="text" name="code"
                                           class="form-control @error('code') is-invalid @enderror" id="name"
                                           value="{{ $history->code }}" disabled>
                                </div>

                                <div class="form-group checkbox mt-4 mb-2">
                                    <label>
                                        <input type="checkbox" id="paid" name="paid"
                                               @if($history->paid) checked @endif
                                               @if($show) disabled @endif
                                        >
                                        Оплата полного результата тестирования выполнена
                                    </label>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            @if(!$show)
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" id="submit">Сохранить</button>
                                </div>
                            @endif
                        </form>

                    </div>
                    <!-- /.card -->

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@push('scripts.injection')
    <script>
        //
    </script>
@endpush

