<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends \Spatie\Permission\Models\Permission
{
    //protected $fillable = ['name', 'guard_name', 'description', 'chapter'];

    use HasFactory;
}
