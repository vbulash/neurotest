<?php


	namespace App\Models\Blocks;


	class Images2 extends Image
	{
        protected $table = 'blocks';

        public static ?string $type = 'images2';
        // Заголовок блока
        public static ?string $title = 'Модуль из 2 картинок';
        // Views
        protected static ?string $createView = null;
        protected static ?string $editView = 'blocks.images.edit';
        protected static ?string $playView = null;

        public static array $content = [
            [
                "actual" => true,
                "required" => true,
                "name" => "imageA",
                "label" => "Изображение А",
                "type" => "image",
                "value" => ""
            ],
            [
                "actual" => true,
                "required" => true,
                "name" => "weightA",
                "label" => "Вес изображения А",
                "type" => "number",
                "value" => "1"
            ],
            [
                "actual" => true,
                "required" => true,
                "name" => "imageB",
                "label" => "Изображение Б",
                "type" => "image",
                "value" => ""
            ],
            [
                "actual" => true,
                "required" => true,
                "name" => "weightB",
                "label" => "Вес изображения Б",
                "type" => "number",
                "value" => "1"
            ],
        ];
	}
