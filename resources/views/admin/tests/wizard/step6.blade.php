<div class="checkbox mb-2">
    <label>
        <input type="checkbox" id="branding" name="branding">
        Тест имеет самостоятельный брендинг, отличный от встроенного
    </label>
</div>

<div id="branding_panel" style="display: none">
    <div class="row">
        @php
            $placeholder = "https://via.placeholder.com/300x300.png?text=Пусто";
        @endphp
        <div class="col-md-6">
            <div class="form-group">
                <label
                    for="logo-file">Логотип</label>
                <input type="file" id="logo-file"
                       name="logo-file"
                       class="image-file mb-4 form-control"
                       onchange="readImage(this)">
                <div>
                    <a href="javascript:void(0)" class="preview_anchor"
                       data-toggle="lightbox"
                       data-title="Логотип">
                        <img id="preview_logo-file"
                             src="{{ $placeholder }}" alt=""
                             class="logo-preview">
                    </a>
                    <a href="javascript:void(0)"
                       id="clear_logo-file"
                       data-preview="preview_logo-file"
                       class="btn btn-primary mb-3 image-clear">Очистить</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="input-group pl-0">
                    <label for="background" class="form-control pl-0" style="border: none">Первичный цвет:</label>
                    <div class="input-group-append">
                        <div id="back-picker"></div>
                        <input type="hidden" name="background-input" id="background-input"/>
                    </div>
                </div>
                <div class="input-group pl-0">
                    <label for="font-color" class="form-control pl-0" style="border: none">Цвет шрифта:</label>
                    <div class="input-group-append">
                        <div id="font-picker"></div>
                        <input type="hidden" name="font-color-input" id="font-color-input"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="company-name-changer">Организация / компания:</label>
        <input type="text" name="company-name-changer" id="company-name-changer"
               class="form-control @error('company-name-changer') is-invalid @enderror"
               value="{{ env('APP_NAME') }}"
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
