<?php

namespace App\Http\Controllers\parents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

class ParentPermissionController extends Controller
{
    /**
     * Tampilkan form pengajuan izin untuk orang tua.
     */
    public function create()
    {
        $parent = Auth::guard('parent')->user();
        $students = $parent->students;

        return view('content.parents.create_permission', compact('students'));
    }

    /**
     * Simpan pengajuan izin ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'   => 'required|exists:students,id',
            'type'         => 'required|in:pesiar,ib',
            'reason'       => 'required|string',
            'start_date'   => 'required|date|after_or_equal:today',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'attachment'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validated['type'] === 'pesiar') {
            $validated['end_date'] = $validated['start_date'];
        }

        $permission = new Permission();
        $permission->parent_id = Auth::guard('parent')->id();
        $permission->student_id = $validated['student_id'];
        $permission->type = $validated['type'];
        $permission->reason = $validated['reason'];
        $permission->start_date = $validated['start_date'];
        $permission->end_date = $validated['end_date'];
        $permission->status = 'pending';

        if ($request->hasFile('attachment')) {
            $permission->attachment = $request->file('attachment')->store('attachments');
        }

        $permission->save();

        return redirect()->route('parent.permissions.create')
                         ->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Tampilkan riwayat izin yang diajukan oleh orang tua (dengan search).
     */
    public function history(Request $request)
    {
        $parent = Auth::guard('parent')->user();

        // Ambil semua permission anak-anak dari parent
        $permissions = $parent->students()->with(['permissions' => function ($query) {
            $query->latest();
        }])->get()->pluck('permissions')->flatten();

        // Jika ada search
        if ($request->has('search')) {
            $search = strtolower($request->search);
            $permissions = $permissions->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->student->name), $search)
                    || str_contains(strtolower($item->student->nim), $search);
            });
        }

        return view('content.parents.permission_history', [
            'permissions' => $permissions
        ]);
    }
}
