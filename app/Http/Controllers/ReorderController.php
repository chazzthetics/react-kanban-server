<?php

namespace App\Http\Controllers;

use App\Board;
use App\Column;
use App\Task;
use Illuminate\Http\Request;

class ReorderController extends Controller
{
    public function columns(Request $request, string $uuid)
    {
        $board = Board::where('uuid', $uuid)->firstOrFail();
        $columns = $board->columns;

        foreach ($columns as $column) {
            foreach ($request->newOrder as $index => $position) {
                if ($column->uuid === $position) {
                    $column->update(['position' => $index]);
                }
            }
        }

        return response()->json(['message' => 'Column reordered']);
    }

    public function tasks(Request $request, string $uuid)
    {
        $column = Column::where('uuid', $uuid)->firstOrFail();
        $tasks = $column->tasks;

        foreach ($tasks as $task) {
            foreach ($request->newOrder as $index => $position) {
                if ($task->uuid === $position) {
                    $task->update(['position' => $index]);
                }
            }
        }

        return response()->json(['message' => 'Task reordered']);
    }

    // TODO: refactor
    public function between(Request $request, string $start_uuid, string $end_uuid)
    {
        $startColumn = Column::where('uuid', $start_uuid)->firstOrFail();
        $endColumn = Column::where('uuid', $end_uuid)->firstOrFail();

        if (empty($request->startOrder)) {
            foreach ($request->endOrder as $index => $position) {
                $task = Task::where('uuid', $position)->firstOrFail();
                $task->update(['column_id' => $endColumn->id, 'position' => $index]);
            }
        } else {
            foreach ($request->startOrder as $index => $position) {
                $task = Task::where('uuid', $position)->firstOrFail();
                $task->update(['column_id' => $startColumn->id, 'position' => $index]);
            }

            foreach ($request->endOrder as $index => $position) {
                $task = Task::where('uuid', $position)->firstOrFail();
                $task->update(['column_id' => $endColumn->id, 'position' => $index]);
            }
        }

        return response()->json(['message' => 'Task reordered between']);
    }
}
