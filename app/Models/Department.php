<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name'];

    public function lecturers()
    {
        return $this->hasMany(Lecturer::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function studentGroups()
    {
        return $this->hasMany(StudentGroup::class);
    }
}