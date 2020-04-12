<?php

namespace App\Http\Controllers;

use App\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::where('user_id', Auth::id())->latest()->get();

        return response()->json($activities);
    }

    public function destroy(int $id)
    {
        Activity::destroy($id);

        return response()->json(['message' => 'Activity removed']);
    }

    public function clear(Request $request)
    {
        Activity::destroy($request->activities);

        return response()->json(['message' => 'Activities cleared']);
    }
}
