<?php

namespace App\Observers;

use App\Events\TestActionsEvent;
use App\Http\Controllers\Admin\ReportDataController;
use App\Models\History;

class HistoryObserver
{
    /**
     * Handle the History "created" event.
     *
     * @param History $history
     * @return void
     */
    public function created(History $history)
    {
        //
    }

    /**
     * Handle the History "updated" event.
     *
     * @param History $history
     * @return void
     */
    public function updated(History $history)
    {
        if($history->wasChanged('paid')) {
            ReportDataController::generate(ReportDataController::HISTORY_PAID_COUNT);
            event(new TestActionsEvent(
                ReportDataController::HISTORY_PAID_COUNT,
                ReportDataController::get(ReportDataController::HISTORY_PAID_COUNT)));
        } elseif ($history->wasChanged('done')) {
            ReportDataController::generate(ReportDataController::HISTORY_ALL_COUNT);
            event(new TestActionsEvent(
                ReportDataController::HISTORY_ALL_COUNT,
                ReportDataController::get(ReportDataController::HISTORY_ALL_COUNT)));
        }
    }

    /**
     * Handle the History "deleted" event.
     *
     * @param History $history
     * @return void
     */
    public function deleted(History $history)
    {
        //
    }

    /**
     * Handle the History "restored" event.
     *
     * @param History $history
     * @return void
     */
    public function restored(History $history)
    {
        //
    }

    /**
     * Handle the History "force deleted" event.
     *
     * @param History $history
     * @return void
     */
    public function forceDeleted(History $history)
    {
        //
    }
}
