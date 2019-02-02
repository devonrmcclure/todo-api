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
	public function index() : TaskCollection
	{
		$tasks = QueryBuilder::for(Task::class)
			->where('owner_id', auth()->user()->id)
			->allowedIncludes(['folders'])
			->jsonPaginate();

		return new TaskCollection($tasks);
	}

	public function show(Task $task) : TaskResource
	{
		return new TaskResource($task);
	}

	public function store() : JsonResponse
	{
		$attributes = request()->validate([
			'title' => 'required', 
			'description' => 'required',
		]);
		
		$attributes['due_at'] = request('due_at') ?? null;
		$attributes['owner_id'] = auth()->user()->id; // TODO use a setOwnerIdAttribute() method on Task model

		$task = Task::create($attributes);
		
		return response()->json($task)->setStatusCode(201);
	}

	public function update(Task $task) : JsonResponse
	{
		$attributes = request()->validate([
			'title' => 'required',
			'description' => 'required'
		]);

		$attributes['due_at'] = request('due_at') ?? null;
		$attributes['status_id'] = request('status_id') ?? $task->status_id;

		if (request('status_id') == 3) {
			$task->complete();
		}

		$task->update($attributes);

		return response()->json($task);
	}

	public function destroy(Task $task) : JsonResponse
	{
		$task->delete();

		return response()->json([
			'message' => 'Task deleted successfully',
			'status' => 200
		])->setStatusCode(200);
	}
}
