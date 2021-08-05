<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use App\Models\State;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function task($id)
    {
        $task = Task::with(['users', 'state', 'priority'])->where('id', $id)->first();

        if ($task) {
            return response()->json(['success' => true, 'data' => $task], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'task not found'], 400);
        }
    }

    public function tasks()
    {
        $tasks = Task::all();

        if ($tasks) {
            return response()->json(['success' => true, 'data' => $tasks], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'tasks not found'], 400);
        }
    }

    public function user_tasks($id)
    {
        $user = User::where('id', $id)->first();
        if ($user !== null) {
            return response()->json(['success' => true, 'data' => $user->tasks], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'user tasks not found'], 400);
        }
    }

    public function add(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required'
        ]);
        $task = new Task();
        $task->name = $request->name;
        $task->description = $request->description;
        if ($task->save()) {
            return response()->json(['success' => true, 'message' => 'task added'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'task not added'], 500);
        }
    }

    public function delete($id)
    {
        $task = Task::find($id);
        if ($task->delete()) {
            return response()->json(['success' => true, 'message' => 'task deleted'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'task not deleted'], 500);
        }
    }

    public function task_users(Request $request, $id)
    {
        $task = Task::find($id);
        if ($task->users()->sync($request->users)) {
            return response()->json(['success' => true, 'message' => 'users synced'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'users not synced'], 500);
        }
    }

    public function task_state($task_id, $state_id)
    {
        $task = Task::where('id', $task_id);
        $state_exists = State::where('id', $state_id)->exists();

        if ($state_exists && $$task->update(['state_id' => $state_id])) {
            return response()->json(['success' => true, 'message' => 'state synced'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'state not synced'], 500);
        }
    }

    public function task_priority($task_id, $priority_id)
    {
        $task = Task::where('id', $task_id);
        $priority_exists = Priority::where('id', $priority_id)->exists();

        if ($priority_exists && $task->update(['priority_id' => $priority_id])) {
            return response()->json(['success' => true, 'message' => 'priority synced'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'priority not synced'], 500);
        }
    }

}
