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

{{-- Filter Bar --}}
<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('tasks.index') }}"
       class="px-3 py-1.5 rounded-lg text-sm border
              {{ !request('status') && !request('priority') ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-400' }}">
        All
    </a>
    @foreach(['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed'] as $val => $label)
        <a href="{{ route('tasks.index', ['status' => $val]) }}"
           class="px-3 py-1.5 rounded-lg text-sm border
                  {{ request('status') === $val ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-400' }}">
            {{ $label }}
        </a>
    @endforeach
    <div class="w-px bg-gray-200 mx-1"></div>
    @foreach(['high' => '🔴 High', 'medium' => '🟡 Medium', 'low' => '🟢 Low'] as $val => $label)
        <a href="{{ route('tasks.index', ['priority' => $val]) }}"
           class="px-3 py-1.5 rounded-lg text-sm border
                  {{ request('priority') === $val ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-400' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- Task List --}}
<div class="space-y-3">
    @forelse($tasks as $task)
        @php
            $isOverdue = $task->due_date &&
                         $task->due_date->isPast() &&
                         $task->status !== 'completed';
        @endphp
        <div class="bg-white rounded-xl border p-4 flex items-start gap-4
                    {{ $isOverdue ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">

            {{-- Toggle Status --}}
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
                <div class="flex items-start justify-between gap-2">
                    <p class="font-medium text-gray-800
                               {{ $task->status === 'completed' ? 'line-through text-gray-400' : '' }}">
                        {{ $task->title }}
                        @if($isOverdue)
                            <span class="ml-2 text-xs text-red-600 font-semibold">⚠ Overdue</span>
                        @endif
                    </p>
                    {{-- Edit Button --}}
                    <button onclick="openEditModal(
                                {{ $task->id }},
                                '{{ addslashes($task->title) }}',
                                '{{ addslashes($task->description ?? '') }}',
                                '{{ $task->priority }}',
                                '{{ $task->status }}',
                                '{{ $task->due_date?->format('Y-m-d') ?? '' }}',
                                '{{ $task->category_id ?? '' }}'
                            )"
                            class="text-gray-400 hover:text-indigo-600 text-xs flex-shrink-0">
                        ✏ Edit
                    </button>
                </div>
                @if($task->description)
                    <p class="text-sm text-gray-500 mt-1">{{ $task->description }}</p>
                @endif
                <div class="flex flex-wrap items-center gap-3 mt-2">
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium
                        {{ $task->priority === 'high' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $task->priority === 'low' ? 'bg-green-100 text-green-700' : '' }}">
                        {{ ucfirst($task->priority) }}
                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium
                        {{ $task->status === 'pending' ? 'bg-gray-100 text-gray-600' : '' }}
                        {{ $task->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $task->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                    @if($task->category)
                        <span class="text-xs px-2 py-0.5 rounded-full text-white"
                              style="background-color: {{ $task->category->color }}">
                            {{ $task->category->name }}
                        </span>
                    @endif
                    @if($task->due_date)
                        <span class="text-xs {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-400' }}">
                            📅 {{ $task->due_date->format('M d, Y') }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Delete --}}
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

{{-- Create Modal --}}
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

{{-- Edit Modal --}}
<div id="editModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Edit Task</h2>
        <form method="POST" id="editForm" class="space-y-4">
            @csrf @method('PATCH')
            <div>
                <label class="text-sm text-gray-600">Title *</label>
                <input type="text" name="title" id="edit_title" required
                       class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="text-sm text-gray-600">Description</label>
                <textarea name="description" id="edit_description" rows="2"
                          class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm text-gray-600">Priority</label>
                    <select name="priority" id="edit_priority"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Status</label>
                    <select name="status" id="edit_status"
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
                    <select name="category_id" id="edit_category"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">None</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Due Date</label>
                    <input type="date" name="due_date" id="edit_due_date"
                           class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-indigo-600 text-white py-2 rounded-lg text-sm hover:bg-indigo-700">
                    Save Changes
                </button>
                <button type="button"
                        onclick="document.getElementById('editModal').classList.add('hidden')"
                        class="flex-1 bg-gray-100 text-gray-600 py-2 rounded-lg text-sm hover:bg-gray-200">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

{{-- JavaScript para sa Edit Modal --}}
<script>
function openEditModal(id, title, description, priority, status, dueDate, categoryId) {
    // I-set ang form action para sa tamang task
    document.getElementById('editForm').action = '/tasks/' + id;

    // I-populate ang fields
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_priority').value = priority;
    document.getElementById('edit_status').value = status;
    document.getElementById('edit_due_date').value = dueDate;
    document.getElementById('edit_category').value = categoryId;

    // Ipakita ang modal
    document.getElementById('editModal').classList.remove('hidden');
}
</script>

@endsection