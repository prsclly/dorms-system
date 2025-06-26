<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['resident_id', 'category', 'description', 'photo', 'submitted_at'];
    protected $casts = ['submitted_at' => 'datetime',];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function task() 
    {
        return $this->hasOne(Task::class);
    }
    public function feedback()
    {
        return $this->hasOne(FeedbackReport::class);
    }

}
