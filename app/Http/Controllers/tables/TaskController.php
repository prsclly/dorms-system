<?php

namespace App\Http\Controllers\tables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // Ambil technician yang sedang login
        $technicianId = auth()->id(); 

        // Query task yang berkaitan dengan technician ini, dengan relasi report dan resident
        $query = Task::with(['report.resident'])
            ->where('technician_id', $technicianId);

        // Filter status jika ada
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal assigned_at
        if ($request->has('date') && $request->date) {
            $query->whereDate('assigned_at', $request->date);
        }

        $tasks = $query->orderBy('updated_at', 'desc')->paginate(10)->withQueryString();

        // Status untuk dropdown filter
        $statuses = ['Assigned', 'In Progress', 'Completed'];

        return view('content.tables.task', compact('tasks', 'statuses'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        // Validasi dan cek teknisi yang login
        if ($task->technician_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:In Progress,Completed',
        ]);

        $task->status = $request->status;
        $task->updated_at = now();
        $task->save();

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

}
