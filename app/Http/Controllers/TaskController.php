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
            'content' => 'required|string',
        ]);

        $column = Column::where('uuid', $uuid)->withCount('tasks')->firstOrFail();

        $task = $column->addTask([
            'uuid' => $request->uuid,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'column_id' => $column->id,
            'position' => $column->tasks_count,
        ]);

        return response()->json($task, 201);
    }

    public function destroy(string $uuid)
    {
        $task = Task::where('uuid', $uuid)->firstOrFail();
        $task->delete();

        return response()->json(['message' => 'Task removed']);
    }
}
