<?php

namespace App\Http\Controllers\resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Report;
use App\Models\FeedbackReport;

class ResidentReportController extends Controller
{
    // Menampilkan halaman riwayat report
    public function index()
    {
        $residentId = Auth::guard('resident')->id();
        if (!$residentId) {
            abort(403, 'Unauthorized');
        }

        $reports = Report::where('resident_id', $residentId)
        ->orderBy('created_at', 'desc')
        ->paginate(7); // 7 report per page

        return view('content.resident.resident-report', compact('reports'));
    }

    // Menampilkan detail report (via AJAX)
    public function detail($id)
    {
        $report = Report::with(['task', 'feedback'])->findOrFail($id);

        // Cegah akses report milik orang lain
        if ($report->resident_id !== Auth::guard('resident')->id()) {
            abort(403, 'Access denied');
        }

        return response()->json([
            'id' => $report->id,
            'title' => $report->category . ' Report',
            'description' => $report->description,
            'status' => $report->task->status ?? 'Pending', 
            'image' => $report->photo,
            'created_at' => $report->created_at->format('d M Y H:i'),
            'has_feedback' => $report->feedback !== null,
            'feedback_comment' => $report->feedback->comment ?? null,
        ]);
    }

    // Menyimpan report baru dari modal
    public function store(Request $request)
    {
        $request->validate([
            'category'    => 'required|in:Electrical,Plumbing,Air Conditioning,Furniture,Cleaning',
            'description' => 'required|string',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $residentId = Auth::guard('resident')->id();
        if (!$residentId) {
            abort(403, 'Unauthorized');
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('report_photos', 'public');
        }

        Report::create([
            'resident_id'  => $residentId,
            'category'     => $request->category,
            'description'  => $request->description,
            'photo'        => $photoPath,
            'submitted_at' => now(),
        ]);

        return redirect()->route('resident.report.history')->with('success', 'Report submitted successfully!');
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);

        if ($report->resident_id !== Auth::guard('resident')->id()) {
            abort(403, 'Unauthorized');
        }

        if ($report->task && $report->task->status !== 'Pending') {
            return redirect()->back()->with('error', 'Cannot edit assigned report.');
        }

        // Hapus file foto jika ada
        if ($report->photo && Storage::disk('public')->exists($report->photo)) {
            Storage::disk('public')->delete($report->photo);
        }

        $report->delete();

        return redirect()->back()->with('success', 'Report deleted successfully.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|in:Electrical,Plumbing,Air Conditioning,Furniture,Cleaning',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $report = Report::findOrFail($id);

        if ($report->resident_id !== Auth::guard('resident')->id()) {
            abort(403, 'Unauthorized');
        }

        if ($report->task && $report->task->status !== 'Pending') {
            return redirect()->back()->with('error', 'Cannot edit assigned report.');
        }

        $report->category = $request->category;
        $report->description = $request->description;

        if ($request->hasFile('photo')) {
            if ($report->photo && Storage::disk('public')->exists($report->photo)) {
                Storage::disk('public')->delete($report->photo);
            }

            $path = $request->file('photo')->store('report_photos', 'public');
            $report->photo = $path;
        }

        $report->save();

        return redirect()->back()->with('success', 'Report updated successfully.');
    }

    public function submitFeedback(Request $request)
    {
        $request->validate([
            'report_id' => 'required|exists:reports,id',
            'comment'   => 'nullable|string|max:1000',
        ]);

        $report = Report::with('task')->findOrFail($request->report_id);

        if ($report->resident_id !== Auth::guard('resident')->id()) {
            abort(403, 'Unauthorized');
        }

        if (!$report->task || $report->task->status !== 'Completed') {
            return back()->with('error', 'You can only give feedback for completed reports.');
        }

        // Cek apakah sudah pernah beri feedback
        if ($report->feedback) {
            return back()->with('error', 'Feedback already submitted.');
        }

        FeedbackReport::create([
            'report_id' => $report->id,
            'resident_id' => Auth::guard('resident')->id(),
            'comment' => $request->comment,
            'submitted_at' => now(),
        ]);

        return redirect()->route('resident.report.history')->with('success', 'Feedback submitted successfully!');
    }

}
