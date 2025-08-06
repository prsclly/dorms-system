<?php

namespace App\Http\Controllers\point_tracker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\PointLog;

class add_log extends Controller
{
    public function create($id)
    {
        $student = Student::findOrFail($id);
        return view('content.point_tracker.add_log', compact('student'));
    }

    public function store(Request $request, $id)
    {
        $validated = $this->validateInput($request);

        $student = Student::findOrFail($id);
        $previous_point = $student->total_point;
        $point_change = $validated['point_change'];
        $new_point = $previous_point + $point_change;

        PointLog::create([
            'student_id'     => $student->id,
            'date'           => $validated['date'],
            'category'       => $validated['category'],
            'description'    => $validated['description'],
            'point_change'   => $point_change,
            'previous_point' => $previous_point,
            'new_point'      => $new_point,
        ]);

        $student->update(['total_point' => $new_point]);

        return redirect()->route('edit_student_point', $student->id)->with('success', 'Point log added.');
    }

    public function edit($student_id, $log_id)
    {
        $student = Student::findOrFail($student_id);
        $log = PointLog::findOrFail($log_id);
        return view('content.point_tracker.edit_log', compact('student', 'log'));
    }

    public function update(Request $request, $student_id, $log_id)
    {
        $validated = $this->validateInput($request);

        $log = PointLog::findOrFail($log_id);

        // Validasi waktu maksimal 1 jam (60 menit)
        if (now()->diffInMinutes($log->created_at) > 60) {
            return redirect()->back()->with('error', 'Log point hanya bisa diedit dalam waktu 1 jam setelah dibuat.');
        }

        $student = Student::findOrFail($student_id);

        // Hitung total poin baru berdasarkan perubahan
        $difference = $validated['point_change'] - $log->point_change;
        $new_total = $student->total_point + $difference;

        $log->update([
            'date' => $validated['date'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'point_change' => $validated['point_change'],
            'new_point' => $log->previous_point + $validated['point_change'],
        ]);

        $student->update(['total_point' => $new_total]);

        return redirect()->route('edit_student_point', $student_id)->with('success', 'Point log updated.');
    }

    public function destroy($student_id, $log_id)
    {
        $log = PointLog::findOrFail($log_id);

        // Validasi waktu maksimal 1 jam (60 menit)
        if (now()->diffInMinutes($log->created_at) > 60) {
            return redirect()->back()->with('error', 'Log point hanya bisa dihapus dalam waktu 1 jam setelah dibuat.');
        }

        $student = Student::findOrFail($student_id);
        $student->total_point -= $log->point_change;
        $student->save();

        $log->delete();

        return redirect()->route('edit_student_point', $student_id)->with('success', 'Point log deleted.');
    }

    private function validateInput(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category' => 'required|in:Appreciation,Violation',
            'description' => 'required|string',
            'point_change' => 'required|integer',
        ]);

        if ($validated['category'] === 'Appreciation' && $validated['point_change'] <= 0) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'point_change' => 'Appreciation harus berupa poin positif.',
            ]);
        }

        if ($validated['category'] === 'Violation' && $validated['point_change'] >= 0) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'point_change' => 'Violation harus berupa poin negatif.',
            ]);
        }

        return $validated;
    }
}
