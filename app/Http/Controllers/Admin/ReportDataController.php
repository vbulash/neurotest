<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\HistStat;
use Illuminate\Support\Facades\DB;

class ReportDataController extends Controller
{
    // События модели
    public const HISTORY_ALL_COUNT = 'history.all.count';
    public const HISTORY_PAID_COUNT = 'history.paid.count';
    public const HISTORY_DYNAMIC = 'history.dynamic';
    public const HISTORY_DYNAMIC_LABELS = 'history.dynamic.labels';
    public const HISTORY_DYNAMIC_ALL_COUNT = 'history.dynamic.all.count';
    public const HISTORY_DYNAMIC_PAID_COUNT = 'history.dynamic.paid.count';

    protected static $reportData = [];

    /**
     * @param string $key
     * @param $value
     */
    public static function generate(string $key): void
    {
        switch ($key) {
            case self::HISTORY_ALL_COUNT:
                $count = History::all()->count();
                self::$reportData[$key] = $count;
                break;
            case self::HISTORY_PAID_COUNT:
                $count = History::all()->where('paid', true)->count();
                self::$reportData[$key] = $count;
                break;
            case self::HISTORY_DYNAMIC:
                $dynamic = HistStat::limit(14)->get()->reverse();

                self::$reportData[self::HISTORY_DYNAMIC_LABELS] = [];
                self::$reportData[self::HISTORY_DYNAMIC_LABELS] = $dynamic->pluck('day')->toArray();
                self::$reportData[self::HISTORY_DYNAMIC_PAID_COUNT] = $paid = $dynamic->pluck('paid')->toArray();
                self::$reportData[self::HISTORY_DYNAMIC_ALL_COUNT] = $dynamic->pluck('total')->toArray();
                break;
        }
    }

    public static function get(string $key)
    {
        return self::$reportData[$key];
    }

    /**
     * Генерация всего пул данных для отчетов
     */
    public static function init(): void
    {
        self::generate(self::HISTORY_ALL_COUNT);
        self::generate(self::HISTORY_PAID_COUNT);
        self::generate(self::HISTORY_DYNAMIC);
    }
}
