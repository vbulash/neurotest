<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreUserRequest;
    use App\Http\Requests\UpdateUserRequest;
    use App\Models\Client;
    use App\Models\Role;
    use App\Models\User;
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

    class UserController extends Controller
    {
        /**
         * Process datatables ajax request.
         *
         * @return JsonResponse
         * @throws Exception
         */
        public function getData(): JsonResponse
        {
            return Datatables::of(User::all())
                ->editColumn('roles', function($user) {
                    return $user->getRoleNames()->toArray();
                })
                ->editColumn('clients', function($user) {
                    $clients = $user->clients()->pluck('name')->toArray();
                    //Log::debug('Клиенты = ' . print_r($clients, true));
                    return $clients ?: ['Все клиенты'];
                })
                ->addColumn('action', function ($user) {
                    $params = [
                        'user' => $user->id,
                        'sid' => session()->getId()
                    ];
                    $editRoute = route('users.edit', $params);
                    $showRoute = route('users.show', $params);
                    $action = '';
                    $logged = Auth::user();
                    if ($logged->hasPermissionTo('users.edit'))
                        $action .=
                            "<a href=\"{$editRoute}\" class=\"btn btn-info btn-sm float-left mr-1\" " .
                            "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Редактирование\">\n" .
                            "<i class=\"fas fa-pencil-alt\"></i>\n" .
                            "</a>\n";
                    if ($logged->hasPermissionTo('users.show'))
                        $action .=
                            "<a href=\"{$showRoute}\" class=\"btn btn-info btn-sm float-left mr-1\" " .
                            "data-toggle=\"tooltip\" data-placement=\"top\" title=\"Просмотр\">\n" .
                            "<i class=\"fas fa-eye\"></i>\n" .
                            "</a>\n";
                    return $action;
                })
                ->make(true);
        }

        public function index()
        {
            $users = User::all();
            return view('admin.users.index', compact('users'));
        }

        public function create()
        {
            $roles = Role::all()->pluck('name');
            $clients = Client::all()->pluck('name', 'id');
            return view('admin.users.create', compact('roles', 'clients'));
        }

        /**
         * @param StoreUserRequest $request
         * @return RedirectResponse
         */
        public function store(StoreUserRequest $request): RedirectResponse
        {
            $data = $request->all();
            $data['password'] = bcrypt($request->password);
            $user = User::create($data);
            $user->save();

            $user->syncRoles($data['roles']);
            if(key_exists('clients', $data)) {
                $user->clients()->sync($data['clients']);
            }

            session()->flash('success', "Пользователь \"{$user->name}\" зарегистрирован");
            return redirect()->route('users.index',
                ['sid' => ($request->has('sid') ? $request->sid : session()->getId())]);
        }

        /**
         * Display the specified resource.
         *
         * @param int $id
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
            $user = User::findOrFail($id);
            $roles = Role::all()->pluck('name');
            $user_roles = $user->getRoleNames();
            $clients = Client::all()->pluck('name', 'id');
            $user_clients = $user->clients()->get()->pluck('name', 'id'); // не null, а count() == 0
            return view('admin.users.edit',
                compact('user', 'show', 'roles', 'user_roles', 'clients', 'user_clients'));
        }

        /**
         * Update the specified resource in storage.
         *
         * @param UpdateUserRequest $request
         * @param int $id
         * @return RedirectResponse|Response
         */
        public function update(UpdateUserRequest $request, int $id)
        {
            $data = $request->all();
            if ($data['password'] == null) {
                unset($data['password']);
            } else {
                $data['password'] = bcrypt($data['password']);
            }
            $user = User::findOrFail($id);
            $original =$user->name;
            $user->update($data);

            $changed = $user->wasChanged();
            $changed = $changed || (count(array_diff($user->getRoleNames()->toArray(), $data['roles'])) != 0);

            if(key_exists('clients', $data)) {
                $user_clients = $user->clients()->get()->pluck('id')->toArray();
                $changed = $changed || (count(array_diff_assoc($user_clients, $data['clients'])) != 0);
            }

            $user->syncRoles($data['roles']);
            if(key_exists('clients', $data)) {
                $user->clients()->sync($data['clients']);
            }

            if($changed) {
                // TODO: Отправить письмо
                session()->flash('success',
                    "Изменения пользователя &laquo;{$original}&raquo; сохранены<br/>" .
                    "Письмо пользователю &laquo;{$original}&raquo; отправлено");
            }

            return redirect()->route('users.index',
                ['sid' => ($request->has('sid') ? $request->sid : session()->getId())]);
        }

        public function loginForm()
        {
            return view('users.login');
        }

        public function login(Request $request)
        {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
            ])) {
                session()->flash('success', 'Вы вошли в систему');
                return redirect()->route('admin.index',
                    ['sid' => ($request->has('sid') ? $request->sid : session()->getId())]);
            } else {
                session()->flash('error', 'Электронная почта / пароль неверны; вход в систему невозможен');
                return redirect()->route('login');
            }
        }

        public function logout()
        {
            Auth::logout();
            return redirect()->route('login.create');
        }
    }
