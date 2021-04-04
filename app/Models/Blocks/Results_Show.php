<?php


	namespace App\Models\Blocks;


	use App\Models\Block;

    class Results_Show extends Block
	{
        protected $table = 'blocks';

        public static ?string $type = 'results_show';
        // Заголовок блока
        public static ?string $title = 'Модуль отображения результата';
        // Views
        protected static ?string $createView = null;
        protected static ?string $editView = null;
        protected static ?string $playView = null;
        // Блокировка перемещения блока
        public static bool $locked = true;
        // Видимость блока
        public static bool $display = false;
        // Положение по умолчанию
        public static int $sortNo = Block::MAX;
    }
