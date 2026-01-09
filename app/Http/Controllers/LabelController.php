<?php

namespace App\Http\Controllers;

use App\Http\Requests\Label\StoreLabelRequest;
use App\Http\Requests\Label\UpdateLabelRequest;
use App\Models\Label;
use App\Models\Task;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Mockery\Exception;

class LabelController extends Controller
{
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
        return view('label.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLabelRequest $request)
    {
        try {
            $label = Label::create($request->validated());
            $label->save();
            Flash::success('Метка создана');
        } catch(Exception $e) {
            Flash::error('Не удалось создать метку');
        }
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
        return view('label.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLabelRequest $request, Label $label)
    {
        try {
            $label->update($request->validated());
            Flash::success('Метка обновлена');
        } catch(Exception $e) {
            Flash::error('Не удалось обновить метку');
        }
        return redirect()->route('labels.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label)
    {
        try {
            $label->delete();
            Flash::success('Метка удалена');
        } catch(Exception $e) {
            Flash::error('Не удалось удалить метку');
        }
        return redirect()->route('labels.index');
    }
}
