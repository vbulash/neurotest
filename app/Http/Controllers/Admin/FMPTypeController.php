<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FMPTypeRequest;
use App\Http\Support\CallStack;
use App\Http\Support\Stack;
use App\Models\FMPType;
use App\Models\Neuroprofile;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\DocBlock\Tags\Source;
use Yajra\DataTables\DataTables;

class FMPTypeController extends Controller
{
    /**
     * Process datatables ajax request.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getData(): JsonResponse
    {
        $fmptypes = FMPType::all();

        return Datatables::of($fmptypes)
            ->addColumn('action', function ($fmptype) {
                $editRoute = route('fmptypes.edit', ['fmptype' => $fmptype->id]);
                $showRoute = route('fmptypes.show', ['fmptype' => $fmptype->id]);
                $copyRoute = route('fmptypes.copy', ['fmptype' => $fmptype->id]);
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
                    "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Удаление\" onclick=\"clickDelete({$fmptype->id})\">\n" .
                    "<i class=\"fas fa-trash-alt\"></i>\n" .
                    "</a>\n";
                $actions .=
                    "<a href=\"{$copyRoute}\" class=\"btn btn-info btn-sm float-left ml-3\" " .
                    "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Дублирование\">\n" .
                    "<i class=\"fas fa-copy\"></i>\n" .
                    "</a>\n";

                return $actions;
            })
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $fmptypes = FMPType::all();

        return view('admin.fmptypes.index', compact('fmptypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function create(Request $request)
    {
        return view('admin.fmptypes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FMPTypeRequest $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function store(FMPTypeRequest $request)
    {
        $fmptype = FMPType::create([
            'name' => $request->name,
            'cluster' => $request->profiletype
        ]);
        $name = $request->name;
        $fmptype->save();

        session()->put('success', "Тип описания ФМП &laquo;{$name}&raquo; создан");

        $profiles = [];
        $show = false;
        return view('admin.fmptypes.edit', compact('fmptype', 'profiles', 'show'));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function show(Request $request, int $id)
    {
        $show = true;
        $fmptype = $id;
        return redirect()->route('fmptypes.edit', compact('fmptype', 'show'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(Request $request, int $id)
    {
        $fmptype = FMPType::findOrfail($id);
        $profiles = Neuroprofile::all()->where('fmptype_id', $fmptype->id);

        $show = $request->has('show') ? $request->show : false;
        return view('admin.fmptypes.edit', compact('fmptype', 'profiles', 'show'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FMPTypeRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(FMPTypeRequest $request, $id)
    {
        $fmptype = FMPtype::findOrFail($id);
        $name = $fmptype->name;
        $fmptype->update([
            'name' => $request->name,
            'cluster' => $request->profiletype
        ]);

        return CallStack::back('success', "Изменения типа описания ФМП &laquo;{$name}&raquo; сохранены");
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

        $fmptype = FMPType::findOrFail($id);
        $name = $fmptype->name;
        $fmptype->delete();

        return CallStack::back('success', "Тип описания ФМП {$name} удален");
    }

    public function copy(Request $request, int $fmptype)
    {
        $source = FMPType::findOrFail($fmptype);

        // Дублирование типа описания
        $target = $source->replicate();
        $target->name = $source->name . ' (Копия)';
        $target->save();

        // Дублирования нейропрофилей в типе
        foreach ($source->profiles as $profile) {
            $targetProfile = $profile->replicate();
            $target->profiles()->save($targetProfile);

            // Дубирование блоков профиля
            foreach ($profile->blocks as $block) {
                $targetBlock = $block->replicate();
                $targetProfile->blocks()->save($targetBlock);
            }
        }

        return redirect()->route('fmptypes.edit', ['fmptype' => $target->id])
            ->with('success', "Тип описания &laquo;{$source->name}&raquo; скопирован");
    }

    public function back(?string $key = null, ?string $message = null)
    {
        return CallStack::back($key, $message);
    }
}
