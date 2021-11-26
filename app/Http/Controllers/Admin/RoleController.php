<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     * Process datatables ajax request.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function getData(): JsonResponse
    {
        return Datatables::of(Role::query()->orderBy('name'))
            ->addColumn('permissions', function($role) {
                if($role->name == ROLE::SUPERADMIN) {
                    return ['Супер-администратор: полный доступ'];
                } else {
                    $names = $role->getPermissionNames();
                    $descriptions = [];
                    foreach ($names as $name) {
                        $descriptions[] = Permission::all()->where('name', $name)->first()->description;
                    }
                    return $descriptions;
                }
            })
            ->addColumn('wildcards', function($role) {
                if($role->wildcards) {
                    return 'Конкретные контракты';
                } else {
                    return 'Все контракты';
                }
            })
            ->addColumn('action', function ($role) {
                if($role->name == ROLE::SUPERADMIN) {
                    return '';
                }
                $params = [
                    'role' => $role->id,
                    'sid' => session()->getId()
                ];
                $editRoute = route('roles.edit', $params);
                $showRoute = route('roles.show', $params);
                $action = '';
                $logged = Auth::user();
                if(true)
                //if ($logged->hasPermissionTo('roles.edit'))
                    $action .=
                        "<a href=\"{$editRoute}\" class=\"btn btn-info btn-sm float-left mr-1\" " .
                        "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Редактирование\">\n" .
                        "<i class=\"fas fa-pencil-alt\"></i>\n" .
                        "</a>\n";
                if(true)
                //if ($logged->hasPermissionTo('roles.show'))
                    $action .=
                        "<a href=\"{$showRoute}\" class=\"btn btn-info btn-sm float-left mr-1\" " .
                        "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Просмотр\">\n" .
                        "<i class=\"fas fa-eye\"></i>\n" .
                        "</a>\n";
                return $action;
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
        $roles = Role::all();
        return view('admin.roles.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRoleRequest $request
     * @return RedirectResponse|Response
     */
    public function store(StoreRoleRequest $request)
    {
        $data = $request->all();
        $permissions = array_values($data['permissions']);
        $data['wildcards'] = (array_key_exists('wildcards', $data) ? '1' : '0');

        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'],
            'wildcards' => $data['wildcards']
        ]);
        $role->syncPermissions($permissions);
        $role->save();

        return redirect()->route('roles.index', [
            'sid' => ($request->has('sid') ? $request->sid : session()->getId())
        ])->with('success', "Роль \"{$role->name}\" добавлена");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
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
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $role_permissions = $role->getPermissionNames()->toArray();

        $predefined = [
            Role::SUPERADMIN, Role::CLIENT, Role::MANAGER, Role::ADMIN
        ];

        return view('admin.roles.edit', compact('role', 'permissions', 'role_permissions', 'show', 'predefined'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRoleRequest $request
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        $data = $request->all();
        $permissions = array_values($data['permissions']);

        $role = Role::findOrFail($id);
        $role->guard_name = 'web';
        $role->update($data);

        $role->syncPermissions($permissions);

        return redirect()->route('roles.index', [
            'sid' => ($request->has('sid') ? $request->sid : session()->getId())
        ])->with('success', "Изменения роли \"{$role->name}\" сохранены");
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
