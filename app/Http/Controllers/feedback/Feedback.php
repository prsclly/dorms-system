<?php

namespace App\Http\Controllers\feedback;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeedbackMenu;
use App\Models\Pic;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Feedback extends Controller
{
    public function index(Request $request)
    {
        $query = FeedbackMenu::with(['meal.pic', 'resident.student']);

        // Filter berdasarkan tanggal
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Filter berdasarkan meal time
        if ($request->filled('meal_time')) {
            $query->whereHas('meal', function ($q) use ($request) {
                $q->where('meal_type', $request->meal_time);
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter berdasarkan PIC
        if ($request->filled('pic_id')) {
            $query->whereHas('meal.pic', function ($q) use ($request) {
                $q->where('id', $request->pic_id);
            });
        }

        // Kalau request-nya untuk export
        if ($request->has('export')) {
            return $this->exportToExcel(clone $query);
        }

        // Ambil data dengan pagination
        $feedbacks = $query->orderBy('date', 'desc')
                           ->orderBy('created_at', 'desc')
                           ->paginate(10)
                           ->withQueryString();

        // Ambil semua PIC
        $allPics = Pic::orderBy('name')->get();

        return view('content.feedback.feedback_list', compact('feedbacks', 'allPics'));
    }

    private function exportToExcel($query)
    {
        $feedbacks = $query->orderBy('date', 'desc')->orderBy('created_at', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->fromArray([
            'Date', 'student ID', 'Student Name', 'Meal Time', 'Menu Description', 'Vendor', 'Category', 'Description', 'Submitted At'
        ], null, 'A1');

        $row = 2;
        foreach ($feedbacks as $feedback) {
            $sheet->setCellValue('A' . $row, \Carbon\Carbon::parse($feedback->date)->format('j M Y'));
            $sheet->setCellValue('B' . $row, $feedback->resident?->student?->nim ?? '-');
            $sheet->setCellValue('C' . $row, $feedback->resident?->name ?? '-');
            $sheet->setCellValue('D' . $row, $feedback->meal?->meal_type ?? '-');
            $sheet->setCellValue('E' . $row, $feedback->meal?->menu_description ?? '-');
            $sheet->setCellValue('F' . $row, $feedback->meal?->pic?->name ?? '-');
            $sheet->setCellValue('G' . $row, $feedback->category ?? '-');
            $sheet->setCellValue('H' . $row, $feedback->message ?? '-');
            $sheet->setCellValue('I' . $row, \Carbon\Carbon::parse($feedback->created_at)->format('H:i') . ' WIB');
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        // Gunakan nama file dinamis berdasarkan tanggal
        $fileName = 'Feedback_Report_' . now()->format('Ymd_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'feedback_');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
