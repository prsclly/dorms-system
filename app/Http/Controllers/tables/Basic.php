<?php

namespace App\Http\Controllers\tables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Technician;
use App\Models\Task;

class Basic extends Controller
{
    public function index(Request $request)
{
        $query = Report::with(['resident', 'task']);

        // Filter berdasarkan tanggal (submitted_at)
        if ($request->has('date') && $request->date) {
            $query->whereDate('submitted_at', $request->date);
        }

        // Filter berdasarkan kategori report
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filter berdasarkan status task (Assigned, In Progress, Completed)
        if ($request->has('status') && $request->status) {
            // Jika status filter adalah "Pending", ambil report tanpa task atau task dengan status "Pending"
            if ($request->status == 'Pending') {
                $query->whereDoesntHave('task'); // Ambil laporan yang tidak memiliki task
            } else {
                $query->whereHas('task', function ($q) use ($request) {
                    $q->where('status', $request->status);
                });
            }
        }

        // Ambil hasil akhir setelah filter
        $reports = $query->orderBy('submitted_at', 'desc')->get();

        // Ambil semua kategori unik dari laporan (untuk dropdown filter)
        $categories = Report::select('category')->distinct()->pluck('category');

        // Ambil semua teknisi dan spesialisasinya
        $technicians = Technician::with('specialization')->get();

        return view('content.tables.tables-basic', compact('reports', 'technicians', 'categories'));
    }


    public function assignTechnician(Request $request, Report $report)
    {
        $request->validate([
            'technician_id' => 'required|exists:technicians,id',
        ]);

        // Cek apakah sudah ada task untuk report ini
        $task = Task::where('report_id', $report->id)->first();

        if (!$task) {
            // Buat task baru jika belum ada
            $task = new Task([
                'report_id' => $report->id,
                'technician_id' => $request->technician_id,
                'status' => 'Assigned',
                'assigned_at' => now(),
            ]);
        } else {
            // Update task yang sudah ada
            $task->technician_id = $request->technician_id;
            $task->status = 'Assigned';
            $task->assigned_at = now();
        }

        $task->save();

        return redirect()->back()->with('success', 'Technician assigned successfully.');
    }
}
