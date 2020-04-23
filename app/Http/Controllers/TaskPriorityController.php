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

        return response()->json(['message' => 'Priority added']);
    }

    public function update(Request $request, string $uuid)
    {
        $task = Task::where('uuid', $uuid)->firstOrFail();
        $task->priority()->detach();

        return response()->json(['message' => 'Priority removed']);
    }
}