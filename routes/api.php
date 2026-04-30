<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TimeSlotController;
use App\Http\Controllers\StudentGroupController;
use App\Http\Controllers\TimetableController;

Route::apiResource('departments', DepartmentController::class);
Route::apiResource('lecturers', LecturerController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('rooms', RoomController::class);
Route::apiResource('time-slots', TimeSlotController::class);
Route::apiResource('student-groups', StudentGroupController::class);
Route::apiResource('timetables', TimetableController::class);