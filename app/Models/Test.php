<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;

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
        public const SHOW_SHORT = 2048;
        public const SHOW_MIDDLE = 4096;
        public const SHOW_FULL = 8192;
        public const MAIL_RESULTS = 16384;   // Отправить результат на почту респонеденту
        public const MAIL_SHORT = 32768;
        public const MAIL_MIDDLE = 65536;
        public const MAIL_FULL = 131072;

        protected $fillable = ['name', 'timeout', 'type', 'options', 'contract_id', 'test_id', 'questionset_id'];

        public static array $ident = [
            [
                [
                    "actual" => true,
                    "required" => false,
                    "name" => "first_name",
                    "label" => "Имя",
                    "type" => "text",
                    "value" => ""
                ],
                [
                    "actual" => true,
                    "required" => false,
                    "name" => "surname",
                    "label" => "Отчество",
                    "type" => "text",
                    "value" => ""
                ],
                [
                    "actual" => true,
                    "required" => false,
                    "name" => "last_name",
                    "label" => "Фамилия",
                    "type" => "text",
                    "value" => ""
                ],
                [
                    "actual" => true,
                    "required" => false,
                    "name" => "email",
                    "label" => "Электронная почта",
                    "type" => "email",
                    "value" => ""
                ],
                [
                    "actual" => true,
                    "required" => false,
                    "name" => "phone",
                    "label" => "Телефон",
                    "type" => "phone",
                    "value" => ""
                ],
                [
                    "actual" => true,
                    "required" => false,
                    "name" => "birth",
                    "label" => "Дата рождения",
                    "type" => "date",
                    "value" => ""
                ],
                [
                    "actual" => true,
                    "required" => false,
                    "name" => "age",
                    "label" => "Возраст",
                    "type" => "number",
                    "value" => ""
                ],
                [
                    "actual" => true,
                    "required" => false,
                    "name" => "sex",
                    "label" => "Пол",
                    "type" => "select",
                    "value" => "Ж",
                    "cases" => [
                        ["value" => "М", "label" => "Мужской"],
                        ["value" => "Ж", "label" => "Женский"]
                    ]
                ]
            ],
            [
                [
                    "actual" => true,
                    "required" => false,
                    "name" => "education_school",
                    "label" => "Образование (среднее)",
                    "type" => "text",
                    "value" => ""
                ],
                [
                    "actual" => true,
                    "required" => false,
                    "name" => "education_middle",
                    "label" => "Образование (среднее профессиональное)",
                    "type" => "text",
                    "value" => ""
                ],
                [
                    "actual" => true,
                    "required" => false,
                    "name" => "education_high",
                    "label" => "Образование (высшее)",
                    "type" => "text",
                    "value" => ""
                ],
            ],
            [
                [
                    "actual" => true,
                    "required" => false,
                    "name" => "work",
                    "label" => "Место работы",
                    "type" => "text",
                    "value" => ""
                ],
                [
                    "actual" => true,
                    "required" => false,
                    "name" => "position",
                    "label" => "Должность",
                    "type" => "text",
                    "value" => ""
                ]
            ]
        ];

        public static function all($columns = ['*'])
        {
            // TODO Сделать фильтрацию согласно правам
            return parent::all();
        }

        /**
         * Шаблон текущего теста (если есть)
         * @return BelongsTo
         */
        public function template()
        {
            return $this->belongsTo(Test::class);
        }

        /**
         * Тесты-потомки текущего шаблона
         * @return HasMany
         */
        public function children()
        {
            return $this->hasMany(Test::class);
        }

        /**
         * Контракт теста
         * @return BelongsTo
         */
        public function contract()
        {
            return $this->belongsTo(Contract::class);
        }

        /**
         * Записи журнала (истории) прохождения теста
         * @return HasMany
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

