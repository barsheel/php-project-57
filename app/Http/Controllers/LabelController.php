<?php

namespace App\Http\Controllers;

use App\Http\Requests\Label\StoreLabelRequest;
use App\Http\Requests\Label\UpdateLabelRequest;
use App\Models\Label;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Mockery\Exception;

class LabelController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labels = Label::all();
        return view('label.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Label::class);
        return view('label.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLabelRequest $request)
    {
        $this->authorize('create', Label::class);
        $label = Label::create($request->validated());
        $label->save();
        Flash::success(__('flash.label.store.success'));
        return redirect()->route('labels.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Label $label)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Label $label)
    {
        $this->authorize('update', $label);
        return view('label.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLabelRequest $request, Label $label)
    {
        $this->authorize('update', $label);
        $label->update($request->validated());
        Flash::success(__('flash.label.update.success'));
        return redirect()->route('labels.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label)
    {
        $this->authorize('delete', $label);
        if ($label->tasks()->exists()) {
            Flash::error(__('flash.label.destroy.error'));
        } else {
            $label->delete();
            Flash::success(__('flash.label.destroy.success'));
        }

        return redirect()->route('labels.index');
    }
}
