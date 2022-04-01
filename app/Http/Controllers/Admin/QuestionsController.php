<?php

namespace App\Http\Controllers\Admin;

use App\Events\ToastEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Models\FileLink;
use App\Models\Question;
use App\Models\QuestionSet;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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
        $first = $questions->first()->id;
        $last = $questions->last()->id;

        return Datatables::of($questions)
            ->addColumn('thumb', function ($question) {
                $data = [];
                $letters = ['A', 'B', 'C', 'D'];
                foreach ($letters as $letter) {
                    $image = $question->getAttributeValue('image' . $letter);
                    if ($image) $data[] = '/uploads/' . $image;
                }
                return ($data ? json_encode($data) : '');
            })
            ->addColumn('key', function ($question) {
                //if($question->learning) return '';  // У учебных вопросов нет ключа
                $data = [];
                if($question->qset->quantity == QuestionSet::IMAGES2)
                    $letters = ['A', 'B'];
                elseif($question->qset->quantity == QuestionSet::IMAGES4)
                    $letters = ['A', 'B', 'C', 'D'];
                foreach ($letters as $letter) {
                    $value = $question->getAttributeValue('value' . $letter);
                    $data[] = ($value ?: '--');
                }
                return ($data ? implode(' | ', $data) : '');
            })
            ->addColumn('action', function ($question) use ($first, $last) {
                $params = [
                    'question' => $question->id,
                    'sid' => session()->getId()
                ];
                $editRoute = route('questions.edit', $params);
                $showRoute = route('questions.show', $params);
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
                    "<a href=\"javascript:void(0)\" class=\"btn btn-info btn-sm float-left mr-5\" " .
                    "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Удаление\" onclick=\"clickDelete({$question->id})\">\n" .
                    "<i class=\"fas fa-trash-alt\"></i>\n" .
                    "</a>\n";

                if ($question->id != $first)
                    $actions .=
                        "<a href=\"javascript:void(0)\" class=\"btn btn-info btn-sm float-left mr-1\" " .
                        "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Выше\" onclick=\"clickUp({$question->id})\">\n" .
                        "<i class=\"fas fa-arrow-up\"></i>\n" .
                        "</a>\n";
                if ($question->id != $last)
                    $actions .=
                        "<a href=\"javascript:void(0)\" class=\"btn btn-info btn-sm float-left\" " .
                        "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Ниже\" onclick=\"clickDown({$question->id})\">\n" .
                        "<i class=\"fas fa-arrow-down\"></i>\n" .
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
        for ($imageNo = 0; $imageNo < $set->quantity; $imageNo++) {
            // Картинка
            $field = 'image' . $letters[$imageNo];
            $mediaPath = Question::uploadImage($request, $field);
            if ($mediaPath) FileLink::link($mediaPath);
            $data[$field] = $mediaPath;
            // Ключ
            $field = 'value' . $letters[$imageNo];
            if($request->has($field)) {
                $value = $request->input($field);
                if ($value == Question::EMPTY_VALUE) $value = null;
                $data[$field] = $value;
            }
        }

        $data['learning'] = $request->has('learning');

        $question = Question::create($data);
        $question->save();

        $set_show = session('set_show');
        $route = 'sets.' . ($set_show ? 'show' : 'edit');
        return redirect()->route($route, [
            'set' => $set_id,
            'sid' => ($request->has('sid') ? $request->sid : session()->getId())
        ])->with('success', "Вопрос № {$data['sort_no']} добавлен");
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
     * @param int $id
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
        for ($imageNo = 0; $imageNo < $set->quantity; $imageNo++) {
            // Картинка
            $field = 'image' . $letters[$imageNo];
            if ($request->has($field)) {
                $mediaPath = Question::uploadImage($request, $field, $qa[$field]);
                if ($mediaPath) FileLink::link($mediaPath);
                $data[$field] = $mediaPath;
            }
            // Ключ
            $field = 'value' . $letters[$imageNo];
            if($request->has($field)) {
                $value = $request->input($field);
                if ($value == Question::EMPTY_VALUE) $value = null;
                $data[$field] = $value;
            }
        }

        $data['learning'] = $request->has('learning');

        $question->update($data);

        $set_show = session('set_show');
        $route = 'sets.' . ($set_show ? 'show' : 'edit');
        return redirect()->route($route, [
            'set' => $set_id,
            'sid' => ($request->has('sid') ? $request->sid : session()->getId())
        ])->with('success', "Изменения вопроса № {$question->sort_no} сохранены");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $question
     * @return bool
     */
    public function destroy(Request $request, int $question)
    {
        if ($question == 0) {
            $id = $request->id;
        } else $id = $question;
        $question = Question::findOrFail($id);
        $sort_no = $question->sort_no;

        foreach (['imageA', 'imageB', 'imageC', 'imageD'] as $imageField) {
            if (!$imageField) continue;

            $mediaPath = $question->getAttributeValue($imageField);
            if ($mediaPath)
                if (FileLink::unlink($mediaPath))
                    Storage::delete($mediaPath);
        }
        $question->delete();
        event(new ToastEvent('success', 'Работа с вопросами тестов', "Вопрос № {$sort_no} удалён"));
        return true;
    }

    private function move(int $id, bool $up)
    {
        //event(new ToastEvent('info', 'Работа с вопросами тестов', 'Смена позиции вопроса в списке...'));
        $question = Question::findOrFail($id);

        $set = $question->qset()->pluck('id')->first();
        $questions = Question::all()->where('questionset_id', $set)->sortBy('sort_no')->pluck('sort_no', 'id')->toArray();

        $indexes = array_keys($questions);

        $currentPos = array_search($question->id, $indexes);
        $currentID = $question->id;
        $currentOrder = $question->sort_no;
        $current = Question::findOrFail($currentID);

        $targetPos = ($up ? $currentPos - 1 : $currentPos + 1);
        $targetID = $indexes[$targetPos];
        $targetOrder = $questions[$targetID];
        $target = Question::findOrFail($targetID);

        // Обмен sort_no в 2 записях в рамках транзакции
        DB::transaction(function () use ($current, $target, $currentOrder, $targetOrder) {
            $current->update([
                'sort_no' => $targetOrder
            ]);
            $target->update([
                'sort_no' => $currentOrder
            ]);
        });
    }

    /**
     * Move question up on sort order
     *
     * @param Request $request
     * @param int $id
     * @return bool
     */
    public function up(Request $request)
    {
        $id = $request->id;
        $this->move($id, true);
        event(new ToastEvent('success', 'Работа с вопросами тестов', 'Вопрос перемещен ближе к началу списка'));

        return true;
    }

    /**
     * Move question down on sort order
     *
     * @param Request $request
     * @param int $id
     * @return bool
     */
    public function down(Request $request)
    {
        $id = $request->id;
        $this->move($id, false);
        event(new ToastEvent('success', 'Работа с вопросами тестов', 'Вопрос перемещен ближе к концу списка'));

        return true;
    }


//    public function copy(int $qid, int $setId, bool $massCopy = true)
//    {
//        $source = Question::findOrFail($qid);
//        $target = $source->copyToSet($setId);
//        // TODO отработать индивидуальное копирование вопросов (из GUI)
//        if($massCopy) return $target->id;
//    }

    public function back(?string $key = null, ?string $message = null)
    {
		session()->put($key, $message);
        return Redirect::back();
    }
}
