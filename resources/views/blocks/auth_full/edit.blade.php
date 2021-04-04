@extends('admin.blocks.edit')

@section('fieldset')
    <div class="form-group mt-4 mb-4"><h4>Дополнительные поля</h4></div>
    <div id="dynamic"></div>
@endsection

@push('scripts.injection')
    <script>
        $(function () {
            let content = JSON.parse('{!! $content !!}');
            let output = '';
            content.forEach((control) => {
                let html =
                    "<div class=\"form-group\">\n" +
                    "<div class=\"checkbox\">\n" +
                    "<input type=\"checkbox\" name=\"" + control.name + "\" id=\"" + control.name + "\" " +
                    (control.actual ? "checked" : "") + "@if($show) disabled @endif>\n" +
                    "<label for=\"" + control.name + "\"> (" + control.type + ") " + control.label + "</label>\n" +
                    "</div>\n" +
                    "</div>\n\n";
                output = output + html;
            });
            $('#dynamic').html(output);
        });
    </script>
@endpush

