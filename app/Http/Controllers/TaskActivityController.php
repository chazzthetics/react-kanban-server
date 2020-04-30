<?php

namespace App\Http\Controllers;

use App\Task;

class TaskActivityController extends Controller
{
    public function index(string $uuid)
    {
        $task = Task::where('uuid', $uuid)->firstOrFail();

        $activities = $task->activities()->get();

        return response()->json($activities);
    }
}
