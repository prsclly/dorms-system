<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use Barryvdh\DomPDF\Facade\Pdf; // ✅ Tambahkan ini!

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::with('student');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $permissions = $query->latest()->get();

        return view('admin.permissions.index', compact('permissions'));
    }

    public function show($id)
    {
        $permission = Permission::with('student')->findOrFail($id);
        return view('admin.permissions.show', compact('permission'));
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
        $pdf = Pdf::loadView('admin.permissions.pdf', compact('permission')); // ✅ pakai Pdf bukan PDF
        return $pdf->download('leave_permission_' . $permission->student->nim . '.pdf');
    }
}
