<div class="checkbox mb-2">
    <label>
		@php
			$branding = (isset($content['branding']) ? $content['branding'] : null);
		@endphp
        <input type="checkbox" id="branding" name="branding" @if($branding) checked @endif @if($show) disabled @endif>
        Тест имеет самостоятельный брендинг, отличный от встроенного
    </label>
</div>

<div class="form-group" id="branding_panel" style="display: none">
	@php
		$placeholder = "https://via.placeholder.com/300x300.png?text=Пусто";
		$logo = ($branding ? (isset($branding['logo']) ? $branding['logo'] : null) : null);
	@endphp
    <div class="form-group">
        <div class="col-lg-3 col-xs-12">
            <label
                for="image">Логотип</label>
            @if(!$show)
                <input type="file" id="logo-file" name="logo-file"
                       accept="image/*"
                       class="image-file mb-4 form-control"
                       onchange="readLogoImage(this)">
            @endif
            <div class="mb-4">
                <img id="preview_logo-file"
                     src="{{ '/uploads/' . $logo }}" alt=""
                     class="col-sm-6 mb-2 image-preview">
                @if(!$show)
                    <a href="javascript:void(0)"
                       id="clear_logo-file"
                       data-preview="preview_image"
                       class="btn btn-primary mb-3 image-clear col-sm-6"
                       style="display:none;">Очистить</a>
                @endif
            </div>
        </div>
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
               @if($show) disabled @endif
        >
        <div class="invalid-feedback">Поле &laquo;Организация / компания&raquo; должно быть заполнено</div>
    </div>

    <div class="preview mt-5">
        <div class="form-group">
            <label for="preview_nav">Предпросмотр заголовка окна / фрейма:</label>
            <nav class="navbar navbar-dark bg-primary d-none d-lg-flex align-content-center custom-background mb-2"
                 id="preview_nav">
                <div class="navbar-brand custom-color">
					<span id="preview_logo" style="height: 20px;"></span>
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

@push('scripts.injection')
	<script>
		function readLogoImage(input) {
			if (input.files && input.files[0]) {
				let reader = new FileReader();
				reader.onload = function (event) {
					document.getElementById('preview_logo-file').setAttribute('src', event.target.result);
					document.getElementById('preview_logo-file').style.display = 'block';
					document.getElementById('clear_logo-file').style.display = 'block';
					document.getElementById('preview_logo').innerHTML =
						"<img src=\"" + event.target.result + "\" class=\"preview_logo\" style=\"height: 20px;\">";
				};
				reader.readAsDataURL(input.files[0]);
			}
		}

		document.getElementById('clear_logo-file').addEventListener('click', () => {
			let file = document.getElementById('logo-file');
			file.setAttribute('type', 'text');
			file.setAttribute('type', 'file');

			document.getElementById('preview_logo-file').style.display = 'none';
			document.getElementById('clear_logo-file').style.display = 'none';
			document.getElementById('preview_logo').innerHTML = "<i class=\"fas fa-home\"></i>";
		});

		document.addEventListener("DOMContentLoaded", () => {
			@if($logo)
				document.getElementById('preview_logo-file').setAttribute('src', "{{ '/uploads/' . $logo }}");
				document.getElementById('preview_logo-file').style.display = 'block'
				document.getElementById('clear_logo-file').style.display = 'block';
				document.getElementById('preview_logo').innerHTML =
					"<img src=\"{{ '/uploads/' . $logo }}\" class=\"preview_logo\" style=\"height: 20px;\">";
			@else
				document.getElementById('preview_logo-file').style.display = 'none';
				document.getElementById('clear_logo-file').style.display = 'none';
				document.getElementById('preview_logo').innerHTML = "<i class=\"fas fa-home\"></i>";
			@endif
		}, false);
	</script>
@endpush
