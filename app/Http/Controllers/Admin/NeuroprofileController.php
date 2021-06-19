<?php

    namespace App\Http\Controllers\Admin;

    use App\Events\ToastEvent;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\NeuroprofileRequest;
    use App\Http\Support\CallStack;
    use App\Models\Block;
    use App\Models\FMPType;
    use App\Models\Neuroprofile;
    use Illuminate\Contracts\Foundation\Application;
    use Illuminate\Contracts\View\Factory;
    use Illuminate\Contracts\View\View;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Yajra\DataTables\DataTables;
    use Exception;

    class NeuroprofileController extends Controller
    {
        /**
         * Process datatables ajax request.
         *
         * @param int $fmptype_id
         * @return JsonResponse
         * @throws Exception
         */
        public function getData(int $fmptype_id = 0): JsonResponse
        {
            if ($fmptype_id == 0) {
                $profiles = Neuroprofile::all();
            } else {
                $profiles = Neuroprofile::all()->where('fmptype_id', $fmptype_id);
            }

            return Datatables::of($profiles)
                ->editColumn('fmptype', function ($profile) {
                    return $profile->fmptype->name;
                })
                ->addColumn('action', function ($profile) use ($fmptype_id) {
                    $params = ['neuroprofile' => $profile->id];
                    if ($fmptype_id != 0) $params['fmptype'] = $fmptype_id;

                    $editRoute = route('neuroprofiles.edit', $params);
                    $showRoute = route('neuroprofiles.show', $params);
                    $actions =
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
                        "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Удаление\" onclick=\"clickDelete({$profile->id})\">\n" .
                        "<i class=\"fas fa-trash-alt\"></i>\n" .
                        "</a>\n";

                    return $actions;
                })
                ->make(true);
        }

        /**
         * Display a listing of the resource.
         *
         * @return Application|Factory|View|Response
         */
        public function index()
        {
            $profiles = Neuroprofile::all();
            return view('admin.profiles.index', compact('profiles'));
        }

        /**
         * Show the form for creating a new resource.
         *
         * @param Request $request
         * @return Application|Factory|View|Response
         */
        public function create(Request $request)
        {
            $embedded = false;
            if($request->has('fmptype_id')) {
                $fmptypes = FMPType::findOrFail($request->fmptype_id);
                $embedded = true;
            } else {
                $fmptypes = FMPType::all();
            }
            return view('admin.profiles.create', compact('fmptypes', 'embedded'));
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return Application|Factory|View|RedirectResponse|Response
         */
        public function store(NeuroprofileRequest $request)
        {
            $fmptypes = FMPType::find($request->fmptype);
            $embedded = true;
            $blocks = [];
            $show = false;

            $profile = Neuroprofile::create([
                'code' => $request->code,
                'name' => $request->name,
                'fmptype_id' => $request->fmptype
            ]);
            $profile->save();

            return view('admin.profiles.edit', compact('profile', 'fmptypes', 'blocks', 'show', 'embedded'));
        }

        /**
         * Display the specified resource.
         *
         * @param Request $request
         * @param int $id
         * @return RedirectResponse|Response
         */
        public function show(Request $request, int $id)
        {
            $show = true;
            $neuroprofile = $id;
            return redirect()->route('neuroprofiles.edit', compact('neuroprofile', 'show'));
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param Request $request
         * @param int $id
         * @return Application|Factory|View|Response
         */
        public function edit(Request $request, int $id)
        {
            $profile = Neuroprofile::findOrFail($id);
            $embedded = false;
            if ($request->has('fmptype'))
                if ($request->fmptype == 0) {
                    $fmptypes = FMPType::all();
                } else {
                    $fmptypes = FMPType::find($request->fmptype);
                    $embedded = true;
                }
            else $fmptypes = FMPType::all();
            $blocks = Block::all()->where('neuroprofile_id', $profile->id);
            $show = $request->has('show') ? $request->show : false;

            return view('admin.profiles.edit', compact('profile', 'fmptypes', 'blocks', 'show', 'embedded'));
        }

        /**
         * Update the specified resource in storage.
         *
         * @param NeuroprofileRequest $request
         * @param int $id
         * @return RedirectResponse|Response
         */
        public function update(NeuroprofileRequest $request, $id)
        {
            $profile = Neuroprofile::findOrFail($id);
            $profile->update([
                'code' => $request->code,
                'name' => $request->name,
                'fmptype_id' => $request->fmptype
            ]);
            return CallStack::back('success', "Изменения нейропрофиля &laquo;{$request->name}&raquo; сохранены");
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param Request $request
         * @param int $neuroprofile
         * @return bool
         */
        public function destroy(Request $request, int $neuroprofile)
        {
            if ($neuroprofile == 0) {
                $id = $request->id;
            } else $id = $neuroprofile;

            $profile = Neuroprofile::findOrFail($id);
            $name = $profile->name;
            $profile->delete();

            event(new ToastEvent('success', '', "Нейропрофиль &laquo;{$name}&raquo; удален"));
            return true;
        }

        public function back(?string $key = null, ?string $message = null)
        {
            return CallStack::back($key, $message);
        }
    }
