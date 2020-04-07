<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BoardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return Auth::user()->boards()->with('columns')->get();
    }

    public function store(Request $request)
    {
        //TODO: validation

        $this->validate($request, [
            'uuid' => 'required|uuid',
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

        //TODO: move out of controller, put in observer?
        $currentBoard = Auth::user()->boards()->where('is_current', true)->firstOrFail();

        $currentBoard->update(['is_current' => false]);

        return response()->json($board, 201);
    }

    public function update(Request $request, string $uuid)
    {
        //TODO:
    }

    public function destroy(string $uuid)
    {
        $board = Board::where('uuid', $uuid)->firstOrFail();
        $board->delete();

        return response()->json(['message' => 'Board removed']);
    }
}
