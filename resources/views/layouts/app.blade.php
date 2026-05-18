<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskManager — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <a href="{{ route('tasks.index') }}"
               class="text-xl font-bold text-indigo-600">
                ✅ TaskManager
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}"
                class="text-sm text-gray-600 hover:text-indigo-600">Dashboard</a>
                <a href="{{ route('tasks.index') }}"
                class="text-sm text-gray-600 hover:text-indigo-600">Tasks</a>
                <a href="{{ route('categories.index') }}"
                class="text-sm text-gray-600 hover:text-indigo-600">Categories</a>
                <span class="text-sm text-gray-400">|</span>
                <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-red-500 hover:text-red-700">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="max-w-5xl mx-auto mt-4 px-6">
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="max-w-5xl mx-auto px-6 py-8">
        @yield('content')
    </main>

</body>
</html>