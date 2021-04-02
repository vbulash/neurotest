<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\Block;
    use App\Models\Test;
    use Illuminate\Contracts\Foundation\Application;
    use Illuminate\Contracts\View\Factory;
    use Illuminate\Contracts\View\View;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Yajra\DataTables\DataTables;
    use Exception;

    class BlockController extends Controller
    {
        /**
         * Process datatables ajax request.
         *
         * @return JsonResponse
         * @throws Exception
         */
        public function getData(): JsonResponse
        {
            $blocks = Block::all();

            return Datatables::of($blocks)
                ->addColumn('order', fn($block) => $block->sort_no)
                ->addColumn('locked', fn($block) => Block::locked($block->type))
                ->addColumn('action', function ($block) {
                    $editRoute = route('blocks.edit', ['block' => $block->id]);
                    $showRoute = route('blocks.show', ['block' => $block->id]);

                    $handler = config('blocks.' . $block->type);
                    $actions = '';
                    if ($handler::editable()) {
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
                    }
                    if (!Block::locked($block->type)) {
                        $actions .=
                            "<a href=\"javascript:void(0)\" class=\"btn btn-info btn-sm float-left mr-1\" " .
                            "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Удаление\" onclick=\"clickDelete({$block->id});\">\n" .
                            "<i class=\"fas fa-trash-alt\"></i>\n" .
                            "</a>\n";
                    }
                    return $actions;
                })
                ->make(true);
        }

        /**
         * Display a listing of the resource.
         *
         * @return Response
         */
        public function index()
        {
            //
        }

        /**
         * Show the form for creating a new resource.
         *
         * @param Request $request
         * @param string $type Тип блока
         * @return bool|RedirectResponse|Response|null
         */
        public function create(Request $request, string $type)
        {
            $handler = config('blocks.' . $type);
            if (!$handler) return null;

            if ($handler::creatable()) {
                // TODO сделать визуальное создание блока
            } else {
                $test_id = session('test_id');
                if ($test_id != 0) {
                    $block = $handler::add();
                    if (!$block) return false;
                    $message = [$handler::$title . ' добавлен'];

                    $test_show = session('test_show');
                    $route = 'tests.' . ($test_show ? 'show' : 'edit');
                    return redirect()->route($route, ['test' => $test_id])
                        ->with('success', implode("<br/>", $message));;
                } else {
                    // TODO сделать пакетное добавление блока вне теста
                }
            }
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return Response
         */
        public function store(Request $request)
        {
            //
        }

        /**
         * Display the specified resource.
         *
         * @param int $id
         * @return Response
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
         * @return Application|Factory|View|Response
         */
        public function edit(int $id, bool $show = false)
        {
            $block = Block::findOrFail($id);
            $handler = config('blocks.' . $block->type);

            session()->put('block_id', $block->id);
            session()->put('block_show', $show);

            return view($handler::getEditView(), compact('block', 'show'));
        }

        /**
         * Update the specified resource in storage.
         *
         * @param Request $request
         * @param int $id
         * @return RedirectResponse|Response
         */
        public function update(Request $request, int $id)
        {
            $request->validate([
                'title' => 'required',
            ]);

            $block = Block::findOrFail($id);
            $title = $block->name;
            $block->update([
                'name' => $request->title
            ]);

            $test_id = session('test_id');
            $test_show = session('test_show');
            $route = 'tests.' . ($test_show ? 'show' : 'edit');
            return redirect()->route($route, ['test' => $test_id])
                ->with('success', "Изменения модуля &laquo;{$title}&raquo; сохранены");
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
            $block = Block::findOrFail($id);
            $title = $block->name;
            $block->delete();

            $test_id = session('test_id');
            $test_show = session('test_show');
            $route = 'tests.' . ($test_show ? 'show' : 'edit');
            return redirect()->route($route, ['test' => $test_id])->with('success', $title . " удалён");
        }

        /**
         * Проигрывание = прохождение блока теста
         *
         * @param int $id ID блока для "проигрывания"
         * @return Response Результат работы модуля в тесте на runtime
         */
        public function play(int $id): Response
        {
            //
        }

        // Helper
//        public function getTypeName(int $type): ?string
//        {
//            if (!key_exists($type, Block::types)) return null;
//            return Block::types[$type];
//        }
    }
