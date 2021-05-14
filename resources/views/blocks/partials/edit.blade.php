<div class="form-group col-lg-3 col-xs-6">
    <label for="profile_code">Код нейропрофиля</label>
    <input type="text" id="profile_code" name="profile_code" class="form-control"
           value="{{ $profile->code }}" disabled>
</div>
<div class="form-group col-lg-6 col-xs-6">
    <label for="profile_name">Наименование нейропрофиля</label>
    <input type="text" id="profile_name" name="profile_name" class="form-control"
           value="{{ $profile->name }}" disabled>
</div>

<div class="form-group">
    <label for="description">Наименование</label>
    <input type="text" name="description" id="description"
           class="form-control @error('description') is-invalid @enderror"
           value="{{ $block->description }}"
           @if($show) disabled @endif >
</div>
