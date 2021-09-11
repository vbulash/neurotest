<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Support\CallStack;
use App\Models\History;
use DateTime;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

class HistoryController extends Controller
{
    /**
     * Process datatables ajax request.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getData(): JsonResponse
    {
        $histories = History::query()
            ->orderBy('done', 'desc');

        return Datatables::of($histories)
            ->editColumn('done', function ($history) {
                $done = DateTime::createFromFormat('Y-m-d G:i:s', $history->done);
                return $done->format('d.m.Y G:i:s');
            })
            ->editColumn('first_name', function ($history) {
                $card = json_decode($history->card);
                return $card->first_name;
            })
            ->editColumn('last_name', function ($history) {
                $card = json_decode($history->card);
                return $card->last_name;
            })
            ->editColumn('email', function ($history) {
                $card = json_decode($history->card);
                return $card->email;
            })
            ->editColumn('action', function ($history) {
                $editRoute = route('history.edit', ['history' => $history->id]);
                $showRoute = route('history.show', ['history' => $history->id]);
                $mailRoute = route('payment.result', ['InvId' => $history->id, 'List' => true]);

                $actions =
                    "<a href=\"{$editRoute}\" class=\"btn btn-info btn-sm float-left mr-1\" " .
                    "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Редактирование\">\n" .
                    "<i class=\"fas fa-pencil-alt\"></i>\n" .
                    "</a>\n";
                $actions .=
                    "<a href=\"{$showRoute}\" class=\"btn btn-info btn-sm float-left mr-5\" " .
                    "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Просмотр\">\n" .
                    "<i class=\"fas fa-eye\"></i>\n" .
                    "</a>\n";
                if($history->paid)
                $actions .=
                    "<a href=\"$mailRoute\" class=\"btn btn-info btn-sm float-left mr-1\" " .
                    "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Повтор письма\" onclick=\"clickMail({$history->id})\">\n" .
                    "<i class=\"fas fa-envelope\"></i>\n" .
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
        $count = (History::all()->count() > 0);
        return view('admin.history.index', compact('count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
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
     * @return Factory|\Illuminate\View\View
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
     * @return Factory|\Illuminate\View\View
     */
    public function edit($id, bool $show = false)
    {
        $history = History::findOrFail($id);
        return view('admin.history.edit', compact('history', 'show'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);
        $history = History::findOrFail($id);
        $data['paid'] = $request->has('paid');
        $card = $data;
        unset($card['paid']);
        $history->update([
            'card' => json_encode($card),
            'paid' => $data['paid']
        ]);

        session()->put('success', "Запись истории прохождения тестов $history->id обновлена");
        return redirect()->route('history.index');
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

    public function back(?string $key = null, ?string $message = null)
    {
        return CallStack::back($key, $message);
    }
}
