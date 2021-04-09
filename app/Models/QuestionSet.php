<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionSet extends Model
{
    use HasFactory;

    protected $table = 'questionsets';
    protected $fillable = ['name', 'quantity', 'type', 'client_id', 'options'];

    // Количество картинок в вопросе
    public const IMAGES2 = 2;
    public const IMAGES4 = 4;

    // Тип набора вопросов
    public const TYPE_DRAFT = 1;    // Черновик
    public const TYPE_ACTIVE = 4;   // Активный
    public const TYPE_TEST = 8;     // Только для владельцев платформы, остальным невидим и недоступен
    public const TYPE_EXACT = 16;   // Тест только для определенного клиента
    public const types = [
        self::TYPE_DRAFT => 'Черновик',
        self::TYPE_ACTIVE => 'Активный',
        self::TYPE_TEST => 'Тестовый, используется только для владельцев платформы',
        self::TYPE_EXACT => 'Исключительный, для тестов определенного клиента'
    ];
    // Дополнительные настройки
    public const OPTIONS_EYE_TRACKING = 1;
    public const OPTIONS_MOUSE_TRACKING = 2;

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'test_questionset');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'questionset_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
