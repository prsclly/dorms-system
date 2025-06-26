<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Resident extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'room_number',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class,'resident_id');
    }

}
