<?php

namespace App\Http\Controllers\point_tracker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\PointLog;

class add_log extends Controller
{
    /**
     * Tampilkan form tambah log poin.
     */
    public function create($id)
    {
        $student = Student::findOrFail($id);
        return view('content.point_tracker.add_log', compact('student'));
    }

    /**
     * Simpan log poin baru ke database.
     */
    public function store(Request $request, $id)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'date' => 'required|date',
            'category' => 'required|in:Appreciation,Violation',
            'description' => 'required|string',
            'point_change' => 'required|integer',
        ]);

        // Ambil data student berdasarkan ID
        $student = Student::findOrFail($id);

        // Hitung poin sebelum dan sesudah
        $previous_point = $student->total_point;
        $point_change = $validated['point_change'];
        $new_point = $previous_point + $point_change;

        // Simpan ke tabel point_logs
        PointLog::create([
            'student_id' => $student->id, // ✔ gunakan student_id sesuai struktur tabel
            'date' => $validated['date'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'point_change' => $point_change,
            'previous_point' => $previous_point,
            'new_point' => $new_point,
        ]);

        // Update total poin di tabel students
        $student->update(['total_point' => $new_point]);

        // Redirect kembali ke halaman edit
        return redirect()->route('edit_student_point', ['id' => $id])
            ->with('success', 'Point log berhasil ditambahkan.');
    }
}
