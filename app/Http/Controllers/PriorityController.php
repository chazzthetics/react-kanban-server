<?php

namespace App\Http\Controllers;

use App\Priority;

class PriorityController extends Controller
{
    public function index()
    {
        $priorities = Priority::all();

        return response()->json($priorities);
    }
}
