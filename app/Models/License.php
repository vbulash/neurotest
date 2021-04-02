<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    // Константы статуса лицензии
    public const FREE = 0b00000001;     // Свободная лицензия
    public const USING = 0b00000010;    // Используется (в настоящий момент)
    public const USED = 0b00000100;     // Использована (тестирование завершено)
    public const BROKEN = 0b00001000;   // Повреждена (тест прерван)

    protected $fillable = ['pkey', 'status', 'contract_id', 'user_id'];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Генератор PKey
    public static function generateKey()
    {
        return uniqid('mkey_', true);
    }
}
