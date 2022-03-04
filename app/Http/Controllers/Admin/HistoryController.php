<?php

namespace App\Http\Controllers\Admin;

use App\Events\ToastEvent;
use App\Http\Controllers\Controller;
use App\Http\Support\CallStack;
use App\Models\History;
use DateTime;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;


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
            ->whereNotNull('card')
            ->whereNotNull('done')
            ->orderBy('done', 'desc');

        return Datatables::of($histories)
            ->editColumn('done', function ($history) {
                $done = DateTime::createFromFormat('Y-m-d G:i:s', $history->done);
                return $done->format('d.m.Y G:i:s');
            })
            ->editColumn('first_name', function ($history) {
                $card = json_decode($history->card, true);
                return $card['first_name'] ?? ' ';
            })
            ->editColumn('last_name', function ($history) {
                $card = json_decode($history->card, true);
                return $card['last_name'] ?? ' ';
            })
            ->editColumn('email', function ($history) {
                $card = json_decode($history->card, true);
                return $card['email'] ?? ' ';
            })
            ->editColumn('action', function ($history) {
                $params = [
                    'history' => $history->id,
                    'sid' => session()->getId()
                ];
                $editRoute = route('history.edit', $params);
                $showRoute = route('history.show', $params);
                $params = [
                    'InvId' => $history->id,
                    'List' => true,
                    'sid' => session()->getId()
                ];
                $mailRoute = route('payment.result', $params);

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
                $actions .=
                    "<a href=\"javascript:void(0)\" class=\"btn btn-info btn-sm float-left mr-1\" " .
                    "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Удаление\" onclick=\"clickDelete({$history->id})\">\n" .
                    "<i class=\"fas fa-trash-alt\"></i>\n" .
                    "</a>\n";

                if ($history->paid)
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
        return redirect()->route('history.index',
            ['sid' => ($request->has('sid') ? $request->sid : session()->getId())]);
    }

    private function getNameFromNumber($num)
    {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return $this->getNameFromNumber($num2 - 1) . $letter;
        } else {
            return $letter;
        }
    }

    public function detail()
    {
        event(new ToastEvent('info', '', "Формирование детализации тестирования..."));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $column = $this->getNameFromNumber(0);
        $row = 1;
        $sheet->setCellValue('A1', 'ID истории');
        $sheet->setCellValue('B1', 'Тестирование завершено');
        $sheet->setCellValue('C1', 'Имя');
        $sheet->setCellValue('D1', 'Фамилия');
        $sheet->setCellValue('E1', 'Наименование теста');
        $sheet->setCellValue('F1', 'Наименование набора вопросов');
        $sheet->setCellValue('G1', 'Вычисленный код');
        $sheet->freezePane('A2');

        $sql = <<< EOS
select
    h.id as hid,
    h.done as done,
    h.card->>'$.first_name' as first_name,
    h.card->>'$.last_name' as last_name,
    t.name as tname,
    qs.name as qsname,
    h.code as code,
    q.learning as qlearning,
    q.sort_no as qsort_no,
    IF(q.valueA = hs.key, 1, IF(q.valueB = hs.key, 2, IF(q.valueC = hs.key, 3, IF(q.valueD = hs.key, 4, 0)))) as pressed,
    hs.`key` as hskey
from history as h, historysteps as hs, tests as t, questionsets as qs, questions as q
where hs.history_id = h.id and h.test_id = t.id and qs.id = t.questionset_id and q.id = hs.question_id and h.done is not null and h.code is not null
order by h.id, q.sort_no
EOS;
        $view = DB::select($sql);

        $row = 1;
        $prevHid = 0;
        $column = 7;
        $maxColumn = 7;
        foreach ($view as $history) {
            if($history->hid != $prevHid) { // Новая строка
                $sheet->setCellValue('A' . ++$row, $history->hid);
                $sheet->setCellValue('B' . $row, $history->done);   // TODO форматирование даты
                $sheet->setCellValue('C' . $row, $history->first_name);
                $sheet->setCellValue('D' . $row, $history->last_name);
                $sheet->setCellValue('E' . $row, $history->tname);
                $sheet->setCellValue('F' . $row, $history->qsname);
                $sheet->setCellValue('G' . $row, $history->code);
                $column = 7;
                $prevHid = $history->hid;
            }
            if($column > $maxColumn) $maxColumn = $column;
            $sheet->setCellValue($this->getNameFromNumber($column++) . $row, $history->pressed . ' / ' . $history->hskey);
        }

        $column = 7;
        while($column <= $maxColumn) {
            $sheet->setCellValue($this->getNameFromNumber($column++) . '1', $column - 7);
        }

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        //Log::debug(urlencode(env('APP_NAME') . ' - Детализация истории.xlsx"'));
        header('Content-Disposition: attachment;filename="' . env('APP_NAME') . ' - Детализация истории.xlsx' . '"');
        header('Cache-Control: max-age=0');

        event(new ToastEvent('success', '', "Детализация тестирования сформирована"));

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $neuroprofile
     * @return bool
     */
    public function destroy(Request $request, int $history): bool
    {
        if ($history == 0) {
            $id = $request->id;
        } else $id = $history;

        $h = History::findOrFail($id);
        $h->delete();

        event(new ToastEvent('success', '', "Запись истории № {$id} удалена"));
        return true;
    }

    public function back(?string $key = null, ?string $message = null)
    {
        return CallStack::back($key, $message);
    }
}
