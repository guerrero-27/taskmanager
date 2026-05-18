@extends('layouts.app')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Categories</h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Create Category Form --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-700 mb-4">New Category</h2>
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm text-gray-600">Name *</label>
                <input type="text" name="name" required
                       class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="text-sm text-gray-600">Color</label>
                <div class="flex items-center gap-3 mt-1">
                    <input type="color" name="color" value="#6366f1"
                           class="w-10 h-10 rounded cursor-pointer border border-gray-300">
                    <span class="text-sm text-gray-400">Piliin ang color ng category</span>
                </div>
            </div>
            <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 rounded-lg text-sm hover:bg-indigo-700">
                Create Category
            </button>
        </form>
    </div>

    {{-- Category List --}}
    <div class="space-y-3">
        @forelse($categories as $category)
            <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full flex-shrink-0"
                         style="background-color: {{ $category->color }}"></div>
                    <div>
                        <p class="font-medium text-gray-800">{{ $category->name }}</p>
                        <p class="text-xs text-gray-400">
                            {{ $category->tasks()->count() }} tasks
                        </p>
                    </div>
                </div>
                <form method="POST" action="{{ route('categories.destroy', $category) }}">
                    @csrf @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Delete this category?')"
                            class="text-gray-300 hover:text-red-500">✕</button>
                </form>
            </div>
        @empty
            <div class="text-center py-12 text-gray-400">
                <p class="text-3xl mb-2">🏷️</p>
                <p>Wala pang categories.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection