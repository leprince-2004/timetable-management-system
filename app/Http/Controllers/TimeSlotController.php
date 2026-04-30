<?php

namespace App\Http\Controllers;

use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
    public function index()
    {
        $timeSlots = TimeSlot::all();
        return response()->json($timeSlots);
    }

    public function store(Request $request)
    {
        $request->validate([
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time' => 'required',
            'end_time' => 'required',
            'is_active' => 'boolean',
        ]);
        $timeSlot = TimeSlot::create($request->all());
        return response()->json($timeSlot, 201);
    }

    public function show($id)
    {
        $timeSlot = TimeSlot::findOrFail($id);
        return response()->json($timeSlot);
    }

    public function update(Request $request, $id)
    {
        $timeSlot = TimeSlot::findOrFail($id);
        $timeSlot->update($request->all());
        return response()->json($timeSlot);
    }

    public function destroy($id)
    {
        TimeSlot::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}