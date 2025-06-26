<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointLog extends Model
{
    protected $fillable = [
        'student_id',
        'date',
        'category',
        'description',
        'point_change',
        'previous_point',
        'new_point',
    ];

    /**
     * Relasi ke student berdasarkan nim
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class); // pakai default: student_id
    }
}
