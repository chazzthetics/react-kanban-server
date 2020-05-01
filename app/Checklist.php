<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    protected $fillable = [
        'uuid', 'title', 'task_id',
    ];

    protected $hidden = [
        'id', 'created_at', 'updated_at', 'task_id',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function items()
    {
        return $this->hasMany(ChecklistItem::class);
    }

    public function addItem(array $attributes)
    {
        return $this->items()->create($attributes);
    }
}
