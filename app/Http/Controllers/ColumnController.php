<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(string $uuid)
    {
        //TODO:
        // return response()->json($columns);
    }

    public function store(Request $request, string $uuid)
    {
        //TODO: validate
        $this->validate($request, [
            'uuid' => 'required|unique:columns,uuid',
            'title' => 'required|string|max:30',
        ]);

        $board = Board::where('uuid', $uuid)->withCount('columns')->firstOrFail();

        $column = $board->addColumn([
            'uuid' => $request->uuid,
            'title' => $request->title,
            'board_id' => $board->id,
            'position' => $board->columns_count,
        ]);

        return response()->json($column, 201);
    }
}
