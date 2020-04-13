<?php

namespace App\Observers;

use App\Task;

class TaskObserver
{
    public function created(Task $task)
    {
        $task->recordActivity('created');
    }

    public function updated(Task $task)
    {
        if ($task->isDirty('content')) {
            $task->recordActivity('content_updated');
        }

        if ($task->isDirty('column_id')) {
            $task->recordActivity('moved');
        }
    }

    public function deleted(Task $task)
    {
        $task->recordActivity('removed');
    }
}
