<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentGroup extends Model
{
    protected $fillable = ['name', 'department_id', 'size'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_group', 'group_id', 'course_id');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'group_id');
    }
}