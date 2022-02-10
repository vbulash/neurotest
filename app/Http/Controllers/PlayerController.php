<?php

namespace App\Http\Controllers;

use App\Events\ToastEvent;
use App\Http\Requests\PKeyRequest;
use App\Mail\TestResult;
use App\Models\Block;
use App\Models\Contract;
use App\Models\History;
use App\Models\HistoryStep;
use App\Models\License;
use App\Models\Neuroprofile;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PlayerController extends Controller
{
    public function check(Request $request, string $mkey = null, string $test_key = null): bool
    {
//        Log::debug('mkey = ' . $mkey);
//        Log::debug('test_key = ' . $test_key);
        if (!$mkey) {
            if (!session()->has('mkey')) {
                Log::debug('Внутренняя ошибка: потерян мастер-ключ');
                session()->flash('error', 'Внутренняя ошибка: потерян мастер-ключ');
                return false;
            } else return true;
        }
        session()->forget('test');

        $contract = Contract::all()->where('mkey', $mkey)->first();
        if (!$contract) {
            Log::debug('Неверный мастер-ключ');
            session()->flash('error', 'Неверный мастер-ключ');
            return false;
        } else {
            $messages = [];

            // Проверка URL вызова
            $contractUrl = parse_url($contract->url);
            $realUrl = parse_url($request->server('HTTP_REFERER'));
            //Log::debug('server = ' . print_r($request->server(), true));
            // TODO Включить проверку URL
            //$result = Str::startsWith($realUrl['scheme'] . '://' . $realUrl['host'], $contractUrl['scheme'] . '://' . $contractUrl['host']);
            $result = true;
//            Log::debug('contractUrl = ' . $contractUrl['scheme'] . '://' . $contractUrl['host'] .
//                ' | realUrl = ' . $realUrl['scheme'] . '://' . $realUrl['host'] .
//                ' | compare = ' . $result);
            if (!$result) {
                $messages[] = 'Запуск теста с текущей страницы не разрешен';
            } else {
                $test = Test::all()->where('key', $test_key)->first();
                if (!$test) {
                    //Log::debug(__METHOD__ . ':' . __LINE__);
                    $messages[] = 'Не найден тест с указанным ключом';
                } else {
                }
            }

            if (count($messages) > 0) {
                session()->flash('error', implode('<br/>', $messages));
                Log::debug('Сообщения об ошибках: <br/>' . implode('<br/>', $messages));
                return false;
            } else {
                session()->put('test', $test);
                session()->put('mkey', $mkey);
                //Log::debug('test and mkey saved');
                return true;
            }
        }
    }

    public function index()
    {
        return view('front.index');
    }

    public function play(Request $request, string $mkey, string $test)
    {
        if (!$this->check($request, $mkey, $test)) {
            //Log::debug('player.play: ' . __METHOD__ . ':' . __LINE__);
            return redirect()->route('player.index', ['sid' => session()->getId()])->with('error', session('error'));
        } else {
            $test = session('test');
            return view('front.intro', compact('test'));
        }
    }

    public function card(Request $request)
    {
        if (!$this->check($request)) {
            Log::debug('player.card: ' . __METHOD__ . ':' . __LINE__);
            return redirect()->route('player.index', ['sid' => session()->getId()])->with('error', session('error'));
        } else {
            session()->forget('pkey');
            $test = session('test');

            if ($test->options & Test::AUTH_GUEST) {
                //return redirect()->route('player.body', ['question' => 0, 'sid' => session()->getId()]);
                return redirect()->route('player.body2', ['sid' => session()->getId()]);
            } elseif ($test->options & Test::AUTH_FULL) {
                $content = json_decode($test->content, true);
                $card = $content['card'];
                return view('front.full_card', compact('test', 'card'));
            } elseif ($test->options & Test::AUTH_PKEY) {
                return view('front.pkey_card', compact('test'));
            }
        }
    }

    public function store_pkey(PKeyRequest $request)
    {
        session()->put('pkey', $request->pkey);
        return redirect()->route('player.body2', ['question' => 0, 'sid' => session()->getId()]);
    }

    public function store_full_card(Request $request)
    {
        $data = $request->except('_token');
        session()->put('card', $data);

        //return redirect()->route('player.body', ['question' => 0, 'sid' => session()->getId()]);
        return redirect()->route('player.body2', ['sid' => session()->getId()]);
    }

    public function body2(Request $request)
    {
        if (!$this->check($request)) {
            //Log::debug('player.body2: ' . __METHOD__ . ':' . __LINE__);
            return redirect()->route('player.index', ['sid' => session()->getId()])->with('error', session('error'));
        }

        $test = session('test');
        $set = $test->qset;

        $view = DB::select(<<<EOS
SELECT
       t.id as tid,
       qs.quantity as qsquantity,
       q.id as qid,
       q.sort_no as qsort_no,
       q.learning as qlearning,
       q.timeout as qtimeout,
       q.imageA as qimage1,
       q.imageB as qimage2,
       q.imageC as qimage3,
       q.valueA as qvalue1,
       q.imageD as qimage4,
       q.valueB as qvalue2,
       q.valueC as qvalue3,
       q.valueD as qvalue4
FROM
    tests AS t, questionsets AS qs, questions as q
WHERE
    qs.id = t.questionset_id AND
    q.questionset_id = qs.id AND
    t.id = :tid
ORDER BY
    qsort_no
EOS
            , ['tid' => $test->getKey()]
        );

        $stack = [];
        $steps = [];
        foreach ($view as $item) {
            $stack[] = $item->qid;
            $step = [
                'id' => $item->qid,
                'sort_no' => $item->qsort_no,
                'learning' => $item->qlearning,
                'timeout' => $item->qtimeout,
                'quantity' => $item->qsquantity
            ];

            $images = [];
            for ($iimage = 1; $iimage <= $item->qsquantity; $iimage++)
                $images[$item->{'qimage' . $iimage}] = $item->{'qvalue' . $iimage};
            $step['images'] = $images;

            $steps[] = $step;
        }

        // Блокировка лицензии для прохождения теста
        $license = $test->contract->licenses->where('status', License::FREE)->first();
        if ($license) {
            session()->put('pkey', $license->pkey);
        } else {
            session()->put('error', 'Свободные лицензии закончились, обратитесь в Persona');
            //Log::debug(__METHOD__ . ':' . __LINE__);
            return redirect()->route('admin.index');
        }
        $license->lock();

        return view('front.body2', compact('test', 'steps', 'stack'));
    }

    public function body2_store(Request $request): RedirectResponse
    {
        event(new ToastEvent('info', '', "Анализ результатов тестирования..."));

        $test = session('test');
        $data = $request->all();

        // Фиксация лицензии по завершению тестирования
        $license = License::all()->where('pkey', session('pkey'))->first();
        $license->done();

        // Зафиксировать историю теста и индивидуальные результаты прохождения вопросов в рамках транзакции
        DB::transaction(function () use ($data, $license, $test) {
            $history = History::create([
                'test_id' => $test->getKey(),
                'license_id' => $license->getKey(),
                'card' => (session()->has('card') ? json_encode(session('card')) : null),
            ]);
            $history->save();
            session()->put('hid', $history->getKey());

            foreach ($data as $answer => $value) {
                if (!Str::startsWith($answer, 'answer-')) continue;
                $parts = explode('-', $answer);
                $key = $parts[1];
                // $key => $value
                $hs = HistoryStep::create([
                    'history_id' => $history->getKey(),
                    'question_id' => $key,
                    'key' => $value,
                    'done' => date("Y-m-d H:i:s")
                ]);
                $hs->save();
            }

            $history->update(['done' => date("Y-m-d H:i:s")]);
        });

        $hid = session('hid');
        session()->forget('hid');
        return redirect()->route('player.precalc',
            [
                'test' => $test->getKey(),
                'history_id' => $hid,
                'sid' => session()->getId()
            ]
        );
    }

    public function body(Request $request, int $question = 0)
    {
//        Log::debug('session in body = ' . print_r(array_keys(session()->all()), true));
        if (!$this->check($request)) {
            //Log::debug('player.body: ' . __METHOD__ . ':' . __LINE__);
            return redirect()->route('player.index')->with('error', session('error'));
        }

        if ($request->has('answer')) {
            $questions = session('steps');
            $index = array_search($question, $questions);
            $history = session('history');
            $jumpNext = true;
            $step = Question::findOrFail($question);

            if ($request->answer == 0) { // Таймаут
                if ($step->learning != 1) {  // Реальный (не учебный) вопрос
                    unset($questions[$index]);
                    $questions[] = $question;
                    $questions = array_values($questions);
                    session()->put('steps', $questions);
                    $jumpNext = false;
                }
            }
            if ($jumpNext) {
                $index++;
                //if ($step->learning != 1) {  // Веса есть только у реальных (не учебных) вопросов
                $key = $step->getAttributeValue('value' . $request->answer);
                $hs = HistoryStep::create([
                    'history_id' => $history->getKey(),
                    'question_id' => $step->getKey(),
                    'key' => $key,
                    'done' => date("Y-m-d H:i:s")
                ]);
                $hs->save();
                //}
            }

            if ($index < count($questions)) {
                $question = $questions[$index];
            } else {
                $history->update(['done' => date("Y-m-d H:i:s")]);
                $license = License::all()->where('pkey', session('pkey'))->first();
                $license->done();

                return redirect()->route('player.precalc',
                    [
                        'test' => $history->test->getKey(),
                        'history_id' => $history->getKey(),
                        'sid' => session()->getId()
                    ]
                );
            }
        }
        $test = session('test');
        if ($question == 0) {
            $set = $test->qset;
            $questions = array_values($set->questions->sortBy('sort_no')->pluck('id')->toArray());
            session()->put('steps', $questions);

            // Блокировка лицензии для прохождения теста
            if (session()->has('pkey')) {
                $license = $test->contract->licenses->where('pkey', session('pkey'))->first();
            } else {
                $license = $test->contract->licenses->where('status', License::FREE)->first();
                if ($license) {
                    session()->put('pkey', $license->pkey);
                } else {
                    session()->put('error', 'Свободные лицензии закончились, обратитесь в Persona');
                    //Log::debug(__METHOD__ . ':' . __LINE__);
                    return redirect()->route('player.index',
                        [
                            'sid' => session()->getId()
                        ]);
                }
            }
            $license->lock();

            $history = History::create([
                'test_id' => $test->getKey(),
                'license_id' => $license->getKey(),
                'card' => (session()->has('card') ? json_encode(session('card')) : null),
            ]);
            $history->save();
            session()->put('history', $history);

            // Переход на первый шаг теста
            $step = Question::findOrFail($questions[0]);

            return view('front.body', compact('step', 'test'));
        } else {
            // Переход на вопрос $question
            $step = Question::findOrFail($question);

            return view('front.body', compact('step', 'test'));
        }
    }

    public function precalc(int $history_id)
    {
        $history = History::findOrFail($history_id);
        $test = $history->test;
        return view('front.precalc', compact('test', 'history_id'));
    }

    public function calculate(int $history_id): View|Factory|bool|Application|null
    {
        $history = History::findOrFail($history_id);
        $test = $history->test;
        $content = json_decode($test->content);
        $card = ($history->card ? json_decode($history->card) : null);
        $fmptype_show = $content->descriptions->show;
        $fmptype_mail = $content->descriptions->mail;
        if (!$fmptype_show && !$fmptype_mail) {
            session()->put('error', 'Не определен тип описания для результатов тестирования. Обратитесь к администратору');
            return false;
        }

        // Не переименовывать переменную - может использоваться в коде набора вопросов в eval()
        $result = HistoryStep::where('history_id', $history_id)->pluck('key')->toArray();

        // Бельграно 1
        //dd($result);
        // .Бельграно 1

        $code = htmlspecialchars_decode(strip_tags($history->test->qset->content));
        $profile_code = eval($code);
        $history->code = $profile_code;
        $history->update();
        // Код нейропрофиля вычислен и сохранен

        $result = true;
        if ($fmptype_mail && ($test->options & Test::AUTH_FULL)) {
            event(new ToastEvent('info', '', "Отправка письма с результатами тестирования..."));
            $profile = Neuroprofile::all()
                ->where('fmptype_id', '=', $fmptype_mail)
                ->where('code', '=', $profile_code)
                ->first();
            $profile_id = $profile->getKey();
            $profile_name = $profile->name;
            $blocks = Block::all()->where('neuroprofile_id', $profile_id);

            $recipient = (object)[
                'name' => (property_exists($card, 'last_name') ? $card->last_name . ' ' : '') . (property_exists($card, 'first_name') ? $card->first_name . ' ' : ''),
                'email' => $card->email
            ];
            $copy = (object)[
                'name' => env('MAIL_FROM_NAME'),
                'email' => env('MAIL_FROM_ADDRESS')
            ];

            try {
                Mail::to($recipient)
                    ->cc($copy)
                    ->send(new TestResult($test, $blocks, $profile_code, $profile_name, $card, $history));
                session()->put('success', 'Вам отправлено письмо с результатами тестирования');
            } catch (\Exception $exc) {
                session()->put('error', "Ошибка отправки письма с результатами тестирования:<br/>" .
                    $exc->getMessage());
            }
        }

        if ($fmptype_show) {
            event(new ToastEvent('info', '', "Генерация экранных результатов тестирования..."));
            $profile = Neuroprofile::all()
                ->where('fmptype_id', '=', $fmptype_show)
                ->where('code', '=', $profile_code)
                ->first();
            $profile_id = $profile->getKey();
            $profile_name = $profile->name;
            $blocks = Block::all()->where('neuroprofile_id', $profile_id);

            return view('front.show', compact('card', 'test', 'blocks', 'profile_code', 'profile_name', 'history'));
        }

        return null;
    }

    public function iframe()
    {
        return view('front.iframe');
    }

    public function paymentResult(Request $request)
    {
//        Log::debug('Robokassa result = ' . print_r($request->all(), true));
        if ($request->has('InvId')) {
            //if($request->Shp_Mail != '1') $this->paymentSuccess($request);

            $data = $request->all();
            $history_id = $request->InvId;
            $mail = ($request->Shp_Mail == '1');
            $list = false;
            if ($request->has('List')) $list = ($request->List == '1');

            $history = History::findOrFail($history_id);
            if (!$list) {
                $history->paid = true;
                $history->update();
            }

            $test = $history->test;
            $content = json_decode($test->content);
            $card = ($history->card ? json_decode($history->card) : null);
            $fmptype_mail = $content->descriptions->mail;

            // Не переименовывать переменную - может использоваться в коде набора вопросов в eval()
            $result = HistoryStep::where('history_id', $history_id)->pluck('key')->toArray();

            $code = htmlspecialchars_decode(strip_tags($history->test->qset->content));
            $profile_code = eval($code);
            // Код нейропрофиля вычислен

            $profile = Neuroprofile::all()
                ->where('fmptype_id', '=', $fmptype_mail)
                ->where('code', '=', $profile_code)
                ->first();
            $profile_id = $profile->getKey();
            $profile_name = $profile->name;
            $blocks = Block::all()->where('neuroprofile_id', $profile_id);

            $recipient = (object)[
                'name' => (property_exists($card, 'last_name') ? $card->last_name . ' ' : '') . (property_exists($card, 'first_name') ? $card->first_name . ' ' : ''),
                'email' => $card->email
            ];
            $copy = (object)[
                'name' => env('MAIL_FROM_NAME'),
                'email' => env('MAIL_FROM_ADDRESS')
            ];

            try {
                Mail::to($recipient)
                    ->cc($copy)
                    ->send(new TestResult($test, $blocks, $profile_code, $profile_name, $card, $history));
                $kind = 'success';
                session()->put($kind,
                    $list ?
                        "На адрес {$recipient->email} отправлено письмо с полными результатами тестирования" :
                        'Вам отправлено письмо с полными результатами тестирования'
                );
            } catch (\Exception $exc) {
                $kind = 'info';
                session()->put($kind, $exc->getMessage());
            }

            return ($list ?
                redirect()->route('history.index', ['sid' => session()->getId()])->with($kind, session($kind)) :
                'OK' . $request->InvId);
        }
        return false;
    }

    public function paymentSuccess(Request $request)
    {
//        Log::debug('Robokassa success = ' . print_r($request->all(), true));
        $history_id = $request->InvId;
        $history = History::findOrFail($history_id);
        $test = $history->test;

        session()->put('success', 'Вам отправлено письмо с полными результатами тестирования');

        return view('front.info', compact('test'));
    }

    public function paymentFail(Request $request)
    {
        $history_id = $request->InvId;
        $mail = ($request->Shp_Mail == '1');

        $history = History::findOrFail($history_id);
        $test = $history->test;

        session()->forget('error');

        return view('front.info', compact('test'));
    }

    public function showDocument(Request $request, string $document, bool $mail = false)
    {
        $test = session('test');
        $docviews = [
            'privacy' => 'front.documents.privacy',
            'personal' => 'front.documents.personal',
            'oferta' => 'front.documents.oferta',
        ];

        return view($docviews[$document], compact('mail', 'test'));
    }
}
