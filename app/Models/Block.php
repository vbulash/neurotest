<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Support\Facades\Log;

    //use Illuminate\Support\Facades\Log;

    /**
     * Блок теста
     * Class Block
     * @package App\Models
     * @method static create(array $array)
     */
    class Block extends Model
    {
        use HasFactory;

        public static ?string $type = null;
        // Заголовок блока
        public static ?string $title = null;
        // Views
        protected static ?string $createView = null;
        protected static ?string $editView = null;
        protected static ?string $playView = null;
        // Блокировка перемещения блока
        public static bool $locked = false;
        // Положение по умолчанию
        public static int $sortNo = -1;            // Не заблокировано
        // Видимость блока
        public static bool $display = true; // Блок видимый
        // Конфигурация дополнительных полей
        public static array $content = [];

        protected $fillable = [
            'sort_no', 'name', 'type', 'timeout', 'content', 'test_id', 'draft'];

        public const MIN = 0;
        public const MAX = 10000;

        /**
         * Тест для блока
         * @return BelongsTo
         */
        public function test(): BelongsTo
        {
            return $this->belongsTo(Test::class);
        }

        /**
         * Истории прохождения блоков
         * @return HasMany
         */
        public function histories(): HasMany
        {
            return $this->hasMany(History_block::class);
        }


        /**
         * Блокировка прямого изменения и удаления - управление только со стороны контейнера-теста
         * @param string|null $type
         * @return bool Признак блокировки
         */
        public static function locked(?string $type = null): bool
        {
            if ($type == null) return static::$locked;
            foreach (config('blocks') as $handler) {
                if ($handler::$type == $type) {
                    if ($handler::$locked) return true;
                    return false;
                }
            }
            return false;
        }

        /**
         * Возможность визуального создания блока
         * @param string|null $type
         * @return bool
         */
        public static function creatable(?string $type = null): bool
        {
            if ($type == null) return (static::$createView != null);
            foreach (config('blocks') as $handler) {
                if ($handler::$type == $type) {
                    if ($handler::$createView != null) return true;
                    return false;
                }
            }
            return false;
        }

        /**
         * Возможность визуального редактирования / просмотра блока
         * @param string|null $type
         * @return bool
         */
        public static function editable(?string $type = null): bool
        {
            if ($type == null) return (static::$editView != null);
            foreach (config('blocks') as $handler) {
                if ($handler::$type == $type) {
                    if ($handler::$editView != null) return true;
                    return false;
                }
            }
            return false;
        }

        /**
         * Генерация номера по порядку
         *
         * @return int
         */
        public static function getSortNo(): int
        {
            $test_id = session('test_id');
            if ($test_id == 0) {
                $count = Block::all()->count();
            } else {
                $count = Block::all()->where('test_id', $test_id)->count();
            }
            return $count * 10;
        }

        /**
         * Создание записи блока в БД (невизуальное)
         *
         * @return int ID сохраненного блока в БД
         */
        public static function add(): int
        {
            $test_id = session('test_id');

            $block = self::create([
                'sort_no' => static::getSortNo(),
                'name' => static::$title,
                'type' => static::$type,
                'timeout' => 0,
                'content' => null,
                'test_id' => ($test_id == 0 ? null : $test_id)
            ]);
            $block->save();

            return $block->id;
        }

        // TODO: проработать вызов отдельного модуля через показ self::$playView
        public function play(): bool
        {
            return true;
        }

        public static function getCreateView(): ?string
        {
            return static::$createView;
        }

        public static function getEditView(): ?string
        {
            return static::$editView;
        }

        public static function getPlayView(): ?string
        {
            return static::$playView;
        }
    }
