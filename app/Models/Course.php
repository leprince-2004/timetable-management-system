<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'course_code', 'course_name', 'duration_hours',
        'department_id', 'lecturer_id', 'semester'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function studentGroups()
    {
        return $this->belongsToMany(StudentGroup::class, 'course_group', 'course_id', 'group_id');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}