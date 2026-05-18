<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Category;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = auth()->user()->tasks()->with('category')->latest()->get();

        $categories = auth()->user()->categories()->get();

        return view('tasks.index', compact('tasks', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        auth()->user()->tasks()->create($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task Created Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task Deleted Successfully!');
    }

    public function toggleStatus(Task $task)
    {
        abort_if($task->user_id !== auth()->id(), 403);

        $task->update([
            'status' => $task->status === 'completed' ? 'pending' : 'completed'
        ]);

        return redirect()->route('tasks.index');
    }
}
