<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PermissionApprovalController extends Controller
{
    /**
     * Display the list of permissions with optional search.
     */
    public function index(Request $request)
    {
        $query = Permission::with(['student', 'parent']);

        // Apply search filter by student name or NIM
        if ($request->has('search') && $request->search !== '') {
            $search = $request->input('search');
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $permissions = $query->latest()->get();

        return view('content.admin.index', compact('permissions'));
    }

    /**
     * Show detailed view of a leave permission.
     */
    public function show(Permission $permission)
    {
        return view('content.admin.show', compact('permission'));
    }

    /**
     * Approve a leave request.
     */
    public function approve(Permission $permission)
    {
        $permission->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'rejection_reason' => null
        ]);

        return redirect()->back()->with('success', 'Leave request approved.');
    }

    /**
     * Reject a leave request with reason.
     */
    public function reject(Request $request, Permission $permission)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $permission->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'rejection_reason' => $request->input('rejection_reason')
        ]);

        return redirect()->back()->with('success', 'Leave request rejected.');
    }

    /**
     * Download the permission letter as a formatted PDF.
     */
    public function downloadPDF(Permission $permission)
    {
        $pdf = Pdf::loadView('content.admin.pdf', compact('permission'));

        $studentName = str_replace(' ', '_', strtolower($permission->student->name));
        $filename = 'leave_permission_' . $studentName . '_' . $permission->student->nim . '.pdf';

        return $pdf->download($filename);
    }
}
