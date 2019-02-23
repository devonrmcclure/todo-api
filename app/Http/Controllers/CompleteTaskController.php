<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class CompleteTaskController extends Controller
{
    public function store()
    {
        $task = Task::findOrFail(request('id'));

        $task->complete();

        return response()->json($task)->setStatusCode(201);
    }

    public function destroy(Task $task)
    {
        $task->uncomplete();

        return response()->json($task)->setStatusCode(200);
    }
}
