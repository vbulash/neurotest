<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
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

class QuestionsController extends Controller
{
    /**
     * Process datatables ajax request.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getData(): JsonResponse
    {
        $set_id = session('set_id');
        $questions = Question::all()->where('questionset_id', $set_id)->sortBy('sort_no');

        return Datatables::of($questions)
            ->addColumn('action', function ($question) {
                $editRoute = route('questions.edit', ['question' => $question->id]);
                $showRoute = route('questions.show', ['question' => $question->id]);
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
                    "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Удаление\" onclick=\"clickDelete({$question->id})\">\n" .
                    "<i class=\"fas fa-trash-alt\"></i>\n" .
                    "</a>\n";

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
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $set_id = session('set_id');
        $set = QuestionSet::findOrFail($set_id);

        return view('admin.questions.create', compact('set'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreQuestionRequest $request
     * @return RedirectResponse|Response
     */
    public function store(StoreQuestionRequest $request)
    {
        $set_id = session('set_id');
        $set = QuestionSet::findOrFail($set_id);

        $data = $request->all();
        $data['sort_no'] = $set->questions()->count() + 1;

        $letters = ['A', 'B', 'C', 'D'];
        for($imageNo = 0; $imageNo < $set->quantity; $imageNo++) {
            $field = 'image' . $letters[$imageNo];
            $data[$field] = Question::uploadImage($request, $field);
        }
        $question = Question::create($data);
        $question->save();

        $set_show = session('set_show');
        $route = 'sets.' . ($set_show ? 'show' : 'edit');
        return redirect()->route($route, ['set' => $set_id])->with('success', "Вопрос № {$data['sort_no']} добавлен");
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function show(Request $request, int $id)
    {
        return $this->edit($request, $id, true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @param bool $show
     * @return Application|Factory|View|Response
     */
    public function edit(Request $request, int $id, bool $show = false)
    {
        $set_id = session('set_id');
        $set = QuestionSet::findOrFail($set_id);
        $question = Question::findOrFail($id);

        return view('admin.questions.edit', compact('set', 'question', 'show'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return RedirectResponse|Response
     */
    public function update(Request $request, int $id)
    {
        $set_id = session('set_id');
        $set = QuestionSet::findOrFail($set_id);
        $question = Question::findOrFail($id);
        $qa = $question->toArray();

        $data = $request->all();
        $letters = ['A', 'B', 'C', 'D'];
        for($imageNo = 0; $imageNo < $set->quantity; $imageNo++) {
            $field = 'image' . $letters[$imageNo];
            if($request->has($field))
                $data[$field] = Question::uploadImage($request, $field, $qa[$field]);
        }
        $question->update($data);

        $set_show = session('set_show');
        $route = 'sets.' . ($set_show ? 'show' : 'edit');
        return redirect()->route($route, ['set' => $set_id])->with('success', "Изменения вопроса № {$question->sort_no} сохранены");
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
        $question = Question::findOrFail($id);
        $sort_no = $question->sort_no;
        $question->delete();

        $set_id = session('set_id');
        $set_show = session('set_show');
        $route = 'sets.' . ($set_show ? 'show' : 'edit');
        return redirect()->route($route, ['set' => $set_id])->with('success', "Вопрос № {$data['sort_no']} удалён");
    }
}
