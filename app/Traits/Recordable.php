<?php

namespace App\Traits;

use App\Activity;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait Recordable
{
    /**
     * Model's previous attributes.
     *
     * @var array
     */
    public $previousAttributes = [];

    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootRecordable()
    {
        static::updating(function ($model) {
            $model->previousAttributes = $model->getOriginal();
        });
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'recordable')->latest();
    }

    public function recordActivity(string $description)
    {
        $this->activities()->create([
            'user_id' => Auth::id(),
            'description' => $description,
            'changes' => $this->activityChanges($description),
        ]);
    }

    protected function activityChanges($description)
    {
        //TODO: edit...
        if (Str::endsWith($description, 'updated')) {
            return [
                'before' => Arr::except(array_diff($this->previousAttributes, $this->getAttributes()), 'updated_at'),
                'after' => Arr::except($this->getChanges(), 'updated_at'),
            ];
        }

        if (Str::endsWith($description, 'removed')) {
            return [
                'before' => ['title' => $this->title],
                'after' => [],
            ];
        }

        if (Str::endsWith($description, 'starred')) {
            return [
                'before' => ['title' => $this->title],
                'after' => [],
            ];
        }
    }
}
