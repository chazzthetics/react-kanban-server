<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    protected $fillable = [
        'uuid', 'title', 'position', 'board_id', 'user_id',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'id', 'user_id', 'board_id',
    ];

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'board_id' => 'int',
        'position' => 'int',
        'is_locked' => 'boolean',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
