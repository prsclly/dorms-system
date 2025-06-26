<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'technician_id',
        'status',
        'assigned_at',
        'proof_photo',
    ];

    // Relasi ke Report
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    // Relasi ke Technician
    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }
}
