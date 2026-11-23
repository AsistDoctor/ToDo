<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return Task::orderBy('id')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate(['title' => 'required|string|max:255']);

        return response()->json(Task::create($data), 201);
    }

    // Laravel сам достаёт Task из базы по {task} в URL; не нашёл — сам ответит 404
    public function show(Task $task)
    {
        return $task;
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'done' => 'sometimes|boolean',
        ]);

        $task->update($data);

        return $task;
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->noContent();
    }
}
