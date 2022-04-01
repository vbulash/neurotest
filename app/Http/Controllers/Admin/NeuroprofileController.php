<?php

    namespace App\Http\Controllers\Admin;

    use App\Events\ToastEvent;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\NeuroprofileRequest;
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
	use Illuminate\Support\Facades\Redirect;
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
                    $params = [
                        'neuroprofile' => $profile->id,
                        'sid' => session()->getId()
                    ];
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
        public function index(): View|Factory|Response|Application
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
        public function create(Request $request): Factory|View|Response|Application
        {
            $embedded = false;
            if($request->has('fmptype_id')) {
                $fmptypes = FMPType::findOrFail($request->fmptype_id);
                $embedded = true;
                $codes = $this->filterNew($request->fmptype_id);
            } else {
                $fmptypes = FMPType::all();
                $codes = $this->filterNew($fmptypes->first()->id);
            }
            return view('admin.profiles.create', compact('fmptypes', 'embedded', 'codes'));
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return Application|Factory|View|RedirectResponse|Response
         */
        public function store(NeuroprofileRequest $request): View|Factory|Response|RedirectResponse|Application
        {
            $show = false;
            $profile = Neuroprofile::create([
                'code' => $request->code,
                'name' => $request->name,
                'fmptype_id' => $request->fmptype
            ]);
            $profile->save();
            $neuroprofile = $profile->id;
            $sid = ($request->has('sid') ? $request->sid : session()->getId());

            return redirect()->route('neuroprofiles.edit', compact('neuroprofile', 'show', 'sid'))
                ->with('success', "Нейропрофиль &laquo;{$profile->name}&raquo; создан");
        }

        /**
         * Display the specified resource.
         *
         * @param Request $request
         * @param int $id
         * @return RedirectResponse|Response
         */
        public function show(Request $request, int $id): Response|RedirectResponse
        {
            $show = true;
            $neuroprofile = $id;
            $sid = ($request->has('sid') ? $request->sid : session()->getId());
            return redirect()->route('neuroprofiles.edit', compact('neuroprofile', 'show', 'sid'));
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param Request $request
         * @param int $id
         * @return Application|Factory|View|Response
         */
        public function edit(Request $request, int $id): View|Factory|Response|Application
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
        public function update(NeuroprofileRequest $request, $id): Response|RedirectResponse
        {
            $profile = Neuroprofile::findOrFail($id);
            $profile->update([
                'code' => $request->code,
                'name' => $request->name,
                'fmptype_id' => $request->fmptype
            ]);

			session()->put('success', "Изменения нейропрофиля &laquo;{$request->name}&raquo; сохранены");
            return Redirect::back();
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param Request $request
         * @param int $neuroprofile
         * @return bool
         */
        public function destroy(Request $request, int $neuroprofile): bool
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

        public function back(?string $key = null, ?string $message = null): ?RedirectResponse
        {
			session()->put($key, $message);
            return Redirect::back();
        }

        public function filterNew(int $fmptype_id): bool|string|null
        {
            $allCodes = [
                'OV' => true,
                'OI' => true,
                'OA' => true,
                'OO' => true,
                'PA' => true,
                'PP' => true,
                'PK' => true,
                'PR' => true,
                'CS' => true,
                'CI' => true,
                'CO' => true,
                'CV' => true,
                'BD' => true,
                'BH' => true,
                'BP' => true,
                'BO' => true
            ];
            if($fmptype_id == 0) {
                return json_encode($allCodes);
            } else {
                $profiles = Neuroprofile::all()->where('fmptype_id', $fmptype_id);
                if($profiles->first()) {
                    $slot = false;
                    foreach ($profiles as $profile) {
                        $allCodes[$profile->code] = false;
                    }
                    foreach ($allCodes as $key => $value) {
                        if($value) {
                            $slot = true;
                            break;
                        }
                    }
                    return ($slot ? json_encode($allCodes) : null);
                } else return json_encode($allCodes);
            }
        }
    }
