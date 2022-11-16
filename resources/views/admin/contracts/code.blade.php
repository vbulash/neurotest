<div class="modal fade" id="copy-code" tabindex="-1" aria-labelledby="copy-code" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tests-create-label">Код HTML для помещения на страницу сайта клиента</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php
                    $testNo = 1;
                @endphp

                @forelse($contract->tests as $test)
                    @php
                        $code = sprintf(
                            "<iframe\n" .
	  	                    "src=\"%s\"\n" .
		                    "width=\"1000px\"\n" .
		                    "height=\"700px\"\n" .
		                    "allow=\"fullscreen\">\n" .
		                    "frameborder=\"0\">\n" .
		                    "</iframe>",
		                    route('player.play', [
                                'mkey' => $contract->mkey,
                                'test' => $test->key,
                            ]));
                    @endphp
                    <div class="form-group">
                        <label for="html-{{ $testNo }}">Тест # {{ $test->id }} &laquo;{{ $test->name }}&raquo;</label>
                        <textarea name="html-{{ $testNo }}" class="form-control"
                                  id="html-{{ $testNo }}" cols="40" rows="7" disabled>{{ $code }}</textarea>
{{--                        <code id="code-{{ $testNo }}">{{ $code }}</code>--}}
                    </div>
{{--                    <button type="button" class="btn btn-primary" id="copy-{{ $testNo }}" onclick="copy('html-{{ $testNo }}')">--}}
{{--                        Копировать код HTML</button>--}}
                    <hr/>
                    @php($testNo++)
                @empty
                    <p>В контракте нет тестов, нет кода HTML для страниц</p>
                @endforelse
            </div>
            <div class="modal-footer">
                <button type="button" class="btn @if(count($contract->tests) > 0) btn-secondary @else btn-primary @endif" data-bs-dismiss="modal">
                    Закрыть</button>
            </div>
        </div>
    </div>
</div>

@push('scripts.injection')
    <script>
        function copy(name) {
            var copyHTML = document.getElementById(name);
            copyHTML.focus();
            copyHTML.select();
            copyHTML.setSelectionRange(0, 99999);
            document.execCommand("copy");
        }
    </script>
@endpush
