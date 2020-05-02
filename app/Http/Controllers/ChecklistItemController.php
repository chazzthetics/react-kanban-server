<?php

namespace App\Http\Controllers;

use App\ChecklistItem;
use App\Task;
use Illuminate\Http\Request;

class ChecklistItemController extends Controller
{
    public function store(Request $request, string $uuid)
    {
        $this->validate($request, [
            'uuid' => 'required|unique:checklist_items,uuid',
            'title' => 'required',
        ]);

        $task = Task::where('uuid', $uuid)->firstOrFail();

        $item = $task->checklist->addItem([
            'uuid' => $request->uuid,
            'title' => $request->title,
            'checklist_id' => $task->checklist->id,
        ]);

        return response()->json($item, 201);
    }

    public function update(string $uuid)
    {
        $item = ChecklistItem::where('uuid', $uuid)->firstOrFail();

        $item->update([
            'completed' => !$item->completed,
        ]);

        return response()->json(['message' => 'Checklist item toggled']);
    }

    public function destroy(string $uuid)
    {
        $item = ChecklistItem::where('uuid', $uuid)->firstOrFail();

        $item->delete();

        return response()->json(['message' => 'Checklist item removed']);
    }
}
