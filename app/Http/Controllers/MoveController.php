<?php

namespace App\Http\Controllers;

use App\Board;
use App\Column;
use Illuminate\Http\Request;

class MoveController extends Controller
{
    public function __invoke(Request $request, string $uuid)
    {
        $startBoard = Board::where('uuid', $uuid)->firstOrFail();
        $endBoard = Board::where('uuid', $request->endBoardId)->firstOrFail();

        foreach ($request->startOrder as $index => $position) {
            $column = Column::where('uuid', $position)->firstOrFail();
            $column->update(['board_id' => $startBoard->id, 'position' => $index]);
        }

        foreach ($request->endOrder as $index => $position) {
            $column = Column::where('uuid', $position)->firstOrFail();
            $column->update(['board_id' => $endBoard->id, 'position' => $index]);
        }

        return response()->json(['message' => 'Column moved']);
    }
}
