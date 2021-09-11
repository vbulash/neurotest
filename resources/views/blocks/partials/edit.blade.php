@if(!$block->profile && $show)
    <h5>Нейропрофиль не задан, перейдите в режим редактирования</h5>
@else
    <div class="form-group col-lg-6 col-xs-12">
        <label for="neuroprofile_id">Нейропрофиль</label>
        <select name="neuroprofile_id" id="neuroprofile_id" class="select2 form-control"
                data-placeholder="Выбор нейропрофиля">
            @foreach($profiles as $profile)
                <option value="{{ $profile->id }}"
                        @if($block->profile)
                            @if($profile->id == $block->profile->id) selected @endif
                        @elseif(!$show)
                            @if($loop->first) selected @endif
                        @endif
                        >
                    ({{ $profile->code }}) {{ $profile->name }}, тип: {{ $profile->fmptype->name }}</option>
            @endforeach
        </select>
    </div>
@endif

<div class="form-group">
    <label for="description">Наименование</label>
    <input type="text" name="description" id="description"
           class="form-control @error('description') is-invalid @enderror"
           value="{{ $block->description }}"
           @if($show) disabled @endif >
</div>
