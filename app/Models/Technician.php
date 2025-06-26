<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Technician extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'specialization_id',
    ];

    // Relasi ke spesialisasi
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    // Relasi ke tugas-tugas yang ditugaskan ke teknisi ini
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
