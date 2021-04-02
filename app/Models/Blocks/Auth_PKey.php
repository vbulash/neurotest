<?php


	namespace App\Models\Blocks;

	use App\Models\Block;

    class Auth_PKey extends Block
	{
        protected $table = 'blocks';

        public static ?string $type = 'auth_pkey';
        // Заголовок блока
        public static ?string $title = 'Модуль авторизации по ключу';
        // Views
        protected static ?string $createView = null;
        protected static ?string $editView = null;
        protected static ?string $playView = null;
        // Блокировка перемещения блока
        public static bool $locked = true;
        // Положение по умолчанию
        public static int $sortNo = Block::MAX;
	}
