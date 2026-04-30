<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    public function index()
    {
        $lecturers = Lecturer::with('department')->get();
        return response()->json($lecturers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:lecturers',
            'max_hours_per_week' => 'required|integer',
            'department_id' => 'required|exists:departments,id',
        ]);
        $lecturer = Lecturer::create($request->all());
        return response()->json($lecturer, 201);
    }

    public function show($id)
    {
        $lecturer = Lecturer::with('department')->findOrFail($id);
        return response()->json($lecturer);
    }

    public function update(Request $request, $id)
    {
        $lecturer = Lecturer::findOrFail($id);
        $lecturer->update($request->all());
        return response()->json($lecturer);
    }

    public function destroy($id)
    {
        Lecturer::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}