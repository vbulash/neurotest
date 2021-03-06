<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method static create(array $array)
 * @method static findOrFail(int $id)
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static $logAttributes = ['*'];

    public array $fields = [
        'name' => 'ФИО',
        'email' => 'Электронная почта',
        'phone' => 'Телефон',
        'password' => 'Пароль'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Событие изменения пользователя: {$eventName}";
    }

    // Внешние связи
    public function licenses()
    {
        return $this->hasMany(License::class);
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'user_client');
    }

    /**
     * Возвращает список заголовков измененных полей
     * @return array|null
     */
    public function listChanges(): ?array
    {
        if(!$this->wasChanged()) return null;
        $list = [];
        foreach ($this->fields as $name => $title)
            if($this->wasChanged($name)) $list[] = $title;

        return $list;
    }
}
