<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Requests\StoreTestRequest;
    use App\Http\Requests\UpdateTestRequest;
    use App\Models\Contract;
    use Exception;
    use App\Models\Test;
    use App\Models\Block;
    use App\Http\Controllers\Controller;

    //use App\Http\Requests\StoreCategory;
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

        public function changeStatus(int $id, string $newType)
        {
            $test = Test::findOrFail($id);
            if ($test->type == $newType) return false;
            $test->type = $newType;
            $test->update();

            return redirect()->route('tests.index')
                ->with('success', "Тип теста &laquo;{$test->name}&raquo; изменен на &laquo:{$newType}&raquo;");
        }

        /**
         * Display a listing of the resource.
         *
         * @return Factory|View
         */
        public function index()
        {
            if (session()->exists('block_id'))
                session()->forget('block_id');

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
            $options = intval($data['auth']);
            if ($request->has('result'))
                foreach ($data['result'] as $result) {
                    $options |= intval($result);
                }
            $options |= intval($data['mechanics']);
            if ($request->has('aux_mechanics'))
                foreach ($data['aux_mechanics'] as $result) {
                    $options |= intval($result);
                }
            dd($options);

            $test = Test::create([
                'name' => $data['title'],
                'type' => $data['kind'],
                'timeout' => $data['timeout'],
                'options' => $options,
                'contract_id' => $data['contract'],
                'questionset_id' => 5,  // TODO Только для отладки
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
            dd($id);
            $test = Test::findOrFail($id);
            $contracts = Contract::all();
            $blocks = $test->blocks->all();

            session()->put('test_id', $id);
            session()->put('test_show', $show);
            return view('admin.tests.edit', compact('test', 'contracts', 'blocks', 'show'));
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
            $test = Test::find($id);
            $test->update([
                'name' => $data['title'],
                'type' => $data['kind'],
                'timeout' => $data['timeout'],
                'options' => $data['auth'] | $data['result'],
            ]);
            $message[] = "Изменения теста &laquo;{$data['title']}&raquo; сохранены";

            return redirect()->route('tests.index')
                ->with('success', implode("<br/>", $message));
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param Request $request
         * @param int $id
         * @return RedirectResponse
         */
        public function destroy(Request $request, int $id)
        {
            if ($id == 0)
                $id = $request->delete_id;
            $test = Test::findOrFail($id);
            $title = $test->name;
            $test->delete();
            return redirect()->route('tests.index')->with('success', "Тест \"$title\" удалён");
        }
    }
