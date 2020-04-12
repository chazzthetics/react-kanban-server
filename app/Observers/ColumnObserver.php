<?php

namespace App\Observers;

use App\Column;

class ColumnObserver
{
    public function created(Column $column)
    {
        $column->recordActivity('created');
    }

    public function updating(Column $column)
    {
        $column->old = $column->getOriginal();
    }

    public function updated(Column $column)
    {
        if ($column->isDirty('title')) {
            $column->recordActivity('title_updated');
        }
    }

    public function deleted(Column $column)
    {
        $column->recordActivity('removed');
    }
}