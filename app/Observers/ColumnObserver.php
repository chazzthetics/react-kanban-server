<?php

namespace App\Observers;

use App\Column;

class ColumnObserver
{
    public function created(Column $column)
    {
        $column->recordActivity('created');
    }

    public function updated(Column $column)
    {
        if ($column->isDirty('title')) {
            $column->recordActivity('title_updated');
        }

        if ($column->isDirty('is_locked') && $column->is_locked) {
            $column->recordActivity('locked');
        } elseif ($column->isDirty('is_locked') && !$column->is_locked) {
            $column->recordActivity('unlocked');
        }
    }

    public function deleted(Column $column)
    {
        $column->recordActivity('removed');
    }
}
