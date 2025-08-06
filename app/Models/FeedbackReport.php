<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackReport extends Model
{
    use HasFactory;

    protected $table = 'feedback_reports';

    protected $fillable = [
        'report_id',
        'resident_id',
        'comment',
        'submitted_at',
    ];

    protected $casts = [
    'submitted_at' => 'datetime',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
