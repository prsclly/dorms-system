<?php

namespace App\Http\Controllers\resident;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Permission;

class PermissionHistoryController extends Controller
{
    public function index()
    {
        $resident = Auth::user();

        // Ambil permission yang berelasi dengan student dari resident yang sedang login
        $permissions = Permission::with('student')
            ->whereHas('student', function ($query) use ($resident) {
                $query->where('resident_id', $resident->id);
            })
            ->latest()
            ->get();

        return view('content.resident.permission_history', compact('permissions'));

    }
}
