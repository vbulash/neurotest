<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Записи истории прохождения блоков
 * Class History_block
 * @package App\Models
 */
class History_block extends Model
{
    use HasFactory;

    protected $table = 'history_blocks';
    protected $fillable = ['answers', 'history_test_id', 'block_id'];

    /**
     * Запись истории теста для истории блока
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function test()
    {
        return $this->belongsTo(History_test::class, 'history_test_id', 'id');
    }

    /**
     * Блок для истории прохождения блоков
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function block()
    {
        return $this->belongsTo(Block::class);
    }
}
