<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'uuid', 'content', 'position', 'column_id', 'user_id',
    ];

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'column_id' => 'int',
        'position' => 'int',
        'completed' => 'boolean',
    ];

    protected $hidden = [
        'id', 'user_id', 'column_id',
    ];

    public function column()
    {
        return $this->belongsTo(Column::class);
    }
}