<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSetRequest;
use App\Models\Client;
use App\Models\Question;
use App\Models\QuestionSet;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Exception;

class QuestionSetController extends Controller
{
    /**
     * Process datatables ajax request.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getData(): JsonResponse
    {
        $sets = QuestionSet::all();

        return Datatables::of($sets)
            ->editColumn('type', function ($set) {
                return QuestionSet::types[$set->type];
            })
            ->addColumn('questions', function ($set) {
                return $set->questions()->count();
            })
            ->addColumn('action', function ($set) {
                $editRoute = route('sets.edit', ['set' => $set->id]);
                $showRoute = route('sets.show', ['set' => $set->id]);
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
                    "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Удаление\" onclick=\"clickDelete({$set->id})\">\n" .
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
        $count = QuestionSet::all()->count();
        return view('admin.sets.index', compact('count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $clients = Client::all();
        return view('admin.sets.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSetRequest $request
     * @return RedirectResponse|Response
     */
    public function store(StoreSetRequest $request)
    {
        $data = $request->all();
        $data['client_id'] = ($data['type'] == QuestionSet::TYPE_EXACT ? $data['client_id'] : null);
        $data['options'] =
            ($request->has('eye-tracking') ? QuestionSet::OPTIONS_EYE_TRACKING : 0) |
            ($request->has('mouse-tracking') ? QuestionSet::OPTIONS_MOUSE_TRACKING : 0);
        $set = QuestionSet::create($data);
        $set->save();

        return redirect()->route('sets.index')->with('success', "Набор вопросов &laquo;{$data['name']}&raquo; добавлен");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
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
        session()->put('set_id', $id);
        session()->put('set_show', $show);

        $set = QuestionSet::findOrFail($id);
        $clients = Client::all();
        $questions_count = Question::all()->where('questionset_id', $id)->count();
        return view('admin.sets.edit', compact('set', 'clients', 'questions_count', 'show'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreSetRequest $request
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function update(StoreSetRequest $request, int $id)
    {
        $question = QuestionSet::findOrFail($id);

        $data = $request->all();
        $data['client_id'] = ($data['type'] == QuestionSet::TYPE_EXACT ? $data['client_id'] : null);
        $data['options'] =
            ($request->has('eye-tracking') ? QuestionSet::OPTIONS_EYE_TRACKING : 0) |
            ($request->has('mouse-tracking') ? QuestionSet::OPTIONS_MOUSE_TRACKING : 0);

        $set = QuestionSet::findOrFail($id);
        $set->update($data);

        return redirect()->route('sets.index')
            ->with('success', "Изменения набора вопросов &laquo;{$question->name}&raquo; сохранены");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function destroy(Request $request, int $id)
    {
        if ($id == 0)
            $id = $request->delete_id;
        $set = QuestionSet::findOrFail($id);
        $name = $set->name;
        $set->delete();

        return redirect()->route('sets.index')->with('success', "Набор вопросов &laquo;{$name}&raquo; удалён");
    }
}
