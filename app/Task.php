<?php

namespace App;

use App\Traits\Recordable;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use Recordable;

    protected $fillable = [
        'uuid', 'title', 'position', 'description', 'column_id', 'user_id',
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

    public function labels()
    {
        return $this->belongsToMany(Label::class);
    }

    public function priority()
    {
        return $this->hasMany(Priority::class);
    }
}
