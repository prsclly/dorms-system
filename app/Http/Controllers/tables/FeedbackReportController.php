<?php

namespace App\Http\Controllers\tables;

use App\Http\Controllers\Controller;
use App\Models\FeedbackReport;

class FeedbackReportController extends Controller
{
    public function index()
    {
        $feedbacks = FeedbackReport::with(['report', 'resident'])
            ->orderBy('submitted_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        if ($feedbacks->isEmpty()) {
            session()->flash('info', 'No feedback data found.');
        }

        return view('content.tables.tables-feedback-reports', compact('feedbacks'));
    }
}
