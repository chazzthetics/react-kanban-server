<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    protected $fillable = [
        'name', 'color',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'pivot',
    ];

    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }
}
