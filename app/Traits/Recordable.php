<?php

namespace App\Traits;

use App\Activity;
use App\Column;
use Illuminate\Support\Facades\Auth;

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

    protected function activityChanges(string $description)
    {
        switch ($description) {
            case 'created':
            case 'locked':
            case 'unlocked':
            case 'completed':
            case 'incompleted':
            case 'cleared':
                switch (class_basename($this)) {
                    case 'Board':
                    case 'Column':
                        return ['title' => $this->title];
                    case 'Task':
                        return [
                            'uuid' => $this->uuid,
                            'title' => $this->title,
                            'parent_title' => $this->column->title,
                        ];
                    default:
                        return null;
                }
                // no break
            case 'removed':
                switch (class_basename($this)) {
                    case 'Column':
                       return ['title' => $this->title, 'parent_title' => $this->board->title];
                    case 'Task':
                        return ['title' => $this->title, 'parent_title' => $this->column->title];
                    default:
                        return null;
                }
                // no break
            case 'completed':
            case 'incompleted':
            case 'description_updated':
            case 'checklist_removed':
                switch (class_basename($this)) {
                    case 'Task':
                        return ['uuid' => $this->uuid, 'title' => $this->title];
                    default:
                        return null;
                }
                // no break
            case 'title_updated':
                return [
                    'uuid' => $this->uuid,
                    'before' => ['title' => $this->previousAttributes['title']],
                    'after' => ['title' => $this->title],
                ];
            case 'checklist_added':
                return [
                    'uuid' => $this->uuid,
                    'title' => $this->title,
                    'checklist' => $this->checklist->title,
                ];

                return [
                    'uuid' => $this->uuid,
                ];
            case 'due_date_changed':
                return [
                    'uuid' => $this->uuid,
                    'title' => $this->title,
                    'before' => ['due_date' => $this->previousAttributes['due_date']],
                    'after' => ['due_date' => $this->due_date],
                ];
            case 'priority_changed':
                return [
                    'uuid' => $this->uuid,
                    'title' => $this->title,
                    'priority' => $this->priority()->exists() ? $this->priority()->first()->name : null,
                ];
            case 'moved':
                return [
                    'uuid' => $this->uuid,
                    'title' => $this->title,
                    'before' => ['title' => $this->previousColumnTitle()],
                    'after' => ['title' => $this->column->title],
                ];
            case 'copied':
                return [
                    'uuid' => $this->uuid,
                    'title' => $this->title,
                    'parent_title' => $this->column->title,
                ];
            default:
                return;
        }
    }

    private function getBoardId()
    {
        if ('Task' === class_basename($this)) {
            return $this->column->board_id;
        } elseif ('Column' === class_basename($this)) {
            return $this->board_id;
        }

        return $this->id;
    }

    private function previousColumnTitle()
    {
        //FIXME: !!
        if ('Task' === class_basename($this)) {
            return Column::find($this->previousAttributes['column_id'])->title;
        }

        return $this->previousAttributes['title'];
    }
}
