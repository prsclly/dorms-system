<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'student_id',
        'type',
        'reason',
        'start_date',
        'end_date',
        'attachment',
        'status',
        'rejection_reason',
        'approved_by',
    ];

    // Parent yang mengajukan
    public function parent()
    {
        return $this->belongsTo(Parents::class);
    }

    // Siswa yang terkait
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Admin yang menyetujui
    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }
}
