<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\TaskResource;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Cache::remember('tasks', 3600, function () {
            return Task::paginate(15);
        });
        return TaskResource::collection($tasks);    
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $request->user()->tasks()->create($request->validated());
        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        return new TaskResource($task);
    }


    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(null, 204);
    }
}