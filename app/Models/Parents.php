<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Parents extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password'];

    /**
     * Relasi ke banyak siswa (many-to-many)
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'parent_student', 'parent_id', 'student_id');
    }

    /**
     * Relasi ke tabel izin (one-to-many)
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'parent_id');
    }
}
