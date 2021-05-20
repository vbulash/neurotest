<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class History extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'history';

    protected $fillable = [
        'test_id', 'license_id', 'question_id', 'card',
        'channelA', 'channelB', 'channelC', 'channelD', 'done'
    ];

    protected static $logAttributes = ['*'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Событие изменения истории прохождения теста: {$eventName}";
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function license()
    {
        return $this->belongsTo(License::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function mousemoves()
    {
        return $this->hasMany(MouseMove::class);
    }
}
