<?php

namespace App\Http\Controllers;

use App\Http\Requests\PKeyRequest;
use App\Models\Contract;
use App\Models\History;
use App\Models\License;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function check(Request $request, string $mkey): bool
    {
        session()->forget('test');

        $contract = Contract::all()->where('mkey', $mkey)->first();
        if (!$contract) {
            session()->flash('error', 'Неверный мастер-ключ');
            return false;
        } else {
            $messages = [];
            // TODO Сделать проверку на правильный URL
            //$request->server('HTTP_REFERER')
            $test = $contract->test;
            if (!$test) {
                $messages[] = 'Нет привязки теста к контракту';
            } else {
                if ($test->type != Test::TYPE_ACTIVE)
                    $messages[] = "Допускается тест только со статусом &laquo;Активный&raquo;";
            }

            if (count($messages) > 0) {
                session()->flash('error', implode('<br/>', $messages));
                return false;
            } else {
                session()->put('test', $test);
                return true;
            }
        }
    }

    public function index()
    {
        return view('front.index');
    }

    public function play(Request $request, string $mkey)
    {
        if (!$this->check($request, $mkey)) {
            return redirect()->route('player.index')->with('error', session('error'));
        } else {
            $test = session('test');
            return view('front.intro', compact('test'));
        }
    }

    public function card(Request $request, string $mkey)
    {
        if (!$this->check($request, $mkey)) {
            return redirect()->route('player.index')->with('error', session('error'));
        } else {
            session()->forget('pkey');
            $test = session('test');
            if ($test->options & Test::AUTH_GUEST) {
                return redirect()->route('player.body', ['mkey' => $mkey, 'question' => 0]);
                // TODO Перейти на следующий этап теста
            } elseif ($test->options & Test::AUTH_FULL) {
                // TODO Запрос полной анкеты перед тестом
            } elseif ($test->options & Test::AUTH_PKEY) {
                return view('front.pkey_card', compact('test', 'mkey'));
            }
        }
    }

    /*
     * Route::get('/player.play/{mkey}', 'PlayerController@play')->name('player.play');
    Route::get('/player.card/{mkey}', 'PlayerController@card')->name('player.card');
    Route::get('/player.body/{mkey}/{block}', 'PlayerController@body')->name('player.body');
    Route::get('/player.result/{mkey}', 'PlayerController@result')->name('player.result');
     */

    public function store_pkey(PKeyRequest $request)
    {
        $mkey = $request->mkey;
        session()->put('pkey', $request->pkey);
        return redirect()->route('player.body', ['mkey' => $mkey, 'question' => 0]);
    }

    public function body(Request $request, string $mkey, int $question = 0)
    {
        if (!$this->check($request, $mkey))
            return redirect()->route('player.index')->with('error', session('error'));

        if($request->has('answer')) {
            $questions = session('steps');
            $index = array_search($question, $questions);
            $history = session('history');
            $jumpNext = true;
            $step = Question::findOrFail($question);

            if($request->answer == 0) { // Таймаут
                if($step->learning != 1) {  // Реальный (не учебный) вопрос
                    unset($questions[$index]);
                    $questions[] = $question;
                    $questions = array_values($questions);
                    session()->put('steps', $questions);
                    $jumpNext = false;
                }
            }
            if($jumpNext) {
                $index++;
                if($step->learning != 1) {  // Веса есть только у реальных (не учебных) вопросов
                    $old = $history->getAttribute('channel' . $request->answer);
                    $history->setAttribute('channel' . $request->answer, ++$old);
                    $history->update();
                }
            }

            if($index < count($questions)) {
                $question = $questions[$index];
            } else {
                $history->update(['done' => date("Y-m-d H:i:s")]);
                $license = License::all()->where('pkey', session('pkey'))->first();
                $license->done();

                return redirect()->route('player.index')
                    ->with('success', "Тест успешно завершен, нужно отобразить / переслать результат.<br/>Пока результат можно увидеть в базе данных в таблице history");
            }
        }
        $test = session('test');
        $testname = $test->name;
        if ($question == 0) {
            $questions = array_values($test->set->questions->sortBy('sort_no')->pluck('id')->toArray());
            session()->put('steps', $questions);

            // Блокировка лицензии для прохождения теста
            if (session()->has('pkey')) {
                $license = $test->contract->licenses->where('pkey', session('pkey'))->first();
            } else {
                $license = $test->contract->licenses->where('status', License::FREE)->first();
                session()->put('pkey', $license->pkey);
            }
            $license->lock();

            $history = History::create([
                'test_id' => $test->id,
                'license_id' => $license->id,
                'card' => null, // TODO взять карточку с предыдщего маршрута
            ]);
            $history->save();
            session()->put('history', $history);

            // Переход на первый шаг теста
            $step = Question::findOrFail($questions[0]);

            return view('front.body', compact('step', 'test', 'mkey'));
        } else {
            // Переход на вопрос $question
            $step = Question::findOrFail($question);
            return view('front.body', compact('step', 'test', 'mkey'));
        }
    }
}
