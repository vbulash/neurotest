<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['name' => 'Валерий Булаш', 'email' => 'vbulash@yandex.ru', 'password' => 'AeebIex1', 'phone' => '+79099217515', 'roles' => [Role::SUPERADMIN]],
            ['name' => 'client', 'email' => 'client@example.com', 'password' => 'client', 'phone' => '+71231234567', 'roles' => [Role::CLIENT]],
            ['name' => 'admin', 'email' => 'admin@example.com', 'password' => 'admin', 'phone' => '+71231234567', 'roles' => [Role::ADMIN]],
            ['name' => 'manager', 'email' => 'manager@example.com', 'password' => 'manager', 'phone' => '+71231234567', 'roles' => [Role::MANAGER]]
        ];

        foreach ($users as $u) {
            $user = new User();
            $user->name = $u['name'];
            $user->email = $u['email'];
            $user->password = bcrypt($u['password']);
            $user->phone = $u['phone'];
            $user->save();

            $roles = $u['roles'];
            foreach ($roles as $role) {
                $user->assignRole($role);
            }
        }
    }
}
