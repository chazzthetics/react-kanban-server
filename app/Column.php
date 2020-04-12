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

    public $old = [];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('position');
    }

    public function addTask(array $attributes)
    {
        return $this->tasks()->create($attributes);
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'recordable')->latest();
    }

    public function recordActivity(string $description)
    {
        $this->activities()->create([
            'user_id' => $this->user()->id,
            'board_id' => $this->board_id,
            'description' => $description,
            'changes' => [
                'before' => array_diff($this->old, $this->getAttributes()),
                'after' => $this->getChanges(),
            ],
        ]);
    }
}
