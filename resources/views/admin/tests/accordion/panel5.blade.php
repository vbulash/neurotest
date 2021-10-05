@php
    $branding = isset($content['branding']);
@endphp

<div class="checkbox mb-2">
    <label>
        <input type="checkbox" id="branding" name="branding" @if($branding) checked @endif>
        Тест имеет самостоятельный брендинг, отличный от встроенного
    </label>
</div>

<div class="form-group" id="branding_panel" style="display: none">
    <div class="form-group">
        <div class="input-group col-md-6 pl-0">
            <label for="background" class="form-control pl-0" style="border: none">Первичный цвет:</label>
            <div class="input-group-append">
                @php
                    $options = "";
                @endphp
                <div id="back-picker"></div>
                <input type="hidden" name="background-input" id="background-input"/>
            </div>
        </div>
        <div class="input-group col-md-6 pl-0">
            <label for="font-color" class="form-control pl-0" style="border: none">Цвет шрифта:</label>
            <div class="input-group-append">
                <div id="font-picker"></div>
                <input type="hidden" name="font-color-input" id="font-color-input"/>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="company-name-changer">Организация / компания:</label>
        <input type="text" name="company-name-changer" id="company-name-changer"
               class="form-control @error('company-name-changer') is-invalid @enderror"
               value="{{ $branding ? $content['branding']['company-name'] : env('APP_NAME') }}"
               data-parsley-required
        >
        <div class="invalid-feedback">Поле &laquo;Организация / компания&raquo; должно быть заполнено</div>
    </div>

    <div class="preview mt-5">
        <div class="form-group">
            <label for="preview_nav">Предпросмотр заголовка окна / фрейма:</label>
            <nav class="navbar navbar-dark bg-primary d-none d-lg-flex align-content-center custom-background mb-2"
                 id="preview_nav">
                <div class="navbar-brand custom-color">
                    <i class="fas fa-home"></i>
                    <span id="company-name-demo"></span>
                </div>
                <div class="navbar-text custom-color">
                    Наименование теста
                </div>
            </nav>
        </div>

        <label for="preview_button">Предпросмотр кнопки:</label><br/>
        <button class="btn btn-primary ml-0 custom-background custom-color" id="preview_button">Начать тестирование
        </button>
    </div>
</div>
