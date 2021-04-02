<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Test;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function play(Request $request, int $test, string $mkey)
    {
        echo "# теста: " . $test . "<br/>";
        $test = Test::find($test);
        echo "Тест: <pre>" . print_r($test, true) . "</pre><br/><br/>";

        echo "Мастер-ключ: " . $mkey . "<br/>";
        if($test) {
            $contract = Contract::find($test->contract_id);
            echo "Контракт: <pre>" . print_r($contract, true) . "</pre><br/><br/>";
        }
        echo "Запущено с URL: " . $request->server('HTTP_REFERER') . "<br/>";
        die(200);
    }
}
