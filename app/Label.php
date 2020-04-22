<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $fillable = [
        'color',
    ];

    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }
}
