<?php

namespace App;

use App\Traits\Recordable;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use Recordable;

    protected $fillable = [
        'uuid', 'title', 'position', 'description', 'completed', 'column_id', 'user_id', 'due_date',
    ];

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'column_id' => 'int',
        'position' => 'int',
        'completed' => 'boolean',
    ];

    protected $hidden = [
        'id', 'user_id', 'column_id', 'updated_at',
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
        return $this->belongsToMany(Priority::class);
    }

    public function checklist()
    {
        return $this->hasOne(Checklist::class);
    }

    public function addChecklist(array $attributes)
    {
        return $this->checklist()->create($attributes);
    }
}
