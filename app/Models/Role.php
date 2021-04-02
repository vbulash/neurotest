<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends \Spatie\Permission\Models\Role
{
    protected $fillable = ['name', 'guard_name', 'wildcards'];

    public const SUPERADMIN = 'Владелец (Persona)';
    public const CLIENT = 'Клиент';
    public const ADMIN = 'Администратор (Persona)';
    public const MANAGER = 'Клиентский менеджер (Persona)';

    use HasFactory;
}
