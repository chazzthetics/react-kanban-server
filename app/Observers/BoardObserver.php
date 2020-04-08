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

    public function updating(Board $board)
    {
        $boards = Auth::user()->boards()->get()->except($board->id);
        foreach ($boards as $board) {
            $board->update(['is_current' => false]);
        }
    }

    public function deleted()
    {
        $lastBoard = Auth::user()->boards()->latest()->first();
        $lastBoard->update(['is_current' => true]);
    }
}
