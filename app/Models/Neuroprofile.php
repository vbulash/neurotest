<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Neuroprofile extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'neuroprofiles';
    protected $fillable = ['code', 'name', 'fmptype_id'];
    protected static $logAttributes = ['*'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Событие изменения нейропрофиля: {$eventName}";
    }

    public function fmptype() {
        return $this->belongsTo(FMPType::class);
    }

    public function blocks()
    {
        return $this->hasMany(Block::class);
    }
}
