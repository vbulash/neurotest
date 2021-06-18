@if($embedded)
    <div class="form-group col-lg-3 col-xs-6">
        <label for="profile_code">Код нейропрофиля</label>
        <input type="text" id="profile_code" name="profile_code" class="form-control"
               value="{{ $profiles->code }}" disabled>
    </div>
    <div class="form-group col-lg-6 col-xs-6">
        <label for="profile_name">Наименование нейропрофиля</label>
        <input type="text" id="profile_code" name="profile_code" class="form-control"
               value="{{ $profiles->name }}" disabled>
    </div>
    <input type="hidden" name="neuroprofile_id" id="profile_id" value="{{ $profiles->id }}">
@else
    <div class="form-group col-lg-6 col-xs-12">
        <label for="neuroprofile_id">Нейропрофиль</label>
        <select name="neuroprofile_id" id="neuroprofile_id" class="select2 form-control"
                data-placeholder="Выбор нейропрофиля">
            @foreach($profiles as $profile)
                <option value="{{ $profile->id }}"
                        @if($loop->first) selected @endif>
                    ({{ $profile->code }}) {{ $profile->name }}, тип: {{ $profile->fmptype->name }}
                </option>
            @endforeach
        </select>
    </div>
@endif

<div class="form-group">
    <label for="description">Краткое наименование</label>
    <input type="text" name="description" id="description"
           class="form-control @error('description') is-invalid @enderror">
</div>
