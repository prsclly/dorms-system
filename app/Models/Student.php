<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\PointLog;
use App\Models\Permission;
use App\Models\ParentsUser;

class Student extends Model
{
      public $timestamps = false;

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

    // Relasi ke Permission (izin)
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class, 'student_id');
    }

    // Relasi ke Resident (asumsinya satu student punya satu akun resident)
    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }

    public function parents()
    {
        return $this->belongsToMany(Parents::class, 'parent_student', 'student_id', 'parent_id');
    }
}
