<?php

namespace App;

use App\Traits\Recordable;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use Recordable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'background', 'title', 'description', 'user_id', 'is_current', 'is_starred',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'id', 'user_id',
    ];

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'is_current' => 'boolean',
        'is_starred' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function columns()
    {
        return $this->hasMany(Column::class)->orderBy('position');
    }

    public function addColumn(array $attributes)
    {
        return $this->columns()->create($attributes);
    }
}
