<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total' => $user->tasks()->count(),
            'pending' => $user->tasks()->where('status', 'pending')->count(),
            'in_progress' => $user->tasks()->where('status', 'in_progress')->count(),
            'completed' => $user->tasks()->where('status', 'completed')->count(),
            'overdue' => $user->tasks()->where('status', '!=', 'completed')->whereDate('due_date', '<', today())->count(),
        ];

        $recentTasks = $user->tasks()->with('category')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentTasks'));
    }
}
