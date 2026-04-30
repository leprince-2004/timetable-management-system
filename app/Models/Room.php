<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_number', 'capacity', 'building',
        'has_projector', 'is_lab'
    ];

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}