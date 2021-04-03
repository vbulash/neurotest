<?php


	namespace App\Models\Blocks;

	use App\Models\Block;

    class Auth_Full extends Block
	{
	    protected $table = 'blocks';

	    public static ?string $type = 'auth_full';
        // Заголовок блока
        public static ?string $title = 'Модуль авторизации (полные данные)';
        // Views
        protected static ?string $createView = null;
        protected static ?string $editView = "blocks.auth_full.edit";
        protected static ?string $playView = null;
        // Блокировка перемещения блока
        public static bool $locked = true;
        // Положение по умолчанию
        public static int $sortNo = Block::MIN;

        public static array $content = [
            [
                "actual" => true,
                "name" => "birth",
                "label" => "Дата рождения",
                "type" => "date",
                "value" => ""
            ],
            [
                "actual" => true,
                "name" => "sex",
                "label" => "Пол",
                "type" => "select",
                "value" => "Ж",
                "cases" => [
                    ["value" => "М", "label" => "Мужской"],
                    ["value" => "Ж", "label" => "Женский"]
                ]
            ],
            [
                "actual" => true,
                "name" => "education",
                "label" => "Образование",
                "type" => "text",
                "value" => ""
            ],
            [
                "actual" => true,
                "name" => "work",
                "label" => "Место работы",
                "type" => "text",
                "value" => ""
            ],
            [
                "actual" => true,
                "name" => "position",
                "label" => "Должность",
                "type" => "text",
                "value" => ""
            ],
        ];
	}
