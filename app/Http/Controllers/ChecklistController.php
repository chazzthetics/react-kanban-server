<?php

namespace App\Http\Controllers;

use App\Checklist;
use App\Task;
use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    public function store(Request $request, string $uuid)
    {
        $task = Task::where('uuid', $uuid)->firstOrFail();

        $this->validate($request, [
            'uuid' => 'required|unique:checklists,uuid',
            'title' => 'required',
        ]);

        $checklist = $task->addChecklist([
            'uuid' => $request->uuid,
            'title' => $request->title,
            'task_id' => $task->id,
        ]);

        // $task->recordActivity('checklist_added');

        return response()->json($checklist, 201);
    }
}
