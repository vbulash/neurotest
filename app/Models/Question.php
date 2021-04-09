<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';
    protected $fillable = ['sort_no', 'imageA', 'imageB', 'imageC', 'imageD', 'questionset_id'];

    public function set()
    {
        return $this->belongsTo(QuestionSet::class);
    }

    public static function uploadImage(Request $request, string $imageField, string $image = null)
    {
        if($request->hasFile($imageField)) {
            if($image) Storage::delete($image);
            $folder = date('Y-m-d');
            return $request->file($imageField)->store("images/{$folder}");
        }
        return null;
    }
}
