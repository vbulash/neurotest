<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouseMove extends Model
{
    use HasFactory;

    protected $fillable = ['history_id', 'X', 'Y'];

    public function history()
    {
        return $this->belongsTo(History::class);
    }
}
