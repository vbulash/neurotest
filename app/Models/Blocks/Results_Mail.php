<?php


	namespace App\Models\Blocks;


	use App\Models\Block;

    class Results_Mail extends Block
	{
        protected $table = 'blocks';

        public static ?string $type = 'results_mail';
        // Заголовок блока
        public static ?string $title = 'Модуль пересылки результата';
        // Views
        protected static ?string $playView = null;
        // Блокировка перемещения блока
        public static bool $locked = true;
        // Положение по умолчанию
        public static int $sortNo = Block::MAX;
        // Видимость блока
        public static bool $display = false;
	}
