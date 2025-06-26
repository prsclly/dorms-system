<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pic extends Model
{
    protected $table = 'pics';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
    ];


    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
