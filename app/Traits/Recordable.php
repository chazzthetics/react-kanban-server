<?php

namespace App\Traits;

use App\Activity;
use App\Column;
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
            'board_id' => $this->getBoardId(),
            'description' => $description,
            'changes' => $this->activityChanges($description),
        ]);
    }

    private function getBoardId()
    {
        if ('Task' === class_basename($this)) {
            return $this->column->board_id; //FIXME: look up hasone relation again
        } elseif ('Column' === class_basename($this)) {
            return $this->board_id;
        }

        return $this->id;
    }

    protected function activityChanges(string $description)
    {
        //TODO: edit...looks disgusting
        if (Str::endsWith($description, 'created')) {
            if ('Task' === class_basename($this)) {
                return [
                    'before' => null,
                    'after' => ['column_title' => $this->column->title ?: '', 'task_title' => $this->title ?: ''],
                ];
            } else {
                return [
                    'before' => null,
                    'after' => ['title' => $this->title ?: ''],
                ];
            }
        }

        if (Str::endsWith($description, 'updated')) {
            return [
                'before' => ['title' => $this->previousColumnTitle()],
                'after' => Arr::except($this->getChanges(), 'updated_at'),
            ];
            // return [
            //     'before' => Arr::except(array_diff($this->previousAttributes, $this->getAttributes()), 'updated_at'),
            //     'after' => Arr::except($this->getChanges(), 'updated_at'),
            // ];
        }

        if (Str::endsWith($description, 'removed')) {
            return [
                'before' => ['title' => $this->title ?: $this->column->title, 'task_title' => 'Task' === class_basename($this) ? $this->title : ''],
                'after' => [],
            ];
        }

        if (Str::endsWith($description, 'starred')) {
            return [
                'before' => [],
                'after' => ['title' => $this->title],
            ];
        }

        if (Str::endsWith($description, 'locked')) {
            return [
                'before' => [],
                'after' => ['title' => $this->title],
            ];
        }

        if (Str::endsWith($description, 'moved')) {
            return [
                'before' => ['column_title' => $this->previousColumnTitle()],
                'after' => ['column_title' => $this->column->title, 'task_title' => $this->title],
            ];
        }
    }

    private function previousColumnTitle()
    {
        //FIXME:
        if ('Task' === class_basename($this)) {
            return Column::find($this->previousAttributes['column_id'])->title;
        }

        return $this->previousAttributes['title'];
    }
}
