<?php

namespace App\Http\Controllers\Admin;

use App\Events\ToastEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlockRequest;
use App\Models\Block;
use App\Models\FileLink;
use App\Models\FMPType;
use App\Models\Neuroprofile;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Exception;

class BlockController extends Controller
{
    /**
     * Process datatables ajax request.
     *
     * @param int $profile_id
     * @return JsonResponse
     * @throws Exception
     */
    public function getData(int $profile_id = 0): JsonResponse
    {
        if ($profile_id == 0) {
            $blocks = Block::all();
        } else {
            $blocks = Block::all()->where('neuroprofile_id', $profile_id);
        }

        return Datatables::of($blocks)
            ->editColumn('profile', function ($block) {
                if(!$block->profile) {
                    return 'Нет привязки, исправьте в режиме Редактирование';
                } else {
                    return sprintf("(%s) %s, тип: %s", $block->profile->code, $block->profile->name, $block->profile->fmptype->name);
                }
            })
            ->addColumn('action', function ($block) use($profile_id) {
                $params = ['block' => $block->id, 'sid' => session()->getId()];
                if($profile_id != 0) $params['profile_id'] = $profile_id;

                $editRoute = route('blocks.edit', $params);
                $showRoute = route('blocks.show', $params);
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
     * @param Request $request
     * @param int $type
     * @return Application|Factory|View|Response
     */
    public function create(Request $request)
    {
        $embedded = false;
        if($request->has('profile_id')) {
            $profiles = Neuroprofile::findOrFail($request->profile_id);
            $embedded = true;
        } else {
            $profiles = Neuroprofile::all();
        }
        if($request->has('type')) {
            $type = intval($request->type);
        } else $type = Block::TYPE_TEXT;
        if($request->has('embedded'))
            $embedded = $request->embedded;

        $views = [
            Block::TYPE_TEXT => 'blocks.text.create',
            Block::TYPE_IMAGE => 'blocks.image.create',
            Block::TYPE_SCRIPT => 'blocks.script.create',
            Block::TYPE_VIDEO => 'blocks.video.create'
        ];
        return view($views[$type], compact('profiles', 'embedded'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BlockRequest $request
     * @return RedirectResponse|Response
     */
    public function store(BlockRequest $request)
    {
        $data = $request->all();
        $data['type'] = $request->kind;
        $mediaPath = null;
        switch($request->kind) {
            case Block::TYPE_IMAGE:
                $mediaPath = Block::uploadMedia($request, 'image');
                break;
            case Block::TYPE_VIDEO:
                $mediaPath = Block::uploadMedia($request, 'clip');
                break;
        }
        if($mediaPath) {
            FileLink::link($mediaPath);
            $data['content'] = $mediaPath;
        }
        $block = Block::create($data);
        $block->save();

		session()->put('success', "Блок описания ФМП № {$block->id} создан");
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function show(Request $request, int $id)
    {
        $block = $id;
        $show = true;
        $sid = ($request->has('sid') ? $request->sid : session()->getId());
        return redirect()->route('blocks.edit', compact('block', 'show', 'sid'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @param bool $show
     * @return Application|Factory|View|Response
     */
    public function edit(Request $request, int $id)
    {
        $block = Block::findOrFail($id);
        $profile = $block->profile;
        $show = $request->has('show') ? $request->show : false;
        $profiles = Neuroprofile::all();

        $views = [
            Block::TYPE_TEXT => 'blocks.text.edit',
            Block::TYPE_IMAGE => 'blocks.image.edit',
            Block::TYPE_VIDEO => 'blocks.video.edit'
        ];
        return view($views[$block->type], compact('block', 'profile', 'profiles', 'show'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BlockRequest $request
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function update(BlockRequest $request, $id)
    {
        $block = Block::findOrFail($id);

        $data = $request->all();
        $mediaPath = null;
        switch($block->type) {
            case Block::TYPE_IMAGE:
                $mediaPath = Block::uploadMedia($request, 'image', $block->content);
                break;
            case Block::TYPE_VIDEO:
                $mediaPath = Block::uploadMedia($request, 'clip', $block->content);
                break;
        }
        if($mediaPath) {
            FileLink::link($mediaPath);
            $data['content'] = $mediaPath;
        }

        $block->update($data);

		session()->put('success', "Изменения блока описания ФМП № {$block->id} сохранены");
        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $block
     * @return bool
     */
    public function destroy(Request $request, int $block)
    {
        if ($block == 0) {
            $id = $request->id;
        } else $id = $block;

        $block = Block::findOrFail($id);
        switch($block->type) {
            case Block::TYPE_IMAGE:
            case Block::TYPE_VIDEO:
                if($block->content)
                    if(FileLink::unlink($block->content))
                        Storage::delete($block->content);
                break;
        }
        $block_id = $block->id;
        $block->delete();

        event(new ToastEvent('success', '', "Блок № {$block_id} удалён"));
        return true;
    }

    public function back(?string $key = null, ?string $message = null)
    {
		session()->put($key, $message);
        return Redirect::back();
    }
}
