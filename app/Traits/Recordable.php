<?php

namespace App\Traits;

use App\Activity;
use App\Column;
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
        //TODO: edit...looks disgusting!!!
        if (Str::endsWith($description, 'created')) {
            if ('Task' === class_basename($this)) {
                return [
                    'before' => ['uuid' => $this->uuid],
                    'after' => ['column_title' => $this->column->title, 'task_title' => $this->title],
                ];
            }

            return [
                'before' => [],
                'after' => ['title' => $this->title],
            ];
        }

        if (Str::endsWith($description, 'title_updated')) {
            if ('Task' === class_basename($this)) {
                return [
                    'before' => ['uuid' => $this->uuid, 'title' => $this->previousAttributes['title']],
                    'after' => ['title' => $this->title],
                ];
            }

            return [
                'before' => ['title' => $this->previousAttributes['title']],
                'after' => ['title' => $this->title],
            ];
        }

        if (Str::endsWith($description, 'description_updated')) {
            return [
                'before' => 'Task' === class_basename($this) ? ['uuid' => $this->uuid, 'title' => $this->title] : [],
                'after' => ['description' => $this->description],
            ];
        }

        if (Str::startsWith($description, 'removed')) {
            if ('Task' === class_basename($this)) {
                return [
                    'before' => [],
                    'after' => ['task_title' => $this->title, 'column_title' => $this->column->title],
                ];
            }

            return [
                'before' => [],
                'after' => ['title' => $this->title, 'board_title' => $this->board->title],
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

        if (Str::endsWith($description, 'completed')) {
            return [
                'before' => ['uuid' => $this->uuid],
                'after' => ['title' => $this->title],
            ];
        }

        if (Str::endsWith($description, 'incompleted')) {
            return [
                'before' => ['uuid' => $this->uuid],
                'after' => ['title' => $this->title],
            ];
        }

        if (Str::endsWith($description, 'moved')) {
            return [
                'before' => ['uuid' => $this->uuid, 'column_title' => $this->previousColumnTitle()],
                'after' => ['column_title' => $this->column->title, 'task_title' => $this->title],
            ];
        }

        if (Str::endsWith($description, 'cleared')) {
            return [
                'before' => [],
                'after' => ['title' => $this->title],
            ];
        }

        if (Str::endsWith($description, 'due_date')) {
            return [
                'before' => ['uuid' => $this->uuid, 'due_date' => $this->previousAttributes['due_date']],
                'after' => ['title' => $this->title, 'due_date' => $this->due_date],
            ];
        }

        if (Str::endsWith($description, 'priority')) {
            return [
                'before' => ['uuid' => $this->uuid],
                'after' => [
                    'title' => $this->title,
                    'priority' => $this->priority()->first() ? $this->priority()->first()->name : null,
                ],
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
