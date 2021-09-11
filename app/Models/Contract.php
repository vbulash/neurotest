<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use phpDocumentor\Reflection\Types\Parent_;
    use Spatie\Activitylog\Traits\LogsActivity;

    class Contract extends Model
    {
        use HasFactory, LogsActivity;

        // Константы статуса контракта
        public const INACTIVE = 0b00000001;             // Контракт неактивен (до даты начала)
        public const ACTIVE = 0b00000010;               // Контракт активен
        public const COMPLETE_BY_DATE = 0b00000100;     // Контракт завершен (превышен срок действия)
        public const COMPLETE_BY_COUNT = 0b00001000;    // Контракт завершен (использованы все лицензии контракта)

        protected $fillable = [
            'number', 'start', 'end', 'invoice', 'mkey', 'license_count', 'url', 'status', 'client_id'
        ];
        protected static $logAttributes = ['*'];

        public function getDescriptionForEvent(string $eventName): string
        {
            return "Событие изменения контракта: {$eventName}";
        }

        public static function all($columns = ['*'])
        {
            // TODO Сделать фильтрацию согласно правам
            return parent::all();
        }

        public function client()
        {
            return $this->belongsTo(Client::class);
        }

        public function licenses()
        {
            return $this->hasMany(License::class);
        }

        public function tests()
        {
            return $this->hasMany(Test::class);
        }

        // Генератор MKey
        public static function generateKey(string $url): string
        {
            $first = uniqid('mkey_', true);
            $last = sprintf("%u", crc32($url));
            return $first . '*' . $last;
        }

        public static function checkUrl(string $mkey, string $url): bool
        {
            $parts = explode('*', $mkey);
            $crc = sprintf("%u", crc32($url));
            return ($parts[1] == $crc);
        }
    }
