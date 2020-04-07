<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    protected $fillable = [
        'uuid', 'title', 'position', 'board_id',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $casts = [
        'id' => 'int',
        'board_id' => 'int',
        'position' => 'int',
        'is_locked' => 'boolean',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
