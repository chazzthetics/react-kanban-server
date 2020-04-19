<?php

namespace App;

use App\Traits\Recordable;
use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    use Recordable;

    protected $fillable = [
        'uuid', 'title', 'position', 'board_id', 'user_id', 'is_locked',
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

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('position');
    }

    public function addTask(array $attributes)
    {
        return $this->tasks()->create($attributes);
    }
}
