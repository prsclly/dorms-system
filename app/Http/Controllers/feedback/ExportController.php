<?php

namespace App\Http\Controllers\feedback;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeedbackMenu;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'Resident');
        $sheet->setCellValue('B1', 'Meal Time');
        $sheet->setCellValue('C1', 'Category');
        $sheet->setCellValue('D1', 'Feedback');

        // Ambil data
        $feedbacks = FeedbackMenu::with('resident', 'meal')->get();
        $row = 2;
        foreach ($feedbacks as $feedback) {
            $sheet->setCellValue("A$row", $feedback->resident->name ?? '-');
            $sheet->setCellValue("B$row", $feedback->meal->meal_type ?? '-');
            $sheet->setCellValue("C$row", $feedback->category ?? '-');
            $sheet->setCellValue("D$row", $feedback->description ?? '-');
            $row++;
        }

        // Format nama file dengan tanggal
        $filename = 'Feedback_Menu_' . now()->format('dmY') . '.xlsx';
        $filePath = storage_path("app/public/{$filename}");

        // Simpan sementara
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        // Kirim response download dan hapus setelah dikirim
        return response()->download($filePath, $filename)->deleteFileAfterSend(true);
    }
}
