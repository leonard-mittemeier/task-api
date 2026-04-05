<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    // GET /api/tasks — список всех задач
    public function index()
    {
        $tasks = Task::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }

    // POST /api/tasks — создание задачи
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => ['nullable', Rule::in(['pending', 'in_progress', 'completed'])]
        ]);

        $task = Task::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Задача создана',
            'data' => $task
        ], 201);
    }

    // GET /api/tasks/{id} — просмотр одной задачи
    public function show($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Задача не найдена'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $task
        ]);
    }

    // PUT /api/tasks/{id} — обновление задачи
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Задача не найдена'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => ['sometimes', Rule::in(['pending', 'in_progress', 'completed'])]
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Задача обновлена',
            'data' => $task
        ]);
    }

    // DELETE /api/tasks/{id} — удаление задачи
    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Задача не найдена'
            ], 404);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Задача удалена'
        ]);
    }
}