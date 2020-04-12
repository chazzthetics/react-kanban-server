<?php

namespace App\Observers;

use App\Board;
use Illuminate\Support\Facades\Auth;

class BoardObserver
{
    //TODO: refactor - duplicate logic
    public function creating(Board $board)
    {
        $boards = Auth::user()->boards()->get()->except($board->id);
        foreach ($boards as $board) {
            $board->update(['is_current' => false]);
        }
    }

    public function created(Board $board)
    {
        $board->recordActivity('created');
    }

    public function updating(Board $board)
    {
        $boards = Auth::user()->boards()->get()->except($board->id);
        foreach ($boards as $board) {
            $board->update(['is_current' => false]);
        }

        $board->old = $board->getOriginal();
    }

    public function updated(Board $board)
    {
        if ($board->isDirty('title')) {
            $board->recordActivity('title_updated');
        }
    }

    public function deleted(Board $board)
    {
        if (Auth::user()->boards()->count() > 0) {
            $lastBoard = Auth::user()->boards()->latest()->first();
            $lastBoard->update(['is_current' => true]);
        }

        $board->recordActivity('removed');
    }
}
