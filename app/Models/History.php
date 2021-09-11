<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class History extends Model
{
    use HasFactory;

    protected $table = 'history';

    protected $fillable = [
        'test_id', 'license_id', 'card', 'done', 'code', 'paid'
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function license()
    {
        return $this->belongsTo(License::class);
    }

    public function steps()
    {
        return $this->hasMany(HistoryStep::class);
    }

    public function mousemoves()
    {
        return $this->hasMany(MouseMove::class);
    }
}
