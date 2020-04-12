<?php

namespace App\Http\Controllers;

use App\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::where('user_id', Auth::id())->get();

        return response()->json($activities);
    }
}
