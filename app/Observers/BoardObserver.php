<?php

namespace App\Observers;

use App\Board;
use Illuminate\Support\Facades\Auth;

class BoardObserver
{
    public function created(Board $board)
    {
        $boards = Auth::user()->boards()->where('id', '!=', $board->id)->get();
        foreach ($boards as $board) {
            $board->update(['is_current' => false]);
        }
    }
}
