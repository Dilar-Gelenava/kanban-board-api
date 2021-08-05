<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\Task;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function tasks($state_id)
    {
        $tasks = Task::where('state_id', $state_id)->get();

        if (count($tasks) > 0) {
            return response()->json(['success' => true, 'data' => $tasks], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'state tasks not found'], 400);
        }
    }

    public function add(Request $request)
    {
        $state = new State();
        $state->name = $request->name;
        if ($state->save()) {
            return response()->json(['success' => true, 'message' => 'state added'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'state not added'], 500);
        }
    }

    public function delete($id)
    {
        $state = State::find($id);
        $has_no_tasks = !count($state->tasks) > 0;

        if ($has_no_tasks && $state->delete()) {
            return response()->json(['success' => true, 'message' => 'state deleted'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'state not deleted'], 500);
        }
    }
}
