<?php

namespace App\Http\Controllers\feedback;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeedbackMenu;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportController extends Controller
{
    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
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
            $sheet->setCellValue("C$row", $feedback->category);
            $sheet->setCellValue("D$row", $feedback->description);
            $row++;
        }

        // Export
        $writer = new Xlsx($spreadsheet);
        $filename = 'feedback_export.xlsx';

        // Header response
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save("php://output");
        exit;
    }
}
