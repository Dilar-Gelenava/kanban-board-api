<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use App\Models\Task;
use Illuminate\Http\Request;

class PriorityController extends Controller
{
    public function tasks($priority_id)
    {
        $tasks = Task::where('priority_id', $priority_id)->get();

        if (count($tasks) > 0) {
            return response()->json(['success' => true, 'data' => $tasks], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'priority tasks not found'], 400);
        }
    }

    public function add(Request $request)
    {
        $priority = new Priority();
        $priority->name = $request->name;
        if ($priority->save()) {
            return response()->json(['success' => true, 'message' => 'priority added'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'priority not added'], 500);
        }
    }

    public function delete($id)
    {
        $priority = Priority::find($id);
        $has_no_tasks = !count($priority->tasks) > 0;

        if ($has_no_tasks && $priority->delete()) {
            return response()->json(['success' => true, 'message' => 'priority deleted'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'priority not deleted'], 500);
        }
    }
}
