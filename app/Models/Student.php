<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'father_name',
        'mobile_no',
        'class',
        'section',
        'roll_no',
        'registration_no',
        'blood_group',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
