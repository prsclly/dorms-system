<?php

namespace App\Models;
use App\Models\Pic;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $table = 'meals';

    protected $fillable = [
        'date','meal_type','time','menu_description','pic_id'
    ];

    protected $casts = [
      'time' => 'string',
  ];


    public function pic()
    {
        return $this->belongsTo(Pic::class);
    }
}