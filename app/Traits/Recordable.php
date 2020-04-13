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

    protected function activityChanges(string $description)
    {
        //TODO: edit...looks disgusting
        if (Str::contains($description, 'created') && 'Task' === class_basename($this)) {
            return [
                'before' => null,
                'after' => ['title' => $this->column->title ?: '', 'content' => $this->content ?: ''],
            ];
        }

        if (Str::endsWith($description, 'updated')) {
            return [
                'before' => Arr::except(array_diff($this->previousAttributes, $this->getAttributes()), 'updated_at'),
                'after' => Arr::except($this->getChanges(), 'updated_at'),
            ];
        }

        if (Str::endsWith($description, 'removed')) {
            return [
                'before' => ['title' => $this->title ?: $this->column->title, 'content' => 'Task' === class_basename($this) ? $this->content : ''],
                'after' => [],
            ];
        }

        if (Str::endsWith($description, 'starred')) {
            return [
                'before' => [],
                'after' => ['title' => $this->title],
            ];
        }

        if (Str::endsWith($description, 'moved')) {
            return [
                'before' => ['title' => $this->previousColumnTitle()],
                'after' => ['title' => $this->column->title, 'content' => $this->content],
            ];
        }
    }
}
