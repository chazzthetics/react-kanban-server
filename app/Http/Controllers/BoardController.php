<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BoardController extends Controller
{
    public function index()
    {
        $boards = Auth::user()->boards()->with(['columns', 'columns.tasks'])->get();

        return response()->json($boards);
    }

    public function store(Request $request)
    {
        //TODO: validation -- uuid validiation later

        $this->validate($request, [
            'uuid' => 'required',
            'title' => 'required|string|max:30',
            'color' => 'required|string|max:15',
        ]);

        $board = Board::create([
            'uuid' => $request->uuid,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'color' => $request->color,
        ]);

        return response()->json($board, 201);
    }

    public function update(Request $request, string $uuid)
    {
        $board = Board::where('uuid', $uuid)->firstOrFail();

        if ($request->boolean('current')) {
            $board->update(['is_current' => true]);
        }

        return response()->json(['message' => 'Board updated']);
    }

    public function destroy(string $uuid)
    {
        $board = Board::where('uuid', $uuid)->firstOrFail();
        $board->delete();

        return response()->json(['message' => 'Board removed']);
    }
}
