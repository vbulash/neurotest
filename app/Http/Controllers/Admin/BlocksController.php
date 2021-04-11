<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Block;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use Exception;

class BlocksController extends Controller
{
    /**
     * Process datatables ajax request.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getData(): JsonResponse
    {
        $blocks = Block::all();

        return Datatables::of($blocks)
            ->addColumn('action', function ($block) {
                $editRoute = route('blocks.edit', ['block' => $block->id]);
                $showRoute = route('blocks.show', ['block' => $block->id]);
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
                    "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Удаление\" onclick=\"clickDelete({$block->id})\">\n" .
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
        $blocks = Block::all();
        return view('admin.blocks.index', compact('blocks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        return view('admin.blocks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
