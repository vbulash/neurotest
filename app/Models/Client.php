<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Auth;
    use Spatie\Activitylog\Traits\LogsActivity;

    class Client extends Model
    {
        use HasFactory, LogsActivity;

        protected $fillable = ['name', 'inn', 'ogrn', 'address', 'phone', 'email'];
        protected static $logAttributes = ['*'];

        public function getDescriptionForEvent(string $eventName): string
        {
            return "Событие изменения клиента: {$eventName}";
        }

        public static function all($columns = ['*'])
        {
            if(!Auth::check()) return null;

            $clients = Auth::user()->clients;
            if(count($clients) == 0) $clients = parent::all();

            return $clients;
        }

        // Внешние связи
        public function contracts()
        {
            return $this->hasMany(Contract::class);
        }

        public function users()
        {
            return $this->belongsToMany(User::class, 'user_client');
        }

        public function sets()
        {
            return $this->hasMany(QuestionSet::class);
        }
    }
