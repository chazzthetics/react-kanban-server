<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index()
    {
        $board = Board::where('user_id', Auth::id())->where('is_current', true)->first();

        if (!$board) {
            return response()->json(null);
        }

        $activities = Activity::where('board_id', $board->id)->latest()->paginate(15);

        return response()->json($activities);
    }

    public function show()
    {
        $activitiy = Activity::where('user_id', Auth::id())->latest()->firstOrFail();

        return response()->json($activitiy);
    }

    public function destroy(int $id)
    {
        Activity::destroy($id);

        return response()->json(['message' => 'Activity removed']);
    }

    //FIXME:
    public function clear(Request $request)
    {
        Activity::destroy($request->activities);

        return response()->json(['message' => 'Activities cleared']);
    }
}
