<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;

class ReorderController extends Controller
{
    public function columns(Request $request, string $uuid)
    {
        $board = Board::where('uuid', $uuid)->firstOrFail();
        $columns = $board->columns;

        foreach ($columns as $column) {
            foreach ($request->newOrder as $index => $position) {
                if ($column->uuid === $position) {
                    $column->position = $index;
                    $column->save();
                }
                // $column->update(['position' => $position]);
            }
        }

        return response()->json(['message' => 'Column reordered']);
    }
}
