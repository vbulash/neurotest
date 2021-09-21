<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="small-box bg-gradient-blue">
        <div class="inner">
            <h3>{{ $data[\App\Http\Controllers\Admin\ReportDataController::HISTORY_ALL_COUNT] }}</h3>
            <p>{{ $data[\App\Http\Controllers\Admin\ReportDataController::HISTORY_ALL_COUNT . '.letter'] }}</p>
        </div>
        <div class="icon">
            <i class="fas fa-drafting-compass"></i>
        </div>
        <a href="{{ route('history.index') }}" class="small-box-footer">
            Больше информации <i class="fas fa-chevron-right"></i>
        </a>
    </div>
</div>
