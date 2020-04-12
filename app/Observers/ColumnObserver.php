<?php

namespace App\Observers;

use App\Column;

class ColumnObserver
{
    public function created(Column $column)
    {
        $column->recordActivity('column_created');
    }

    public function updating(Column $column)
    {
        $column->old = $column->getOriginal();
    }

    public function updated(Column $column)
    {
        if ($column->isDirty('title')) {
            $column->recordActivity('column_title_updated');
        }
    }
}
