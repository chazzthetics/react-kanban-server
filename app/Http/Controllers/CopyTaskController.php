<?php

namespace App\Http\Controllers;

use App\Column;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CopyTaskController extends Controller
{
    //TODO: refactor
    public function __invoke(Request $request, string $uuid)
    {
        $task = Task::where('uuid', $uuid)->firstOrFail();

        $copiedTask = Task::create([
            'user_id' => Auth::id(),
            'uuid' => $request->task['uuid'],
            'column_id' => Column::where('uuid', $request->columnId)->first()->id,
            'title' => $request->task['title'],
            'position' => $request->task['position'],
            'description' => $task['description'],
            'due_date' => $task['due_date'],
        ]);

        // Update task positions
        $tasks = Task::where([
            ['column_id', $copiedTask->column_id],
            ['uuid', '!=', $copiedTask->uuid],
            ['position', '>=', $copiedTask->position],
        ])->get();

        foreach ($tasks as $task) {
            $task->update(['position' => $task->position + 1]);
        }

        if (count($request->task['labels']) > 0) {
            foreach ($request->task['labels'] as $label) {
                $copiedTask->labels()->attach($label);
            }
        }

        if ($request->task['priority']) {
            $copiedTask->priority()->attach($request->task['priority']);
        }

        if ($request->task['checklist']) {
            $copiedChecklist = $copiedTask->addChecklist([
                'uuid' => $request->task['checklist']['uuid'],
                'title' => $request->task['checklist']['title'],
                'task_id' => $copiedTask->id,
            ]);

            if (count($request->task['checklist']['items']) > 0) {
                foreach ($request->task['checklist']['items'] as $item) {
                    $copiedChecklist->addItem([
                        'uuid' => $item['uuid'],
                        'title' => $item['title'],
                        'checklist_id' => $copiedChecklist->id,
                    ]);
                }
            }
        }

        $copiedTask->recordActivity('copied');

        return response()->json(['message' => 'Task copied']);
    }
}
