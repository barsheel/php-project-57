<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class TaskStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taskStatuses = TaskStatus::all();
        return view('task_status.index', compact('taskStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('task_status.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate(['name' => 'required']);
            TaskStatus::create($request->all());
        } catch (\Exception $exception) {
            Flash::error('Не удалось создать статус');
        }
        return redirect()->route('task_statuses.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskStatus $taskStatus)
    {
        return "всё пошло не так";
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskStatus $taskStatus)
    {
        return view('task_status.edit', compact('taskStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskStatus $taskStatus)
    {
        try {
            $request->validate(['name' => 'required']);
            $taskStatus->update($request->all());
            Flash::success('Статус обновлен');
        } catch (\Exception $exception) {
            Flash::error('Не удалось обновить статус');
        }
        return redirect()->route('task_statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskStatus $taskStatus)
    {
        try {
            if ($taskStatus)
            $taskStatus->delete();
            Flash::success('Статус удалён');
        } catch (\Exception $exception) {
            Flash::error('Не удалось удалить статус');
        }
        return redirect()->route('task_statuses.index');
    }
}
