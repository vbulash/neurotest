<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Auth;

    class Client extends Model
    {
        use HasFactory;

        protected $fillable = ['name', 'inn', 'ogrn', 'address'];

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
    }
