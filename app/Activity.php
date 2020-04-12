<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'user_id', 'description', 'changes',
    ];

    protected $casts = [
        'changes' => 'json',
    ];

    protected $hidden = [
        'user_id', 'recordable_id',
    ];

    public function recordable()
    {
        return $this->morphTo();
    }
}
