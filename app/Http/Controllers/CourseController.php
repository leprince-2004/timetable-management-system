<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['department', 'lecturer'])->get();
        return response()->json($courses);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_code' => 'required|string|unique:courses',
            'course_name' => 'required|string|max:100',
            'duration_hours' => 'required|integer',
            'department_id' => 'required|exists:departments,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'semester' => 'required|string',
        ]);
        $course = Course::create($request->all());
        return response()->json($course, 201);
    }

    public function show($id)
    {
        $course = Course::with(['department', 'lecturer'])->findOrFail($id);
        return response()->json($course);
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $course->update($request->all());
        return response()->json($course);
    }

    public function destroy($id)
    {
        Course::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}