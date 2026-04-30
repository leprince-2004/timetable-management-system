<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function index()
    {
        $timetables = Timetable::with([
            'course', 'lecturer', 'room', 'timeSlot', 'studentGroup'
        ])->get();
        return response()->json($timetables);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'room_id' => 'required|exists:rooms,id',
            'time_slot_id' => 'required|exists:time_slots,id',
            'group_id' => 'required|exists:student_groups,id',
        ]);

        // Vérification des conflits
        $lecturerClash = Timetable::where('lecturer_id', $request->lecturer_id)
            ->where('time_slot_id', $request->time_slot_id)->exists();
        if ($lecturerClash) {
            return response()->json(['error' => 'Lecturer clash detected!'], 422);
        }

        $roomClash = Timetable::where('room_id', $request->room_id)
            ->where('time_slot_id', $request->time_slot_id)->exists();
        if ($roomClash) {
            return response()->json(['error' => 'Room clash detected!'], 422);
        }

        $groupClash = Timetable::where('group_id', $request->group_id)
            ->where('time_slot_id', $request->time_slot_id)->exists();
        if ($groupClash) {
            return response()->json(['error' => 'Student group clash detected!'], 422);
        }

        $timetable = Timetable::create($request->all());
        return response()->json($timetable, 201);
    }

    public function show($id)
    {
        $timetable = Timetable::with([
            'course', 'lecturer', 'room', 'timeSlot', 'studentGroup'
        ])->findOrFail($id);
        return response()->json($timetable);
    }

    public function update(Request $request, $id)
    {
        $timetable = Timetable::findOrFail($id);
        $timetable->update($request->all());
        return response()->json($timetable);
    }

    public function destroy($id)
    {
        Timetable::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}