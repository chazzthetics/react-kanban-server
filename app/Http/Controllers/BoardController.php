<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BoardController extends Controller
{
    public function index(Request $request)
    {
        if ('count' === $request->query('q')) {
            $boards = Auth::user()->boards()->count();
        } else {
            $boards = Auth::user()->boards()->with([
                'columns', 'columns.tasks', 'columns.tasks.labels', 'columns.tasks.priority',
            ])->get();
        }

        return response()->json($boards);
    }

    public function store(Request $request)
    {
        //TODO: validation -- uuid validiation later

        $this->validate($request, [
            'uuid' => 'required',
            'title' => 'required|string|max:30',
            'background' => 'required|string|max:30',
            'description' => '',
        ]);

        $board = Board::create([
            'user_id' => Auth::id(),
            'uuid' => $request->uuid,
            'title' => $request->title,
            'background' => $request->background,
            'description' => $request->description,
        ]);

        return response()->json($board, 201);
    }

    public function update(Request $request, string $uuid)
    {
        $board = Board::where('uuid', $uuid)->firstOrFail();

        if ($request->boolean('current')) {
            $board->update(['is_current' => true]);
        }

        if ($request->has('is_starred')) {
            $board->update(['is_starred' => !$request->is_starred]);
        }

        if ($request->has('clear')) {
            $board->columns()->delete();

            return response()->json(['message' => 'Board cleared']);
        }

        $this->validate($request, [
            'title' => 'sometimes|required|max:30',
            'background' => 'sometimes|required',
            'description' => '',
        ]);

        if ($request->title) {
            $board->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
            ]);
        } elseif ($request->background) {
            $board->update(['background' => $request->background]);
        } elseif ($request->description) {
            $board->update(['description' => $request->description]);
        } elseif (empty($request->description) && !$request->title) {
            $board->update(['description' => null]);
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
