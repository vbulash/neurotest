<?php


	namespace App\Models\Blocks;


	class Images4 extends \App\Models\Block
	{
        protected $table = 'blocks';

        public static ?string $type = 'images4';
        // Заголовок блока
        public static ?string $title = 'Модуль из 4 картинок';
        // Views
        protected static ?string $createView = null;
        protected static ?string $editView = 'admin.blocks.edit';
        protected static ?string $playView = null;
	}
