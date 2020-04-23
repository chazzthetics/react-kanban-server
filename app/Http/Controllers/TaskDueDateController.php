<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TaskDueDateController extends Controller
{
    public function update(Request $request, string $uuid)
    {
        $task = Task::where('uuid', $uuid)->firstOrFail();
        $dueDate = Carbon::parse($request->due_date);

        $task->update(['due_date' => $dueDate]);

        return response()->json(['message' => 'Due date added']);
    }

    public function destroy(string $uuid)
    {
        $task = Task::where('uuid', $uuid)->firstOrFail();
        $task->update(['due_date' => null]);

        return response()->json(['message' => 'Due date removed']);
    }
}
