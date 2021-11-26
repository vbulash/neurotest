<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Вопросы набора &laquo;{{ $set->name }}&raquo;
        </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <a href="{{ route('questions.create') }}" class="btn btn-primary mb-3">Добавить вопрос</a>
        @if ($questions_count)
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-nowrap" id="questions_table" style="width: 100%;">
                    <thead>
                    <tr>
                        <th>Номер по порядку</th>
                        <th>Миниатюры изображений</th>
                        <th>Режим прохождения</th>
                        <th>Таймаут, секунд</th>
                        <th>Ключ</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                </table>
            </div>
        @else
            <p>Вопросов пока нет...</p>
        @endif
    </div>
</div>
<!-- /.card -->

@once
    @if ($questions_count)
        @push('styles')
            <link rel="stylesheet" href="{{ asset('assets/admin/plugins/datatables/datatables.css') }}">
        @endpush

        @push('scripts')
            <script src="{{ asset('assets/admin/plugins/datatables/datatables.js') }}"></script>
        @endpush
    @endif
@endonce

@if ($questions_count)
    @push('scripts.injection')
        <script>
            function clickDelete(id) {
                if (window.confirm('Удалить вопрос № ' + id + ' ?')) {
                    $.ajax({
                        method: 'DELETE',
                        url: "{{ route('questions.destroy', ['question' => '0']) }}",
                        data: {
                            id: id,
                        },
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: () => {
                            window.datatable.ajax.reload();
                        }
                    });
                }
            }

            function clickUp(id) {
                $.post({
                    url: "{{ route('questions.up') }}",
                    data: {
                        id: id,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: () => {
                        window.datatable.ajax.reload();
                    }
                });
            }

            function clickDown(id) {
                $.post({
                    url: "{{ route('questions.down') }}",
                    data: {
                        id: id,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: () => {
                        window.datatable.ajax.reload();
                    }
                });
            }

            $(function () {
                window.datatable = $('#questions_table').DataTable({
                    language: {
                        "url": "{{ asset('assets/admin/plugins/datatables/lang/ru/Russian.json') }}"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('questions.index.data', ['sid' => $sid]) !!}',
                    columns: [
                        {data: 'sort_no', name: 'sort_no', responsivePriority: 1},
                        {
                            data: 'thumb', name: 'thumb', responsivePriority: 3, sortable: false, render: (data) => {
                                if (data) {
                                    let thumbs = JSON.parse(data.replace(/&quot;/g, '"'));
                                    let preview = '';
                                    thumbs.forEach((thumb) => {
                                        preview = preview +
                                            "<img src=\"" + thumb + "\" alt=\"\" class=\"thumb-row\">\n";
                                    });
                                    return preview;
                                } else return '';
                            }
                        },
                        {
                            data: 'learning', name: 'learning', responsivePriority: 1, render: (data) => {
                                switch (parseInt(data)) {
                                    case 0:
                                        return 'Реальный';
                                    case 1:
                                        return 'Учебный';
                                }
                            }
                        },
                        {data: 'timeout', name: 'timeout', responsivePriority: 2},
                        {
                            data: 'key', name: 'key', responsivePriority: 4, render: (data) => {
                                return data;
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            sortable: false,
                            responsivePriority: 1,
                            className: 'no-wrap dt-actions'
                        }
                    ]
                });
            });
        </script>
    @endpush
@endif
