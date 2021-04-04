@extends('admin.blocks.edit')

@section('fieldset')
    <div class="form-group mt-4 mb-4"><h4>Дополнительные поля</h4></div>
    <div id="dynamic"></div>
@endsection

@push('scripts.injection')
    <script>
        window.lstorage = "/uploads/";
        $(function () {
            let content = JSON.parse('{!! $content !!}');
            let output = '';
            content.forEach((control) => {
                let html =
                    "<div class=\"form-group\">\n" +
                    "<label for=\"" + control.name + "\">" + control.label + "</label>\n" +
                    "<input type=\"file\" id=\"" + control.name + "\" name=\"" + control.name + "\" class=\"form-control\">\n";
                if(control.value != null)
                    html = html +
                        "<div class=\"col-lg-3 col-xs-6\"><img src=\"" + window.lstorage + control.value + "\" alt=\"\" class=\"img-thumbnail mt-2\"></div>\n";
                html = html + "</div>\n\n";

                output = output + html;
            });
            $('#dynamic').html(output);
        });
    </script>
@endpush

