<?php

namespace App\Http\Controllers\parents;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardParentController extends Controller
{
    public function index()
    {
        // Ambil parent yang sedang login
        $parent = Auth::guard('parent')->user();

        // Ambil semua siswa dari parent dengan histori poinnya
        $students = $parent->students()->with('pointLogs')->get();

        // Kirim data ke view dashboard
        return view('content.parents.dashboard_parents', compact('parent', 'students'));
    }

    public function pointLogs()
    {
    $parent = Auth::guard('parent')->user();
    $students = $parent->students()->with(['pointLogs' => function ($query) {
        $query->latest();
    }])->get();

    return view('content.parents.point_logs', compact('parent', 'students'));
    }

}
