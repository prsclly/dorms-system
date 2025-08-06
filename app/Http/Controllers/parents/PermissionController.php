<?php

namespace App\Http\Controllers\parents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function create()
    {
        $parent = Auth::guard('parent')->user();
        $students = $parent->students;

        return view('content.parents.create_permission', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'   => 'required|exists:students,id',
            'type'         => 'required|in:pesiar,ib',
            'reason'       => 'required|string',
            'start_date'   => 'required|date|after_or_equal:today',
            'end_date'     => 'required|date|after_or_equal:start_date',
        ]);

        if ($validated['type'] === 'pesiar' && $validated['start_date'] !== $validated['end_date']) {
            return back()->withErrors(['end_date' => 'For Day Leave, return date must match start date.'])->withInput();
        }

        if ($validated['type'] === 'ib' && $validated['start_date'] === $validated['end_date']) {
            return back()->withErrors(['end_date' => 'For Overnight Leave, return date must be after start date.'])->withInput();
        }

        $permission = new Permission();
        $permission->parent_id = Auth::guard('parent')->id();
        $permission->student_id = $validated['student_id'];
        $permission->type = $validated['type'];
        $permission->reason = $validated['reason'];
        $permission->start_date = $validated['start_date'];
        $permission->end_date = $validated['end_date'];
        $permission->status = 'pending';
        $permission->save();

        return redirect()->route('parent.permissions.create')
                         ->with('success', 'Leave request submitted successfully.');
    }

    public function history(Request $request)
{
    $parent = Auth::guard('parent')->user();

    $search = $request->search;

    $permissions = Permission::with('student')
        ->whereHas('student', function ($query) use ($parent) {
            $query->where('parent_id', $parent->id);
        })
        ->when($search, function ($query, $search) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('nim', 'like', "%$search%");
            });
        })
        ->latest()
        ->get();

    return view('content.parents.permission_history', [
        'permissions' => $permissions
    ]);
}

    public function edit($id)
    {
        $permission = Permission::with('student')->findOrFail($id);
        $parent = Auth::guard('parent')->user();

        if (!$parent->students->contains($permission->student_id)) {
            abort(403);
        }

        if ($permission->status !== 'pending') {
            return back()->with('error', 'Cannot edit request once it is being processed or decided.');
        }

        return view('content.parents.edit_permission', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $parent = Auth::guard('parent')->user();

        if (!$parent->students->contains($permission->student_id)) {
            abort(403);
        }

        if ($permission->status !== 'pending') {
            return back()->with('error', 'Cannot edit request once it is being processed or decided.');
        }

        $validated = $request->validate([
            'reason'       => 'required|string',
            'start_date'   => 'required|date|after_or_equal:today',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'type'         => 'required|in:pesiar,ib',
        ]);

        if ($validated['type'] === 'pesiar' && $validated['start_date'] !== $validated['end_date']) {
            return back()->withErrors(['end_date' => 'For Day Leave, return date must match start date.'])->withInput();
        }

        if ($validated['type'] === 'ib' && $validated['start_date'] === $validated['end_date']) {
            return back()->withErrors(['end_date' => 'For Overnight Leave, return date must be after start date.'])->withInput();
        }

        $permission->update([
            'type'        => $validated['type'],
            'reason'      => $validated['reason'],
            'start_date'  => $validated['start_date'],
            'end_date'    => $validated['end_date'],
        ]);

        return redirect()->route('parent.permissions.history')
                         ->with('success', 'Leave request updated.');
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $parent = Auth::guard('parent')->user();

        if (!$parent->students->contains($permission->student_id)) {
            abort(403);
        }

        if ($permission->status !== 'pending') {
            return back()->with('error', 'Cannot delete request once it is being processed or decided.');
        }

        $permission->delete();

        return back()->with('success', 'Leave request deleted.');
    }
}
