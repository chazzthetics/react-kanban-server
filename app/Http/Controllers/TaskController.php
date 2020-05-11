<?php

namespace App\Http\Controllers;

use App\Column;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(Request $request, string $uuid)
    {
        $this->validate($request, [
            'uuid' => 'required|unique:tasks',
            'title' => 'required|string',
        ]);

        $column = Column::where('uuid', $uuid)->withCount('tasks')->firstOrFail();

        $task = $column->addTask([
            'uuid' => $request->uuid,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'column_id' => $column->id,
            'position' => $column->tasks_count,
        ]);

        $task->recordActivity('created');

        return response()->json($task, 201);
    }

    public function update(Request $request, string $uuid)
    {
        $task = Task::where('uuid', $uuid)->firstOrFail();

        $this->validate($request, [
            'title' => 'sometimes|required',
            'description' => '',
        ]);

        if ($request->title) {
            $task->update(['title' => $request->title]);
        }

        if ($request->filled('description')) {
            $task->update(['description' => $request->description]);
        }

        if ($request->has('description') && !$request->description) {
            $task->update(['description' => null]);
        }

        if ($request->has('completed')) {
            $task->update(['completed' => !$request->completed]);
        }

        return response()->json(['message' => 'Task updated']);
    }

    public function destroy(string $uuid)
    {
        $task = Task::where('uuid', $uuid)->firstOrFail();
        $task->delete();

        return response()->json(['message' => 'Task removed']);
    }
}
