<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class FMPType extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'fmptypes';
    protected $fillable = ['name', 'cluster'];
    protected static $logAttributes = ['*'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Событие изменения типа ФМП: {$eventName}";
    }

    public function profiles()
    {
        return $this->hasMany(Neuroprofile::class, 'fmptype_id');
    }
}
