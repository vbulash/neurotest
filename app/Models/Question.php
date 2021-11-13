<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;

class Question extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'questions';
    protected $fillable = ['sort_no', 'imageA', 'imageB', 'imageC', 'imageD', 'valueA', 'valueB', 'valueC', 'valueD', 'questionset_id', 'learning', 'timeout'];

    public const EMPTY_VALUE = '&nbsp;&nbsp;';
    public static array $values = [self::EMPTY_VALUE, 'A+', 'A-', 'B+', 'B-', 'C+', 'C-', 'D+', 'D-', 'E+', 'E-'];

    protected static $logAttributes = ['*'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Событие изменения вопроса теста: {$eventName}";
    }

    public function qset()
    {
        return $this->belongsTo(QuestionSet::class, 'questionset_id');
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }

    public static function uploadImage(Request $request, string $imageField, string $image = null)
    {
        if($request->hasFile($imageField)) {
            if($image)
                if(FileLink::unlink($image))
                    Storage::delete($image);
            $folder = date('Y-m-d');
            return $request->file($imageField)->store("images/{$folder}");
        }
        return null;
    }

    /**
     * @param int $setId
     * @return Question
     */
    public function copyToSet(int $setId): Question
    {
        $target = $this->replicate();
        $target->questionset_id = $setId;
        foreach (['imageA', 'imageB', 'imageC', 'imageD'] as $imageField) {
            if(!$imageField) continue;

            $mediaPath = $target->getAttributeValue($imageField);
            if($mediaPath) FileLink::link($mediaPath);
        }
        $target->save();

        return $target;
    }
}
