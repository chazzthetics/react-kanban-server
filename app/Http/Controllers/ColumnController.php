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

    public function update(Request $request, string $uuid)
    {
        $column = Column::where('uuid', $uuid)->firstOrFail();

        if ($request->has('clear')) {
            $column->tasks()->delete();
            $column->recordActivity('cleared');

            return response()->json(['message' => 'Column cleared']);
        }

        if ($request->has('is_locked')) {
            $column->update(['is_locked' => !$request->is_locked]);

            return response()->json(['locked' => !$request->is_locked]);
        }

        $this->validate($request, [
            'title' => 'sometimes|required:max30',
        ]);

        if ($request->title) {
            $column->update(['title' => $request->title]);
        }

        return response()->json(['message' => 'Column updated']);
    }

    public function destroy(string $uuid)
    {
        $column = Column::where('uuid', $uuid)->firstOrFail();
        $column->delete();

        return response()->json(['message' => 'Column removed']);
    }
}
