@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Главная</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Главная</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    @php
        //use Illuminate\Support\Facades\Redis;
        //Redis::set('test', 'Test');
    @endphp

    <!-- Статистика -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Статистика тестирования</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                            title="Свернуть / развернуть">
                        <i class="fas fa-minus"></i></button>
                    {{--                    <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip"--}}
                    {{--                            title="Remove">--}}
                    {{--                        <i class="fas fa-times"></i></button>--}}
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Платные тесты--}}
                    @include('admin.widgets.test_paid')
                    {{-- Тесты всего--}}
                    @include('admin.widgets.test_total')
                </div>
            </div>
            <!-- /.card-body -->
        {{--            <div class="card-footer">--}}
        {{--                Footer--}}
        {{--            </div>--}}
        <!-- /.card-footer-->
        </div>
        <!-- /.Статистика -->

        <!-- Динамика -->
        <div class="card col-md-6 col-sm-12">
            <div class="card-header">
                <h3 class="card-title">Динамика тестирования</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                            title="Свернуть / развернуть">
                        <i class="fas fa-minus"></i></button>
                    {{--                    <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip"--}}
                    {{--                            title="Remove">--}}
                    {{--                        <i class="fas fa-times"></i></button>--}}
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="chart">
                        <canvas id="stackedBarChart" style="min-height:330px"></canvas>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        {{--            <div class="card-footer">--}}
        {{--                Footer--}}
        {{--            </div>--}}
        <!-- /.card-footer-->
        </div>
        <!-- /.Динамика -->

    </section>
    <!-- /.content -->
@endsection

@once
    @push('scripts')
        <script src="{{ asset('assets/admin/plugins/chart.js/chart.min.js') }}"></script>
    @endpush
@endonce

@push('scripts.injection')
    <script>
        let areaChartData = {
            labels: null,
            datasets: [
                {
                    label: 'Тестирований пройдено',
                    backgroundColor: '#007bff',
                    data: null
                },
                {
                    label: 'Тестирований оплачено',
                    backgroundColor: '#28a745',
                    data: null
                },
            ]
        };
        areaChartData.labels = [{!! "'" . implode("', '", $data[\App\Http\Controllers\Admin\ReportDataController::HISTORY_DYNAMIC_LABELS]) . "'" !!}];
        areaChartData.datasets[0].data = [{{ implode(', ', $data[\App\Http\Controllers\Admin\ReportDataController::HISTORY_DYNAMIC_ALL_COUNT]) }}];
        areaChartData.datasets[1].data = [{{ implode(', ', $data[\App\Http\Controllers\Admin\ReportDataController::HISTORY_DYNAMIC_PAID_COUNT]) }}];

        let areaChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: false,
                    text: 'Динамика тестирования'
                },
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                xAxes: {
                    ticks: {
                        autoSkip: false,
                        maxRotation: 90,
                        minRotation: 0
                    }
                }
            }
        };

        let ctx = document.getElementById('stackedBarChart').getContext('2d');
        let myChart = new Chart(ctx, {
            type: 'bar',
            data: areaChartData,
            options: areaChartOptions
        });
    </script>
@endpush
