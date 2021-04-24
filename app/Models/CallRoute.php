<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User as UserModel;

class CallRoute extends Model
{
    use HasFactory;

    protected $table = 'routes';
    protected $fillable = ['route', 'params', 'breadcrumb', 'user_id'];

    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }
}
