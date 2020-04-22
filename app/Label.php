<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $fillable = [
         'color',
    ];

    protected $casts = [
        'id' => 'int',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'pivot',
    ];

    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }
}
