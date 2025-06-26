<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackMenu extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'date',
        'category',
        'message',
        'resident_id',
        'meal_id',
    ];

    // Relasi ke resident
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    // Relasi ke meal
    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}
