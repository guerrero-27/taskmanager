@extends('layouts.app')

@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-500 text-sm mt-1">Kamusta, {{ auth()->user()->name }}! 👋</p>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
        <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Tasks</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
        <p class="text-3xl font-bold text-yellow-500">{{ $stats['pending'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Pending</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
        <p class="text-3xl font-bold text-blue-500">{{ $stats['in_progress'] }}</p>
        <p class="text-xs text-gray-500 mt-1">In Progress</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
        <p class="text-3xl font-bold text-green-500">{{ $stats['completed'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Completed</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
        <p class="text-3xl font-bold text-red-500">{{ $stats['overdue'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Overdue</p>
    </div>
</div>

{{-- Progress Bar --}}
@if($stats['total'] > 0)
    @php $percent = round(($stats['completed'] / $stats['total']) * 100) @endphp
    <div class="bg-white rounded-xl border border-gray-200 p-5 mb-8">
        <div class="flex justify-between text-sm mb-2">
            <span class="font-medium text-gray-700">Overall Progress</span>
            <span class="text-indigo-600 font-bold">{{ $percent }}%</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-3">
            <div class="bg-indigo-500 h-3 rounded-full transition-all"
                 style="width: {{ $percent }}%"></div>
        </div>
    </div>
@endif

{{-- Recent Tasks --}}
<div class="bg-white rounded-xl border border-gray-200 p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-semibold text-gray-700">Recent Tasks</h2>
        <a href="{{ route('tasks.index') }}" class="text-sm text-indigo-600 hover:underline">
            View all →
        </a>
    </div>
    <div class="space-y-3">
        @forelse($recentTasks as $task)
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 rounded-full flex-shrink-0
                    {{ $task->status === 'completed' ? 'bg-green-400' : '' }}
                    {{ $task->status === 'in_progress' ? 'bg-blue-400' : '' }}
                    {{ $task->status === 'pending' ? 'bg-yellow-400' : '' }}">
                </div>
                <p class="text-sm text-gray-700 flex-1
                    {{ $task->status === 'completed' ? 'line-through text-gray-400' : '' }}">
                    {{ $task->title }}
                </p>
                @if($task->category)
                    <span class="text-xs px-2 py-0.5 rounded-full text-white"
                          style="background-color: {{ $task->category->color }}">
                        {{ $task->category->name }}
                    </span>
                @endif
            </div>
        @empty
            <p class="text-sm text-gray-400">Wala pang tasks.</p>
        @endforelse
    </div>
</div>

@endsection