<div class="form-group mb-4"><h5>Выбор механики нейротеста</h5></div>
<div class="form-check">
    <input class="form-check-input" type="radio" name="mechanics" id="mechanics1"
           value="{{ \App\Models\Test::IMAGES2 }}" checked>
    <label class="form-check-label" for="mechanics1">
        2 изображения
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" type="radio" name="mechanics" id="mechanics2"
           value="{{ \App\Models\Test::IMAGES4 }}">
    <label class="form-check-label" for="mechanics2">
        4 изображения
    </label>
</div>

<div class="form-group mt-4 mb-4"><h5>Дополнительные механики нейротеста</h5></div>
<div class="form-check">
    <input class="form-check-input" type="checkbox" value="{{ \App\Models\Test::EYE_TRACKING }}" name="aux_mechanics[]"
           id="aux_mechnics1">
    <label class="form-check-label" for="aux_mechanics1">
        Eye-tracking
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" type="checkbox" value="{{ \App\Models\Test::MOUSE_TRACKING }}"
           name="aux_mechanics[]" id="aux_mechanics2">
    <label class="form-check-label" for="aux_mechanics2">
        Mouse-tracking
    </label>
</div>
