<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;

class Block extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'blocks';
    protected $fillable = ['sort_no', 'description', 'content', 'type', 'neuroprofile_id', 'paid', 'free'];

    // Типы блоков
    public const TYPE_TEXT = 0;     // Текстовый блок
    public const TYPE_IMAGE = 1;    // Блок с изображением
    public const TYPE_SCRIPT = 2;   // Блок Javascript
    public const TYPE_VIDEO = 3;    // Блок с видео
    protected static $logAttributes = ['*'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Событие изменения блока описания ФМП: {$eventName}";
    }

    public static function uploadMedia(Request $request, string $fileField, string $fileName = null)
    {
        if($request->hasFile($fileField)) {
            if($fileName)
                if(FileLink::unlink($fileName))
                    Storage::delete($fileName);
            $folder = date('Y-m-d');
            return $request->file($fileField)->store("images/{$folder}");
        }
        return null;
    }

    public function profile()
    {
        return $this->belongsTo(Neuroprofile::class, 'neuroprofile_id');
    }
}
