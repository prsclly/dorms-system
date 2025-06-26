<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Relasi ke teknisi-teknisi dengan spesialisasi ini
    public function technicians()
    {
        return $this->hasMany(Technician::class);
    }
}
