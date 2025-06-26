<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\PointLog;

class Student extends Model
{
    protected $fillable = [
        'nim',
        'name',
        'resident_id',
        'total_point',
    ];


    public function pointLogs(): HasMany
    {
        return $this->hasMany(PointLog::class, 'student_id');
    }


    // Relasi ke Resident (asumsinya satu student punya satu akun resident)
    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }
  }
