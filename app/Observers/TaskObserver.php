<?php

namespace App\Observers;

use App\Task;

class TaskObserver
{
    public function created(Task $task)
    {
        // $task->recordActivity('created');
    }

    //FIXME:
    public function updated(Task $task)
    {
        if ($task->isDirty('completed') && $task->completed) {
            $task->recordActivity('completed');
        }

        if ($task->isDirty('completed') && !$task->completed) {
            $task->recordActivity('incompleted');
        }

        if ($task->isDirty('due_date')) {
            $task->recordActivity('due_date_changed');
        }

        if ($task->isDirty('column_id')) {
            $task->recordActivity('moved');
        }

        if ($task->isDirty('title')) {
            $task->recordActivity('title_updated');
        }

        if ($task->isDirty('description')) {
            $task->recordActivity('description_updated');
        }
    }

    public function deleted(Task $task)
    {
        $task->recordActivity('removed');
    }
}
