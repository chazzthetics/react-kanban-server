<?php

namespace App\Http\Controllers;

use App\Column;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//TODO: refactor
class CopyColumnController extends Controller
{
    public function __invoke(Request $request, string $uuid)
    {
        $column = Column::where('uuid', $uuid)->firstOrFail();

        // Copy column
        $copiedColumn = Column::create([
            'uuid' => $request->column['uuid'],
            'user_id' => Auth::id(),
            'title' => $request->column['title'],
            'position' => $column->position + 1,
            'board_id' => $column->board_id,
        ]);

        // Update column positions
        $columns = Column::where([
            ['board_id', $copiedColumn->board_id],
            ['uuid', '!=', $uuid],
            ['uuid', '!=', $copiedColumn->uuid],
            ['position', '>=', $copiedColumn->position],
        ])->get();

        foreach ($columns as $column) {
            $column->update(['position' => $column->position + 1]);
        }

        // Copy column tasks
        foreach ($request->tasks as $task) {
            $copiedTask = $copiedColumn->addTask([
                'user_id' => Auth::id(),
                'column_id' => $copiedColumn->id,
                'uuid' => $task['uuid'],
                'title' => $task['title'],
                'description' => $task['description'],
                'position' => $task['position'],
                'due_date' => $task['due_date'],
            ]);

            if (count($task['labels']) > 0) {
                foreach ($task['labels'] as $label) {
                    $copiedTask->labels()->attach($label);
                }
            }

            if ($task['priority']) {
                $copiedTask->priority()->attach($task['priority']);
            }

            if ($task['checklist']) {
                $copiedChecklist = $copiedTask->addChecklist([
                    'uuid' => $task['checklist']['uuid'],
                    'title' => $task['checklist']['title'],
                    'task_id' => $copiedTask->id,
                ]);

                if (count($task['checklist']['items']) > 0) {
                    foreach ($task['checklist']['items'] as $item) {
                        $copiedChecklist->addItem([
                            'uuid' => $item['uuid'],
                            'title' => $item['title'],
                            'checklist_id' => $copiedChecklist->id,
                        ]);
                    }
                }
            }

            $copiedTask->recordActivity('copied');
        }

        return response()->json(['message' => 'Column copied']);
    }
}
