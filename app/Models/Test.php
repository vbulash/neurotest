<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Auth;

    /**
     * Тест
     * Class Test
     * @package App\Models
     */
    class Test extends Model
    {
        use HasFactory;

        // Тип теста
        public const TYPE_DRAFT = 1;    // Черновик
        public const TYPE_TEMPLATE = 2; // Шаблон
        public const TYPE_ACTIVE = 4;   // Активный, доступен для прохождения всеми клиентами
        public const TYPE_TEST = 8;     // Только для владельцев платформы, остальным невидим и недоступен
        public const TYPE_EXACT = 16;   // Тест только для определенного клиента. Не может быть преобразован в шаблон
        public const types = [
            self::TYPE_DRAFT => 'Черновик',
            self::TYPE_TEMPLATE => 'Шаблон',
            self::TYPE_ACTIVE => 'Активный, доступен для прохождения',
            self::TYPE_TEST => 'Тестовый, только для владельцев платформы',
            self::TYPE_EXACT => 'Исключительный, для определенного клиента'
        ];

        // Опции
        // Опции аутентификации пользователя теста
        public const AUTH_GUEST = 1;
        public const AUTH_FULL = 2;
        public const AUTH_PKEY = 4;
        // Опции механики
        public const IMAGES2 = 16;
        public const IMAGES4 = 32;
        // Дополнительные опции механики
        public const EYE_TRACKING = 64;
        public const MOUSE_TRACKING = 128;
        // Опции показа результата тестирования
        public const SHOW_RESULTS = 1024;   // Показать результат на экране респондента
        public const MAIL_RESULTS = 2048;   // Отправить результат на почту респонеденту
        // Опции рассылки результата тестирования

        protected $fillable = ['name', 'timeout', 'type', 'options', 'contract_id', 'test_id', 'questionset_id'];

        public static function all($columns = ['*'])
        {
            // TODO Сделать фильтрацию согласно правам
            return parent::all();
        }

        /**
         * Шаблон текущего теста (если есть)
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function template()
        {
            return $this->belongsTo(Test::class);
        }

        /**
         * Тесты-потомки текущего шаблона
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function children()
        {
            return $this->hasMany(Test::class);
        }

        /**
         * Контракт теста
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function contract()
        {
            return $this->belongsTo(Contract::class);
        }

        /**
         * Блоки теста
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function blocks()
        {
            return $this->hasMany(Block::class);
        }

        /**
         * Записи журнала (истории) прохождения теста
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function histories()
        {
            return $this->hasMany(History_test::class);
        }

        public function sets()
        {
            return $this->belongsToMany(QuestionSet::class, 'test_questionset');
        }
    }

