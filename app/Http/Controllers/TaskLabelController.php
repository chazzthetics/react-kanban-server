<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TaskLabelController extends Controller
{
    public function store(Request $request, string $uuid)
    {
        $task = Task::where('uuid', $uuid)->firstOrFail();
        $task->labels()->attach($request->label);

        return response()->json(['message' => 'Label added']);
    }

    public function update(Request $request, string $uuid)
    {
        $task = Task::where('uuid', $uuid)->firstOrFail();
        $task->labels()->detach($request->label);

        return response()->json(['message' => 'Label removed']);
    }

    public function destroy(string $uuid)
    {
        $task = Task::where('uuid', $uuid)->firstOrFail();
        $task->labels()->detach();

        return response()->json(['message' => 'Labels cleared']);
    }
}
