<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\DeleteTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laracasts\Flash\Flash;
use Mockery\Exception;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();
        $statuses = TaskStatus::all();
        $users = User::all();

        return view('task.index', compact('tasks', 'statuses', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = TaskStatus::all();
        $users = User::all();
        $labels = Label::all();

        return view('task.create', compact('statuses', 'users', 'labels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            $taskData = $request->validated();
            $task = new Task($taskData);
            $task->created_by_id = auth()->id();
            $task->save();
            Flash::success('Задача создана');
        } catch(Exception $e) {
            Flash::error('Не удалось создать задачу');
        }

        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::findOrFail($id);
        return view('task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $statuses = TaskStatus::all();
        $users = User::all();
        $labels = Label::all();

        return view('task.edit', compact('task', 'statuses', 'users', 'labels'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            $taskData = $request->validated();
            $task->update($taskData);
            $task->labels()->sync($request->input('labels'));
            Flash::success('Задача обновлена');
        } catch (Exception $e) {
            Flash::error('Не удалось обновить задачу');
        }
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $user = auth()->user();

        try {
            if ($user->can('delete', $task)) {
                $task->delete();
                Flash::success('Задача удалена');
            } else {
                Flash::error('Нельзя удалить задачу, созданную другим пользователем');
            }
        } catch (Exception $e) {
            Flash::error('Не удалось удалить задачу');
        }

        return redirect()->route('tasks.index');
    }
}
