<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Board extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'color', 'title', 'slug', 'user_id', 'is_current', 'is_starred',
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

    public $old = [];

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

    public function activities()
    {
        return $this->morphMany(Activity::class, 'recordable')->latest();
    }

    public function recordActivity(string $description)
    {
        $this->activities()->create([
            'user_id' => $this->user_id,
            'description' => $description,
            'changes' => $this->activityChanges($description),
        ]);
    }

    protected function activityChanges($description)
    {
        if (Str::endsWith($description, 'updated')) {
            return [
                'before' => Arr::except(array_diff($this->old, $this->getAttributes()), 'updated_at'),
                'after' => Arr::except($this->getChanges(), 'updated_at'),
            ];
        }

        if (Str::endsWith($description, 'removed')) {
            return [
                'before' => ['title' => $this->title],
                'after' => [],
            ];
        }
    }
}
