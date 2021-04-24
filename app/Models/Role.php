<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'guard_name', 'wildcards'];

    public const SUPERADMIN = 'Владелец (Persona)';
    public const CLIENT = 'Клиент';
    public const ADMIN = 'Администратор (Persona)';
    public const MANAGER = 'Клиентский менеджер (Persona)';
    protected static $logAttributes = ['*'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Событие изменения роли пользователя: {$eventName}";
    }
}
