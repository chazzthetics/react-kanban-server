<?php

namespace App\Http\Controllers;

use App\Board;
use App\Column;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColumnController extends Controller
{
    public function index()
    {
        $columns = Column::where('user_id', Auth::id())->with('tasks')->get();

        return response()->json($columns);
    }

    public function store(Request $request, string $uuid)
    {
        //TODO: validate (uuid)
        $this->validate($request, [
            'uuid' => 'required|unique:columns',
            'title' => 'required|string|max:30',
        ]);

        $board = Board::where('uuid', $uuid)->withCount('columns')->firstOrFail();

        $column = $board->addColumn([
            'uuid' => $request->uuid,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'board_id' => $board->id,
            'position' => $board->columns_count,
        ]);

        return response()->json($column, 201);
    }

    public function destroy(string $uuid)
    {
        $column = Column::where('uuid', $uuid)->firstOrFail();
        $column->delete();

        return response()->json(['message' => 'Column removed']);
    }
}
