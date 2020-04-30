<?php

namespace App\Observers;

use App\Board;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BoardObserver
{
    public function saving(Board $board)
    {
        $board->slug = Str::slug($board->title);
    }

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
    }

    public function updated(Board $board)
    {
        if ($board->isDirty('title')) {
            $board->recordActivity('title_updated');
        }

        if ($board->isDirty('background')) {
            $board->recordActivity('background_updated');
        }

        if ($board->isDirty('description')) {
            $board->recordActivity('description_updated');
        }

        if ($board->isDirty('is_starred') && $board->is_starred) {
            $board->recordActivity('starred');
        } elseif ($board->isDirty('is_starred') && !$board->is_starred) {
            $board->recordActivity('unstarred');
        }
    }

    public function deleted()
    {
        if (Auth::user()->boards()->count() > 0) {
            $lastBoard = Auth::user()->boards()->latest()->first();
            $lastBoard->update(['is_current' => true]);
        }
    }
}
