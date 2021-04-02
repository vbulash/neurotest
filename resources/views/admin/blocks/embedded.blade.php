<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Модули теста &laquo;{{ $test->name }}&raquo;
        </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
    <!--
        <a href="#" {{-- {{ route('blocks.create', ['test' => $test->id]) }} --}}
        class="btn btn-primary mb-3">Добавить модуль</a>
        -->
        <form
            style="display:none"
            action="{{ route('blocks.destroy', ['block' => 0]) }}"
            method="post" class="float-left">
            @csrf
            @method('DELETE')
            <input type="hidden" name="delete_id" id="delete_id">
            <button type="submit" id="delete_btn" name="delete_btn"
                    onclick="return confirm('Подтвердите удаление');">&nbsp;</button>
        </form>
        <div class="dropdown">
            <button class="btn btn-primary mb-3 dropdown-toggle" type="button" id="addBlock"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Добавить модуль
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                @foreach(config('blocks') as $slug => $handler)
                    @if(!App\Models\Block::locked($slug))
                        <a class="dropdown-item"
                           href="{{ route('blocks.create.embedded', ['type' => $slug]) }}">{{ $handler::$title }}</a>
                    @endif
                @endforeach
            </div>
        </div>
        @if (count($blocks) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-nowrap" id="blocks_table">
                    <thead>
                    <tr>
                        <th>Номер по порядку</th>
                        <th>Наименование</th>
                        <th>Тип</th>
                        <th>Locked</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                </table>
            </div>
        @else
            <p>Модулей пока нет...</p>
        @endif
    </div>
</div>
<!-- /.card -->

@once
    @if (count($blocks) > 0)
        @push('styles')
            <link rel="stylesheet" href="{{ asset('assets/admin/plugins/datatables/datatables.css') }}">
        @endpush

        @push('scripts')
            <script src="{{ asset('assets/admin/plugins/datatables/datatables.js') }}"></script>
        @endpush
    @endif
@endonce

@if (count($blocks) > 0)
    @push('scripts.injection')
        <script>
            function clickDelete(id) {
                document.getElementById('delete_id').value = id;
                document.getElementById('delete_btn').click();
            }

            $(function () {
                let dt = $('#blocks_table').DataTable({
                    language: {
                        "url": "{{ asset('assets/admin/plugins/datatables/lang/ru/Russian.json') }}"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('blocks.index.data') !!}',
                    columns: [
                        //{data: 'sort_no', name: 'sort_no'},
                        {data: 'order', name: 'order'},
                        {data: 'name', name: 'name'},
                        {
                            data: 'type', name: 'type', render: (data) => {
                                return '';
                            }
                        },
                        {data: 'locked', name: 'locked', visible: false},
                        {data: 'action', name: 'action', sortable: false}
                    ],
                    createdRow: (row, data, dataIndex) => {
                        if (data.locked)
                            $(row).addClass('locked');
                    },
                    // rowReorder: {
                    //      dataSrc: 'order',
                    //      selector: 'tr'
                    // }
                });

                dt.on( 'row-reorder', (event, details, edit)=> {
                    //debugger;
                });
            });
        </script>
    @endpush
@endif
