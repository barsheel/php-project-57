<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStatus\StoreTaskStatusRequest;
use App\Http\Requests\TaskStatus\UpdateTaskStatusRequest;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taskStatuses = QueryBuilder::for(TaskStatus::class)
            ->paginate(15)
            ->withQueryString();

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
    public function store(StoreTaskStatusRequest $request)
    {
        $data = $request->validated();
        TaskStatus::create($data);
        Flash::success(__('flash.task_status.store.success'));
        return redirect()->route('task_statuses.index');
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
    public function update(UpdateTaskStatusRequest $request, TaskStatus $taskStatus)
    {
        $data = $request->validated();
        $taskStatus->update($data);
        Flash::success(__('flash.task_status.update.success'));
        return redirect()->route('task_statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskStatus $taskStatus)
    {
        if ($taskStatus->tasks->exists()) {
            $taskStatus->delete();
            Flash::success(__('flash.task_status.destroy.success'));
        } else {
            Flash::error(__('flash.task_status.destroy.error'));
        }

        return redirect()->route('task_statuses.index');
    }
}
