@extends('layout')

@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body d-flex align-items-center">
                <i class="fas fa-building fa-2x me-3"></i>
                <div>
                    <h5 class="card-title mb-0" id="dept-count">0</h5>
                    <small>Departments</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body d-flex align-items-center">
                <i class="fas fa-chalkboard-teacher fa-2x me-3"></i>
                <div>
                    <h5 class="card-title mb-0" id="lecturer-count">0</h5>
                    <small>Lecturers</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body d-flex align-items-center">
                <i class="fas fa-book fa-2x me-3"></i>
                <div>
                    <h5 class="card-title mb-0" id="course-count">0</h5>
                    <small>Courses</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body d-flex align-items-center">
                <i class="fas fa-door-open fa-2x me-3"></i>
                <div>
                    <h5 class="card-title mb-0" id="room-count">0</h5>
                    <small>Rooms</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-table me-2"></i>Recent Timetable Entries
            </div>
            <div class="card-body">
                <table class="table table-striped" id="timetable-table">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Lecturer</th>
                            <th>Room</th>
                            <th>Day & Time</th>
                            <th>Group</th>
                        </tr>
                    </thead>
                    <tbody id="timetable-body">
                        <tr><td colspan="5" class="text-center">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
async function loadDashboard() {
    try {
        const [depts, lecturers, courses, rooms, timetables] = await Promise.all([
            axios.get('/api/departments'),
            axios.get('/api/lecturers'),
            axios.get('/api/courses'),
            axios.get('/api/rooms'),
            axios.get('/api/timetables')
        ]);

        document.getElementById('dept-count').textContent = depts.data.length;
        document.getElementById('lecturer-count').textContent = lecturers.data.length;
        document.getElementById('course-count').textContent = courses.data.length;
        document.getElementById('room-count').textContent = rooms.data.length;

        const tbody = document.getElementById('timetable-body');
        if (timetables.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No timetable entries yet</td></tr>';
        } else {
            tbody.innerHTML = timetables.data.map(t => `
                <tr>
                    <td>${t.course?.course_name ?? '-'}</td>
                    <td>${t.lecturer?.name ?? '-'}</td>
                    <td>${t.room?.room_number ?? '-'}</td>
                    <td>${t.time_slot?.day ?? '-'} ${t.time_slot?.start_time ?? ''}</td>
                    <td>${t.student_group?.name ?? '-'}</td>
                </tr>
            `).join('');
        }
    } catch (e) {
        console.error(e);
    }
}

loadDashboard();
</script>
@endsection