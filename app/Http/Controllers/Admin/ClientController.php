<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Support\CallStack;
use App\Models\Client;
use App\Models\Role;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $count = Client::all()->count();
        return view('admin.clients.index', compact('count'));
    }

    /**
     * Process datatables ajax request.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getData(): JsonResponse
    {
        $clients = Client::all();

        return Datatables::of($clients)
            ->addColumn('contracts', function($client) {
                $count = $client->contracts->count();
                switch ($count) {
                    case 0:
                    case null:
                        return 'Нет';
                    default:
                        return $count;
                }
            })
            ->addColumn('action', function ($client) {
                $editRoute = route('clients.edit', ['client' => $client->id, 'sid' => session()->getId()]);
                $showRoute = route('clients.show', ['client' => $client->id, 'sid' => session()->getId()]);
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
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $managers = Role::all()->where('name', Role::MANAGER)->first()->users->sortBy('name')->pluck('id', 'name');
        $responsives = Role::all()->where('name', Role::CLIENT)->first()->users->sortBy('name')->pluck('id', 'name');

        return view('admin.clients.create', compact('managers', 'responsives'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreClientRequest $request
     * @return RedirectResponse|Response
     */
    public function store(StoreClientRequest $request)
    {
        $name = $request->name;
        Client::create($request->all());
        return redirect()->route('clients.index', ['sid' => ($request->has('sid') ? $request->sid : session()->getId())])->with('success', "Клиент \"{$name}\" добавлен");
    }

    /**
     * Display the specified resource.
     *
     * @param id $id
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
        $client = Client::find($id);
        $count = $client->contracts->count();
        if($count == 1) {
            $first = $client->contracts->first();
        } else $first = false;

        return view('admin.clients.edit', compact('client', 'count', 'show', 'first'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateClientRequest $request
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function update(UpdateClientRequest $request, int $id)
    {
        $name = $request->name;
        $client = Client::find($id);
        $client->update($request->all());
        return redirect()->route('clients.index', ['sid' => ($request->has('sid') ? $request->sid : session()->getId())])->with('success', "Изменения клиента \"{$name}\" сохранены");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Client $client
     * @return Response
     */
    public function destroy(Client $client)
    {
        //
    }

    public function back(?string $key = null, ?string $message = null)
    {
        return CallStack::back($key, $message);
    }
}
