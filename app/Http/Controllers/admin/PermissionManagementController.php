<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use Barryvdh\DomPDF\Facade\Pdf;

class PermissionManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::with(['student', 'parent']);

        // ✅ Search by student name or NIM
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        // ✅ Filter by status (case-insensitive)
        if ($request->filled('status')) {
            $status = strtolower($request->status);
            $query->whereRaw('LOWER(status) = ?', [$status]);
        }

        // ✅ Filter by type (case-insensitive)
        if ($request->filled('type')) {
            $type = strtolower($request->type);
            $query->whereRaw('LOWER(type) = ?', [$type]);
        }

        // ✅ Sorting & Pagination (10 per page)
        $permissions = $query->latest()->paginate(10)->appends($request->query());

        // ✅ View path diperbaiki
        return view('content.permissions.index', compact('permissions'));
    }

    public function show($id)
    {
        $permission = Permission::with('student')->findOrFail($id);
        return view('content.permissions.show', compact('permissions'));
    }

    public function approve($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->status = 'approved';
        $permission->save();

        return redirect()->back()->with('success', 'Permission approved.');
    }

    public function reject(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->status = 'rejected';
        $permission->rejection_reason = $request->input('rejection_reason');
        $permission->save();

        return redirect()->back()->with('success', 'Permission rejected.');
    }

    public function download($id)
    {
        $permission = Permission::with('student')->findOrFail($id);
        $pdf = Pdf::loadView('content.permissions.pdf', compact('permission'));

        return $pdf->download('leave_permission_' . $permission->student->nim . '.pdf');
    }
}
