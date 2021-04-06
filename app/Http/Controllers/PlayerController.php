<?php

    namespace App\Http\Controllers;

    use App\Models\Block;
    use App\Models\Contract;
    use App\Models\Test;
    use Illuminate\Http\Request;

    class PlayerController extends Controller
    {
        protected array $steps = [];

        public function check(Request $request, string $mkey): int
        {
            $message = [];
            $contract = Contract::all()->where('mkey', $mkey)->first();
            if (!$contract) {
                $message[] = 'Неверный мастер-ключ';
                session()->flash('error', implode('<br/>', $message));
                return 0;
            } else {
                // TODO Сделать проверку на правильный URL
                //$request->server('HTTP_REFERER')
                // TODO Сделать проверку на акутальность контракта
                $test = $contract->test;
                session('test_id', $test->id);
                return $test->id;
            }
            /*
             * echo "# теста: " . $test . "<br/>";
            $test = Test::find($test);
            echo "Тест: <pre>" . print_r($test, true) . "</pre><br/><br/>";

            echo "Мастер-ключ: " . $mkey . "<br/>";
            if($test) {
                $contract = Contract::find($test->contract_id);
                echo "Контракт: <pre>" . print_r($contract, true) . "</pre><br/><br/>";
            }
            echo "Запущено с URL: " . $request->server('HTTP_REFERER') . "<br/>";
            die(200);
             */
        }

        public function play(Request $request, string $mkey, int $block_id = 0)
        {
            if ($block_id == 0) {    // Начало теста
                $test_id = $this->check($request, $mkey);
                if ($test_id == 0) {
                    return redirect()->route('admin.index');
                }

                $test = Test::findOrFail($test_id);
                $this->steps = $test->blocks()
                    ->orderBy('sort_no')
                    ->orderBy('id')
                    ->pluck('id')
                    ->toArray();

//                session()->flash('success', 'Начинаем прохождение теста. Просто сообщение');
                return view('front.intro', compact('test'));
            } else {
                // TODO Решить - проверять mkey на каждом шаге или нет
                $block = Block::findOrFail($block_id);
                $handler = config('blocks.' . $block->type);
                $content = $handler::$content;
                // ...
            }
        }

        public function step(Request $request, int $block_id = -1)
        {
            dd($request);
            // ...
        }
    }
