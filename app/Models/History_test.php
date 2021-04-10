<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * История прохождения теста
 * Class History_test
 * @package App\Models
 */
class History_test extends Model
{
    use HasFactory;

    protected $table = 'history_tests';
    protected $fillable = ['time', 'results', 'license_id', 'test_id'];

    /**
     * Лицензия, по которой пройден тест
     * @return BelongsTo
     */
    public function license()
    {
        return $this->belongsTo(License::class);
    }
}
