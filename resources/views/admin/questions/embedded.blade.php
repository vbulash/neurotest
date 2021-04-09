<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Вопросы набора &laquo;{{ $set->name }}&raquo;
        </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form
            style="display:none"
            action="{{ route('questions.destroy', ['question' => 0]) }}"
            method="post" class="float-left">
            @csrf
            @method('DELETE')
            <input type="hidden" name="delete_id" id="delete_id" value="">
            <button type="submit" id="delete_btn" name="delete_btn"
                    onclick="return confirm('Подтвердите удаление');">&nbsp;
            </button>
        </form>

        <a href="{{ route('questions.create') }}" class="btn btn-primary mb-3">Добавить вопрос</a>
        @if ($questions_count)
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-nowrap" id="questions_table">
                    <thead>
                    <th>Номер по порядку</th>
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
                document.getElementById('delete_id').value = id;
                document.getElementById('delete_btn').click();
            }

            $(function () {
                let dt = $('#questions_table').DataTable({
                    language: {
                        "url": "{{ asset('assets/admin/plugins/datatables/lang/ru/Russian.json') }}"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('questions.index.data') !!}',
                    columns: [
                        {data: 'sort_no', name: 'sort_no'},
                        {data: 'action', name: 'action', sortable: false}
                    ]
                });
            });
        </script>
    @endpush
@endif
