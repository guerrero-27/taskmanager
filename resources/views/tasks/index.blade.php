@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">My Tasks</h1>
    <button onclick="document.getElementById('createModal').classList.remove('hidden')"
            class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
        + New Task
    </button>
</div>

{{-- Task List --}}
<div class="space-y-3">
    @forelse($tasks as $task)
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-start gap-4">

            {{-- Toggle Status Button --}}
            <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                @csrf @method('PATCH')
                <button type="submit"
                        class="mt-1 w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0
                               {{ $task->status === 'completed' ? 'bg-green-500 border-green-500' : 'border-gray-400' }}">
                    @if($task->status === 'completed')
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"/>
                        </svg>
                    @endif
                </button>
            </form>

            {{-- Task Info --}}
            <div class="flex-1">
                <p class="font-medium text-gray-800 {{ $task->status === 'completed' ? 'line-through text-gray-400' : '' }}">
                    {{ $task->title }}
                </p>
                @if($task->description)
                    <p class="text-sm text-gray-500 mt-1">{{ $task->description }}</p>
                @endif
                <div class="flex items-center gap-3 mt-2">
                    {{-- Priority Badge --}}
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium
                        {{ $task->priority === 'high' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $task->priority === 'low' ? 'bg-green-100 text-green-700' : '' }}">
                        {{ ucfirst($task->priority) }}
                    </span>
                    {{-- Category Badge --}}
                    @if($task->category)
                        <span class="text-xs px-2 py-0.5 rounded-full text-white"
                              style="background-color: {{ $task->category->color }}">
                            {{ $task->category->name }}
                        </span>
                    @endif
                    {{-- Due Date --}}
                    @if($task->due_date)
                        <span class="text-xs text-gray-400">
                            📅 {{ $task->due_date->format('M d, Y') }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Delete Button --}}
            <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                @csrf @method('DELETE')
                <button type="submit"
                        onclick="return confirm('Delete this task?')"
                        class="text-gray-300 hover:text-red-500 text-lg">✕</button>
            </form>
        </div>
    @empty
        <div class="text-center py-16 text-gray-400">
            <p class="text-4xl mb-3">📋</p>
            <p>Wala pang tasks. Gumawa na ng bago!</p>
        </div>
    @endforelse
</div>

{{-- Create Task Modal --}}
<div id="createModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
        <h2 class="text-lg font-bold text-gray-800 mb-4">New Task</h2>
        <form method="POST" action="{{ route('tasks.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm text-gray-600">Title *</label>
                <input type="text" name="title" required
                       class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="text-sm text-gray-600">Description</label>
                <textarea name="description" rows="2"
                          class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm text-gray-600">Priority</label>
                    <select name="priority"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Status</label>
                    <select name="status"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm text-gray-600">Category</label>
                    <select name="category_id"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">None</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Due Date</label>
                    <input type="date" name="due_date"
                           class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-indigo-600 text-white py-2 rounded-lg text-sm hover:bg-indigo-700">
                    Create Task
                </button>
                <button type="button"
                        onclick="document.getElementById('createModal').classList.add('hidden')"
                        class="flex-1 bg-gray-100 text-gray-600 py-2 rounded-lg text-sm hover:bg-gray-200">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection