<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    if (session('is_admin')) {
        return redirect('/dashboard');
    }
    return view('login');
});

Route::post('/do-login', function (Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');

    if ($email === 'admin@timetable.com' && $password === 'admin123') {
        session(['is_admin' => true]);
        return response()->json(['success' => true]);
    }

    return response()->json(['error' => 'Invalid credentials'], 401);
});

Route::get('/logout', function () {
    session()->flush();
    return redirect('/login');
});

Route::get('/dashboard', function () {
    if (!session('is_admin')) return redirect('/login');
    return view('dashboard');
});

Route::get('/departments', function () {
    if (!session('is_admin')) return redirect('/login');
    return view('departments');
});

Route::get('/lecturers', function () {
    if (!session('is_admin')) return redirect('/login');
    return view('lecturers');
});

Route::get('/courses', function () {
    if (!session('is_admin')) return redirect('/login');
    return view('courses');
});

Route::get('/rooms', function () {
    if (!session('is_admin')) return redirect('/login');
    return view('rooms');
});

Route::get('/time-slots', function () {
    if (!session('is_admin')) return redirect('/login');
    return view('time-slots');
});

Route::get('/student-groups', function () {
    if (!session('is_admin')) return redirect('/login');
    return view('student-groups');
});

Route::get('/timetables', function () {
    if (!session('is_admin')) return redirect('/login');
    return view('timetables');
});