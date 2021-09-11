<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Contract;
use DateTime;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{

    public function index()
    {
        $data = [];
        // Клиенты
        $count = Client::all()->count();
//        $letter = $count . ' ';
//        if(($count < 10) || ($count > 20)) {
//            switch($count % 10) {
//                case 1:
//                    $letter .= 'клиент';
//                    break;
//                case 2:
//                case 3:
//                case 4:
//                    $letter .= 'клиента';
//                    break;
//                default:
//                    $letter .= 'клиентов';
//            }
//        } else $letter .= 'клиентов';
        $data['clients.count'] = $count;

        return view('admin.index', compact('data'));
    }

}
