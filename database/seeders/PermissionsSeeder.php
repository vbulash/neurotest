<?php

    namespace Database\Seeders;

    use App\Models\Permission;
    use Illuminate\Database\Seeder;

    class PermissionsSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            $permissions = [
                [
                    'name' => 'clients.index',
                    'guard_name' => 'web',
                    'description' => 'Просмотр списка клиентов',
                    'chapter' => 'Клиенты'
                ],
                [
                    'name' => 'clients.create',
                    'guard_name' => 'web',
                    'description' => 'Создание нового клиента',
                    'chapter' => 'Клиенты'
                ],
                [
                    'name' => 'clients.show',
                    'guard_name' => 'web',
                    'description' => 'Просмотр карточки клиента',
                    'chapter' => 'Клиенты'
                ],
                [
                    'name' => 'clients.edit',
                    'guard_name' => 'web',
                    'description' => 'Редактирование карточки клиента',
                    'chapter' => 'Клиенты'
                ],
                [
                    'name' => 'contracts.index',
                    'guard_name' => 'web',
                    'description' => 'Просмотр списка контрактов',
                    'chapter' => 'Контракты'
                ],
                [
                    'name' => 'contracts.create',
                    'guard_name' => 'web',
                    'description' => 'Создание нового контракта',
                    'chapter' => 'Контракты'
                ],
                [
                    'name' => 'contracts.show',
                    'guard_name' => 'web',
                    'description' => 'Просмотр карточки контракта',
                    'chapter' => 'Контракты'
                ],
                [
                    'name' => 'contracts.edit',
                    'guard_name' => 'web',
                    'description' => 'Редактирование карточки контракта',
                    'chapter' => 'Контракты'
                ],
                [
                    'name' => 'users.index',
                    'guard_name' => 'web',
                    'description' => 'Просмотр списка пользователей',
                    'chapter' => 'Пользователи'
                ],
                [
                    'name' => 'users.create',
                    'guard_name' => 'web',
                    'description' => 'Создание нового пользователя',
                    'chapter' => 'Пользователи'
                ],
                [
                    'name' => 'users.show',
                    'guard_name' => 'web',
                    'description' => 'Просмотр карточки пользователя',
                    'chapter' => 'Пользователи'
                ],
                [
                    'name' => 'users.edit',
                    'guard_name' => 'web',
                    'description' => 'Редактирование карточки пользователя',
                    'chapter' => 'Пользователи'
                ]
            ];

            foreach ($permissions as $permission) {
                $perm = new Permission();
                $perm->name = $permission['name'];
                $perm->guard_name = $permission['guard_name'];
                $perm->description = $permission['description'];
                $perm->chapter = $permission['chapter'];
                $perm->save();
            }
        }
    }
