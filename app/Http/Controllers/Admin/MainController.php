<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Contract;
use DateTime;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Admin\ReportDataController as RDC;

class MainController extends Controller
{

    private function testAllLetter(int $count): string {
        $letter = ' ';
        if(($count < 10) || ($count > 20)) {
            switch($count % 10) {
                case 1:
                    $letter .= 'тест пройден';
                    break;
                case 2:
                case 3:
                case 4:
                    $letter .= 'теста пройдено';
                    break;
                default:
                    $letter .= 'тестов пройдено';
            }
        } else $letter .= 'тестов пройдено';

        return $letter;
    }

    private function testPaidLetter(int $count): string {
        $letter = ' ';
        if(($count < 10) || ($count > 20)) {
            switch($count % 10) {
                case 1:
                    $letter .= 'тест оплачен';
                    break;
                case 2:
                case 3:
                case 4:
                    $letter .= 'теста оплачено';
                    break;
                default:
                    $letter .= 'тестов оплачено';
            }
        } else $letter .= 'тестов оплачено';

        return $letter;
    }

    public function index()
    {
        RDC::init();

        $data = [
            RDC::HISTORY_ALL_COUNT => RDC::get(RDC::HISTORY_ALL_COUNT),
            RDC::HISTORY_PAID_COUNT => RDC::get(RDC::HISTORY_PAID_COUNT),
            RDC::HISTORY_DYNAMIC_LABELS => RDC::get(RDC::HISTORY_DYNAMIC_LABELS),
            RDC::HISTORY_DYNAMIC_ALL_COUNT => RDC::get(RDC::HISTORY_DYNAMIC_ALL_COUNT),
            RDC::HISTORY_DYNAMIC_PAID_COUNT => RDC::get(RDC::HISTORY_DYNAMIC_PAID_COUNT),
        ];
        $data[RDC::HISTORY_PAID_COUNT . '.letter'] = $this->testPaidLetter($data[RDC::HISTORY_PAID_COUNT]);
        $data[RDC::HISTORY_ALL_COUNT . '.letter'] = $this->testAllLetter($data[RDC::HISTORY_ALL_COUNT]);

        return view('admin.index', compact('data'));
    }

}
