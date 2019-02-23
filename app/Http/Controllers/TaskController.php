<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\Task as TaskResource;

class TaskController extends Controller
{

    // Return all tasks a user needs to do, paginated
    public function index(): TaskCollection
    {
        $tasks = QueryBuilder::for(Task::class)
            ->where('owner_id', auth()->user()->id)
            ->allowedIncludes(['folders'])
            ->jsonPaginate();

        return new TaskCollection($tasks);
    }

    public function show(Task $task)
    {
        if ($task->owner_id == auth()->user()->id) {
            return new TaskResource($task);
        }

        return response()->json(['message' => 'Unauthorized'])->setStatusCode(401);
    }

    public function store(): JsonResponse
    {
        $attributes = request()->validate([
            'title' => 'required',
            'description' => 'string',
            'due_at' => 'date'
        ]);

        $attributes['owner_id'] = auth()->user()->id;

        $task = Task::create($attributes);

        return response()->json($task)->setStatusCode(201);
    }

    public function update(Task $task): JsonResponse
    {
        if ($task->owner_id == auth()->user()->id) {
            $attributes = request()->validate([
                'title' => 'string',
                'description' => 'string'
            ]);

            $attributes['due_at'] = request('due_at') ?? null;

            $task->update($attributes);

            return response()->json($task);
        }

        return response()->json(['message' => 'Unauthorized'])->setStatusCode(401);
    }

    public function destroy(Task $task): JsonResponse
    {
        if ($task->owner_id == auth()->user()->id) {
            $task->delete();

            return response()->json([
                'message' => 'Task deleted successfully'
            ])->setStatusCode(200);
        }

        return response()->json(['message' => 'Unauthorized'])->setStatusCode(401);
    }
}
