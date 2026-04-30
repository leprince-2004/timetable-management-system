<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = [
        'day', 'start_time', 'end_time', 'is_active'
    ];

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}