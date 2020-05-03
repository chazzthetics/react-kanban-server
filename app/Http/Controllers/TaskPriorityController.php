<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TaskPriorityController extends Controller
{
    public function store(Request $request, string $uuid)
    {
        $task = Task::where('uuid', $uuid)->firstOrFail();

        if ($task->priority) {
            $task->priority()->detach();
        }

        $task->priority()->attach($request->priority);
        $task->recordActivity('priority_changed');

        return response()->json(['message' => 'Priority added']);
    }

    public function update(string $uuid)
    {
        $task = Task::where('uuid', $uuid)->firstOrFail();
        $task->priority()->detach();
        $task->recordActivity('priority_changed');

        return response()->json(['message' => 'Priority removed']);
    }
}
