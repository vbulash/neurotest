<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreContractRequest;
    use App\Http\Requests\UpdateContractRequest;
    use App\Models\Client;
    use App\Models\Contract;
    use App\Models\License;
    use DateTime;
    use Exception;
    use Illuminate\Contracts\Foundation\Application;
    use Illuminate\Contracts\View\Factory;
    use Illuminate\Contracts\View\View;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Redirect;
    use Yajra\DataTables\DataTables;

    class ContractController extends Controller
    {
        /**
         * Process datatables ajax request.
         *
         * @param int|null $id ID клиента по контракту
         * @return JsonResponse
         * @throws Exception
         */
        public function getData(int $id = null): JsonResponse
        {
            if ($id) {
                $contracts = Contract::query()->where('client_id', $id);
            } else {
                $contracts = Contract::query();
            }
            return Datatables::of($contracts)
                ->editColumn('start', function ($contract) {
                    $start = DateTime::createFromFormat('Y-m-d', $contract->start);
                    return $start->format('d.m.Y');
                })
                ->editColumn('end', function ($contract) {
                    $end = DateTime::createFromFormat('Y-m-d', $contract->end);
                    return $end->format('d.m.Y');
                })
                ->editColumn('status', function ($contract) {
                    if ($contract->status & Contract::ACTIVE) {
                        return 'Активный';
                    } elseif ($contract->status & Contract::INACTIVE) {
                        return 'Неактивный';
                    } elseif ($contract->status & Contract::COMPLETE_BY_DATE) {
                        return 'Истёк';
                    } elseif ($contract->status & Contract::COMPLETE_BY_COUNT) {
                        return 'Закончились лицензии';
                    }
                    return '';
                })
                ->editColumn('action', function ($contract) {
                    $editRoute = route('contracts.edit', ['contract' => $contract->id]);
                    $showRoute = route('contracts.show', ['contract' => $contract->id]);
                    return
                        "<a href=\"{$editRoute}\" class=\"btn btn-info btn-sm float-left mr-1\" " .
                        "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Редактирование\">\n" .
                        "<i class=\"fas fa-pencil-alt\"></i>\n" .
                        "</a>\n" .
                        "<a href=\"{$showRoute}\" class=\"btn btn-info btn-sm float-left mr-1\" " .
                        "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Просмотр\">\n" .
                        "<i class=\"fas fa-eye\"></i>\n" .
                        "</a>\n";
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
         * @param int|null $id Клиент контракта
         * @return Application|Factory|View|Response
         */
        public function create(int $id = null)
        {
            if ($id) {
                $client = Client::find($id);
            } else {
                $client = null;
            }
            return view('admin.contracts.create', compact('client'));
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param StoreContractRequest $request
         * @return Application|Factory|View|RedirectResponse|Response
         */
        public function store(StoreContractRequest $request)
        {
            $data = $request->all();
            $start = DateTime::createFromFormat('d.m.Y', $request->start);
            $data['start'] = $start->format('Y-m-d');
            $end = DateTime::createFromFormat('d.m.Y', $request->end);
            $data['end'] = $end->format('Y-m-d');
            $data['mkey'] = Contract::generateKey();

            $today = new DateTime();
            $status = Contract::INACTIVE;
            if(($today >= $start) && ($today < $end)) $status = Contract::ACTIVE;
            if($today > $end) $status = Contract::COMPLETE_BY_DATE;

            $data['status'] = $status;
            $data['options'] = 0;
            $contract = Contract::create($data);

            // Сгенерировать license_count свободных лицензий под текущий контракт
            $licenses = License::factory()->count($contract->license_count)->make([
                'contract_id' => $contract->id
            ]);
            if($licenses) {
                $licenses->each(function ($item, $key) {
                    $item->save();
                });
            }

            return redirect()->route('clients.index')
                ->with('success', "Контракт &laquo;{$contract->number}&raquo; добавлен и " .
                    "сгенерированы лицензии контракта ({$contract->license_count})");
        }

        /**
         * Display the specified resource.
         *
         * @param int $id
         * @return Response
         */
        public function show($id)
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
            $contract = Contract::find($id);
            $start = DateTime::createFromFormat('Y-m-d', $contract->start);
            $contract->start = $start->format('d.m.Y');
            $end = DateTime::createFromFormat('Y-m-d', $contract->end);
            $contract->end = $end->format('d.m.Y');

            $statuses = License::all()->where('contract_id', $id)->groupBy('status')->toArray();
            $statistics = [
                'free' => (array_key_exists(License::FREE, $statuses) ? count($statuses[License::FREE]) : 0),
                'using' => (array_key_exists(License::USING, $statuses) ? count($statuses[License::USING]) : 0),
                'used' => (array_key_exists(License::USED, $statuses) ? count($statuses[License::USED]) : 0),
                'broken' => (array_key_exists(License::BROKEN, $statuses) ? count($statuses[License::BROKEN]) : 0),
            ];

            switch($contract->status) {
                case Contract::INACTIVE:
                    $status = 'Неактивен (дата начала в будущем)';
                    break;
                case Contract::ACTIVE:
                    $status = 'Исполняется';
                    break;
                case Contract::COMPLETE_BY_DATE:
                    $status = 'Завершен по дате';
                    break;
                case Contract::COMPLETE_BY_COUNT:
                    $status = 'Завершен, закончились свободные лицензии';
                    break;
                default:
                    $status = 'Неизвестный статус контракта';
                    break;
            }

            return view('admin.contracts.edit',
                compact('contract', 'statistics', 'status', 'show'));
        }

        /**
         * Update the specified resource in storage.
         *
         * @param UpdateContractRequest $request
         * @param int $id
         * @return Application|Factory|View|Response
         */
        public function update(UpdateContractRequest $request, $id)
        {
            $data = $request->all();
            $start = DateTime::createFromFormat('d.m.Y', $request->start);
            $data['start'] = $start->format('Y-m-d');
            $end = DateTime::createFromFormat('d.m.Y', $request->end);
            $data['end'] = $end->format('Y-m-d');

            $today = new DateTime();
            $status = Contract::INACTIVE;
            if(($today >= $start) && ($today < $end)) $status = Contract::ACTIVE;
            if($today > $end) $status = Contract::COMPLETE_BY_DATE;
            $data['status'] = $status;

            $contract = Contract::find($id);
            $contract->update($data);

            $today = new DateTime();

            $count = Client::all()->count();
            return view('admin.clients.index', compact('count'))
                ->with('success', "Изменения контракта &laquo;{$contract->number}&raquo; сохранены");
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         * @return Response
         */
        public function destroy($id)
        {
            //
        }

        /**
         * Получение мастер-ключа по ID контракта
         *
         * @param Request $request
         * @return string Мастер-ключ
         */
        public function getMKey(Request $request): string {
            $contract = Contract::findOrFail($request->id);
            return $contract->mkey;
        }
    }
