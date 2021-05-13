<div class="form-group mb-4"><h5>Способ предоставления результатов теста отдельного респондента клиенту</h5></div>
@php
    $show_description = \App\Models\FMPType::all()->pluck('name', 'id');
@endphp
<div class="form-group mt-2">
    <label for="client_description">В личном кабинете клиента результаты тестирования отдельного респондента будут показаны в соответствии с типом описани</label>
    <select name="client_description" id="client_description" class="select2 form-control col-lg-6 col-xs-12"
            @if($show) disabled @endif
    >
        @foreach($show_description as $key => $option)
            <option value="{{ $key }}"
                    @if($content['descriptions']['client'] == $key) selected @endif
            >{{ $option }}</option>
        @endforeach
    </select>
</div>
