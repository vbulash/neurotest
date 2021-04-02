<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => Role::SUPERADMIN, 'guard_name' => 'web'],  // Суперадминистратор
            ['name' => Role::CLIENT, 'guard_name' => 'web', 'rights' => [
                'clients.index', 'clients.show',
                'contracts.index', 'contracts.show',
            ]],
            ['name' => Role::ADMIN, 'guard_name' => 'web', 'rights' => [
                'clients.index', 'clients.create', 'clients.show', 'clients.edit',
                'contracts.index', 'contracts.create', 'contracts.show', 'contracts.edit',
                'users.index', 'users.create', 'users.show', 'users.edit'
            ]],
            ['name' => Role::MANAGER, 'guard_name' => 'web', 'rights' => [
                'clients.index', 'clients.create', 'clients.show', 'clients.edit',
                'contracts.index', 'contracts.create', 'contracts.show', 'contracts.edit',
                'users.index', 'users.create', 'users.show', 'users.edit'
            ]],
        ];

        foreach ($roles as $r) {
            $role = new Role();
            $role->name = $r['name'];
            $role->guard_name = $r['guard_name'];
            $role->save();

            if ($role->name == Role::SUPERADMIN) {
                $role->syncPermissions(Permission::all());
            } else {
                $rights = $r['rights'];
                foreach($rights as $right) {
                    $role->givePermissionTo($right);
                }
            }
        }
    }
}
