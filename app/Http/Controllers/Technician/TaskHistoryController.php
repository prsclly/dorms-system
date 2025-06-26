<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class TaskHistoryController extends Controller
{
    public function index()
    {
        $technician = Auth::guard('technician')->user();

        $tasks = Task::with('report')
                    ->where('technician_id', $technician->id)
                    ->where('status', 'Completed')
                    ->orderByDesc('assigned_at')
                    ->paginate(5);

        return view('content.technician.task-history', compact('tasks'));
    }
}
