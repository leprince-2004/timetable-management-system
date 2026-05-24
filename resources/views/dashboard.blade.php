@extends('layout')
@section('page-title', 'Dashboard')

@section('content')

{{-- Welcome Banner --}}
<div class="card mb-4 border-0" style="background: linear-gradient(135deg, #4361ee 0%, #7209b7 100%); border-radius: 18px; overflow:hidden; position:relative;">
    <div style="position:absolute;top:-40px;right:-40px;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,0.05)"></div>
    <div style="position:absolute;bottom:-50px;right:100px;width:150px;height:150px;border-radius:50%;background:rgba(255,255,255,0.04)"></div>
    <div class="card-body p-4 d-flex align-items-center justify-content-between" style="position:relative;z-index:1">
        <div>
            <p style="color:rgba(255,255,255,0.65);font-size:0.8rem;margin-bottom:4px;text-transform:uppercase;letter-spacing:1px">
                <i class="fas fa-sun me-2"></i>Good day
            </p>
            <h3 style="color:#fff;font-weight:700;margin-bottom:6px;font-size:1.5rem">Administrator</h3>
            <p style="color:rgba(255,255,255,0.6);font-size:0.85rem;margin:0">
                Here's what's happening with your timetables today.
            </p>
        </div>
        <div style="font-size:4rem;opacity:0.12">
            <i class="fas fa-calendar-alt"></i>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card" style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
            <div class="stat-number" id="dept-count">—</div>
            <div class="stat-label">Departments</div>
            <i class="fas fa-building stat-icon"></i>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card" style="background: linear-gradient(135deg, #2dc653, #1a7a35);">
            <div class="stat-number" id="lecturer-count">—</div>
            <div class="stat-label">Lecturers</div>
            <i class="fas fa-chalkboard-teacher stat-icon"></i>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card" style="background: linear-gradient(135deg, #f8961e, #c97a08);">
            <div class="stat-number" id="course-count">—</div>
            <div class="stat-label">Courses</div>
            <i class="fas fa-book stat-icon"></i>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card" style="background: linear-gradient(135deg, #ef233c, #b01020);">
            <div class="stat-number" id="room-count">—</div>
            <div class="stat-label">Rooms</div>
            <i class="fas fa-door-open stat-icon"></i>
        </div>
    </div>
</div>

{{-- Recent Timetable --}}
<div class="card border-0">
    <div class="card-header border-0 d-flex align-items-center justify-content-between"
         style="background: linear-gradient(90deg,#4361ee,#7209b7); border-radius: 16px 16px 0 0; padding: 16px 22px;">
        <h6 class="mb-0 text-white fw-600" style="font-weight:600">
            <i class="fas fa-table me-2 opacity-75"></i>Recent Timetable Entries
        </h6>
        <a href="/timetables" class="btn btn-sm" style="background:rgba(255,255,255,0.15);color:#fff;border-radius:8px;font-size:0.75rem;padding:5px 12px">
            View all <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="timetable-table">
                <thead style="background:#f8faff">
                    <tr>
                        <th class="px-4 py-3">Course</th>
                        <th class="py-3">Lecturer</th>
                        <th class="py-3">Room</th>
                        <th class="py-3">Day & Time</th>
                        <th class="py-3">Group</th>
                    </tr>
                </thead>
                <tbody id="timetable-body">
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <div class="spinner-border spinner-border-sm me-2" style="color:#4361ee"></div>
                            Loading entries…
                        </td>
                    </tr>
                </tbody>
            </table>
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

        document.getElementById('dept-count').textContent     = depts.data.length;
        document.getElementById('lecturer-count').textContent = lecturers.data.length;
        document.getElementById('course-count').textContent   = courses.data.length;
        document.getElementById('room-count').textContent     = rooms.data.length;

        const tbody = document.getElementById('timetable-body');
        if (timetables.data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="empty-state-icon"><i class="fas fa-calendar-times"></i></div>
                        <p class="text-muted mb-0 mt-2" style="font-size:0.85rem">No timetable entries yet</p>
                    </td>
                </tr>`;
        } else {
            const days = { Monday:'primary', Tuesday:'success', Wednesday:'warning', Thursday:'danger', Friday:'info', Saturday:'secondary' };
            tbody.innerHTML = timetables.data.map(t => `
                <tr>
                    <td class="px-4">
                        <span class="badge me-1" style="background:#4361ee">${t.course?.course_code ?? '-'}</span>
                        <span style="font-size:0.85rem">${t.course?.course_name ?? '-'}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:28px;height:28px;border-radius:8px;background:#f0f2ff;display:flex;align-items:center;justify-content:center;font-size:0.7rem;color:#4361ee;font-weight:700">
                                ${(t.lecturer?.name ?? 'N').charAt(0)}
                            </div>
                            <span style="font-size:0.85rem">${t.lecturer?.name ?? '-'}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge" style="background:#f0f2ff;color:#4361ee">
                            <i class="fas fa-door-open me-1"></i>${t.room?.room_number ?? '-'}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-${days[t.time_slot?.day] ?? 'secondary'} me-1">${t.time_slot?.day ?? '-'}</span>
                        <small class="text-muted">${t.time_slot?.start_time?.substring(0,5) ?? ''}</small>
                    </td>
                    <td><span style="font-size:0.85rem">${t.student_group?.name ?? '-'}</span></td>
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
