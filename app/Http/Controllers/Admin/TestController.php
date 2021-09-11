<?php

    namespace App\Http\Controllers\Admin;

    use App\Events\ToastEvent;
    use App\Http\Requests\StoreTestRequest;
    use App\Http\Requests\UpdateTestRequest;
    use App\Http\Support\CallStack;
    use App\Models\Contract;
    use Exception;
    use App\Models\Test;
    use App\Http\Controllers\Controller;
    use Illuminate\Contracts\View\Factory;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Support\Facades\Log;
    use Illuminate\View\View;
    use Yajra\DataTables\DataTables;

    class TestController extends Controller
    {
        /**
         * Process datatables ajax request.
         *
         * @return JsonResponse
         * @throws Exception
         */
        public function getData(): JsonResponse
        {
            $tests = Test::all();

            return Datatables::of($tests)
                ->addColumn('contract', function ($test) {
                    if (!$test->contract) return null;
                    $number = $test->contract->number;
                    if ($number) $number .= " ({$test->contract->client->name})";
                    return $number;
                })
                ->addColumn('action', function ($test) {
                    $editRoute = route('tests.edit', ['test' => $test->id]);
                    $showRoute = route('tests.show', ['test' => $test->id]);

                    $actions = '';
                    $actions .=
                        "<a href=\"{$editRoute}\" class=\"btn btn-info btn-sm float-left mr-1\" " .
                        "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Редактирование\">\n" .
                        "<i class=\"fas fa-pencil-alt\"></i>\n" .
                        "</a>\n";
                    $actions .=
                        "<a href=\"{$showRoute}\" class=\"btn btn-info btn-sm float-left mr-1\" " .
                        "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Просмотр\">\n" .
                        "<i class=\"fas fa-eye\"></i>\n" .
                        "</a>\n";
                    $actions .=
                        "<a href=\"javascript:void(0)\" class=\"btn btn-info btn-sm float-left mr-1\" " .
                        "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Удаление\" onclick=\"clickDelete({$test->id});\">\n" .
                        "<i class=\"fas fa-trash-alt\"></i>\n" .
                        "</a>\n";

                    return $actions;
                })
                ->make(true);
        }

        /**
         * Display a listing of the resource.
         *
         * @return Factory|View
         */
        public function index()
        {
            $count = Test::all()->count();
            $contracts = Contract::all();
            return view('admin.tests.index', compact('count', 'contracts'));
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return Factory|View
         */
        public function create()
        {
            $contracts = Contract::all();
            return view('admin.tests.create', compact('contracts'));
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param StoreTestRequest $request
         * @return RedirectResponse
         */
        public function store(StoreTestRequest $request)
        {
            $data = $request->all();

            $content = ['card' => []];
            // Step 1
            $auth = intval($data['auth']);
            // Step 2
            if($auth == Test::AUTH_FULL) {
                foreach ($request->keys() as $key)
                    if(strpos($key, 'ident_') !== false) {
                        $name = str_replace('ident_', '', $key);
                        $value = $request->get($key);
                        $content['card'][$name] = $value;
                    }
            }
            $options = $auth;
            // Step 3
            $options |= intval($data['mechanics']);
            if ($request->has('aux_mechanics'))
                foreach ($data['aux_mechanics'] as $result) {
                    $options |= intval($result);
                }
            // Step 4
            if ($request->has('result'))
                foreach ($data['result'] as $result) {
                    $options |= intval($result);
                };
            $content['descriptions']['show'] = $data['show_description'];
            $content['descriptions']['mail'] = $data['mail_description'];
            $content['descriptions']['client'] = $data['client_description'];

            $test = Test::create([
                'name' => $data['title'],
                'options' => $options,
                'questionset_id' => $data['sets'],
                'contract_id' => $data['contract'],
                'content' => json_encode($content),
                'key' => Test::generateKey(),
                'paid' => isset($data['paid'])
            ]);
            $test->save();
            $message = [];
            $message[] = "Тест &laquo;{$test->name}&raquo; создан";

            return redirect()->route('tests.index')->with('success', implode("<br/>", $message));
        }

        /**
         * Display the specified resource.
         *
         * @param int $id
         * @return Factory|Response|View
         */
        public function show(int $id)
        {
            return $this->edit($id, true);
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param int $id
         * @param bool $show
         * @return Factory|View
         */
        public function edit($id, bool $show = false)
        {
            $test = Test::findOrFail($id);
            $contracts = Contract::all();
            $content = json_decode($test->content, true);

//            session()->put('test_id', $id);
//            session()->put('test_show', $show);
            return view('admin.tests.edit', compact('test', 'contracts', 'show', 'content'));
        }

        /**
         * Update the specified resource in storage.
         *
         * @param UpdateTestRequest $request
         * @param int $id
         * @return RedirectResponse
         */
        public function update(UpdateTestRequest $request, int $id)
        {
            $data = $request->all();

            $content = ['card' => []];
            $auth = intval($data['auth']);
            if($auth == Test::AUTH_FULL) {
                foreach ($request->keys() as $key)
                    if(strpos($key, 'ident_') !== false) {
                        $name = str_replace('ident_', '', $key);
                        $value = $request->get($key);
                        $content['card'][$name] = $value;
                    }
            }
            $options = $auth;
            $options |= intval($data['mechanics']);
            if ($request->has('aux_mechanics'))
                foreach ($data['aux_mechanics'] as $result) {
                    $options |= intval($result);
                }
            if ($request->has('result'))
                foreach ($data['result'] as $result) {
                    $options |= intval($result);
                };
            $content['descriptions']['show'] = $data['show_description'];
            $content['descriptions']['mail'] = $data['mail_description'];
            $content['descriptions']['client'] = $data['client_description'];

            $test = Test::findOrFail($id);
            $update = [
                'name' => $data['title'],
                'options' => $options,
                'questionset_id' => $data['sets'],
                'contract_id' => $data['contract'],
                'content' => json_encode($content),
                'paid' => isset($data['paid'])
            ];
            if(!$test->key) $update['key'] = Test::generateKey();
            $test->update($update);
            $message[] = "Изменения теста &laquo;{$data['title']}&raquo; сохранены";

            return redirect()->route('tests.index')
                ->with('success', implode("<br/>", $message));
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param Request $request
         * @param int $test
         * @return bool
         */
        public function destroy(Request $request, int $test)
        {
            if ($test == 0) {
                $id = $request->id;
            } else $id = $test;

            $test = Test::findOrFail($id);
            $title = $test->name;
            $test->delete();

            event(new ToastEvent('success', '', "Тест \"$title\" удалён"));
            return true;
        }

        public function back(?string $key = null, ?string $message = null)
        {
            return CallStack::back($key, $message);
        }
    }
