<?php

namespace App\Http\Controllers;

use App\Models\StudentGroup;
use Illuminate\Http\Request;

class StudentGroupController extends Controller
{
    public function index()
    {
        $groups = StudentGroup::with('department')->get();
        return response()->json($groups);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'size' => 'required|integer',
        ]);
        $group = StudentGroup::create($request->all());
        return response()->json($group, 201);
    }

    public function show($id)
    {
        $group = StudentGroup::with('department')->findOrFail($id);
        return response()->json($group);
    }

    public function update(Request $request, $id)
    {
        $group = StudentGroup::findOrFail($id);
        $group->update($request->all());
        return response()->json($group);
    }

    public function destroy($id)
    {
        StudentGroup::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}