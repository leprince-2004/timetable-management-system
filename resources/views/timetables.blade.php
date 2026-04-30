@extends('layout')

@section('page-title', 'Timetables')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-table me-2" style="color:#2c3e50"></i>Timetables</h4>
        <p class="text-muted mb-0">Manage and view academic timetables</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" onclick="exportPDF()">
            <i class="fas fa-file-pdf me-2"></i>Export PDF
        </button>
        <button class="btn text-white px-4" style="background:#2c3e50" onclick="openAddModal()">
            <i class="fas fa-plus me-2"></i>Add Entry
        </button>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 text-white" style="background:linear-gradient(135deg,#2c3e50,#3498db)">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="fas fa-table fa-2x opacity-75"></i>
                <div>
                    <div class="fs-4 fw-bold" id="total-count">0</div>
                    <div class="small opacity-75">Total Entries</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-success bg-gradient text-white">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="fas fa-book fa-2x opacity-75"></i>
                <div>
                    <div class="fs-4 fw-bold" id="courses-count">0</div>
                    <div class="small opacity-75">Courses Scheduled</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-warning bg-gradient text-white">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="fas fa-chalkboard-teacher fa-2x opacity-75"></i>
                <div>
                    <div class="fs-4 fw-bold" id="lecturers-count">0</div>
                    <div class="small opacity-75">Lecturers Assigned</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-danger bg-gradient text-white">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="fas fa-door-open fa-2x opacity-75"></i>
                <div>
                    <div class="fs-4 fw-bold" id="rooms-count">0</div>
                    <div class="small opacity-75">Rooms Used</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <select id="filterLecturer" class="form-select" onchange="filterTimetable()">
                    <option value="">All Lecturers</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterRoom" class="form-select" onchange="filterTimetable()">
                    <option value="">All Rooms</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterGroup" class="form-select" onchange="filterTimetable()">
                    <option value="">All Groups</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterDay" class="form-select" onchange="filterTimetable()">
                    <option value="">All Days</option>
                    <option>Monday</option>
                    <option>Tuesday</option>
                    <option>Wednesday</option>
                    <option>Thursday</option>
                    <option>Friday</option>
                    <option>Saturday</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- View Toggle -->
<div class="d-flex gap-2 mb-3">
    <button class="btn btn-sm btn-dark active" id="btn-weekly" onclick="switchView('weekly')">
        <i class="fas fa-calendar-week me-1"></i>Weekly View
    </button>
    <button class="btn btn-sm btn-outline-dark" id="btn-list" onclick="switchView('list')">
        <i class="fas fa-list me-1"></i>List View
    </button>
</div>

<!-- Weekly View -->
<div id="weekly-view">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0" id="weekly-table">
                    <thead style="background:#2c3e50; color:white;">
                        <tr>
                            <th class="p-3" style="min-width:100px">Time</th>
                            <th class="p-3 text-center">Monday</th>
                            <th class="p-3 text-center">Tuesday</th>
                            <th class="p-3 text-center">Wednesday</th>
                            <th class="p-3 text-center">Thursday</th>
                            <th class="p-3 text-center">Friday</th>
                            <th class="p-3 text-center">Saturday</th>
                        </tr>
                    </thead>
                    <tbody id="weekly-body">
                        <tr><td colspan="7" class="text-center py-4 text-muted">
                            Loading...
                        </td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- List View -->
<div id="list-view" class="d-none">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background:#2c3e50; color:white;">
                        <tr>
                            <th class="p-3">Course</th>
                            <th class="p-3">Lecturer</th>
                            <th class="p-3">Room</th>
                            <th class="p-3">Day & Time</th>
                            <th class="p-3">Group</th>
                            <th class="p-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="list-body">
                        <tr><td colspan="6" class="text-center py-4 text-muted">
                            Loading...
                        </td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Empty State -->
<div id="empty-state" class="text-center py-5 d-none">
    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
    <h5 class="text-muted">No timetable entries yet</h5>
    <p class="text-muted">Click "Add Entry" to schedule a course</p>
</div>

<!-- ADD/EDIT MODAL -->
<div class="modal fade" id="timetableModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background:#2c3e50">
                <h5 class="modal-title" id="modal-title">
                    <i class="fas fa-plus me-2"></i>Add Timetable Entry
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="form-error" class="alert alert-danger d-none"></div>
                <div id="clash-warning" class="alert alert-warning d-none">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="clash-message"></span>
                </div>
                <input type="hidden" id="entry-id">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Course <span class="text-danger">*</span></label>
                        <select id="entry-course" class="form-select">
                            <option value="">-- Select Course --</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Lecturer <span class="text-danger">*</span></label>
                        <select id="entry-lecturer" class="form-select">
                            <option value="">-- Select Lecturer --</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Room <span class="text-danger">*</span></label>
                        <select id="entry-room" class="form-select">
                            <option value="">-- Select Room --</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Time Slot <span class="text-danger">*</span></label>
                        <select id="entry-timeslot" class="form-select">
                            <option value="">-- Select Time Slot --</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Student Group <span class="text-danger">*</span></label>
                        <select id="entry-group" class="form-select">
                            <option value="">-- Select Group --</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="btn text-white px-4" style="background:#2c3e50" onclick="saveEntry()">
                    <i class="fas fa-save me-2"></i>Save Entry
                </button>
            </div>
        </div>
    </div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i>Delete Entry</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <p class="fs-5">Are you sure you want to delete this timetable entry?</p>
                <p class="text-muted small" id="delete-name"></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger px-4" onclick="confirmDelete()">
                    <i class="fas fa-trash me-2"></i>Delete
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
<script>
let timetables = [];
let filtered = [];
let courses = [], lecturers = [], rooms = [], timeSlots = [], groups = [];
let deleteId = null;
let currentView = 'weekly';

const timetableModal = new bootstrap.Modal(document.getElementById('timetableModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

const days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
const dayColors = {
    Monday:'primary', Tuesday:'success', Wednesday:'warning',
    Thursday:'danger', Friday:'info', Saturday:'secondary'
};

async function loadData() {
    try {
        const [tRes, cRes, lRes, rRes, tsRes, gRes] = await Promise.all([
            axios.get('/api/timetables'),
            axios.get('/api/courses'),
            axios.get('/api/lecturers'),
            axios.get('/api/rooms'),
            axios.get('/api/time-slots'),
            axios.get('/api/student-groups')
        ]);

        timetables = tRes.data;
        courses = cRes.data;
        lecturers = lRes.data;
        rooms = rRes.data;
        timeSlots = tsRes.data;
        groups = gRes.data;

        filtered = timetables;
        renderView();
        updateStats();
        populateDropdowns();
        populateFilters();

    } catch(e) {
        console.error(e);
    }
}

function updateStats() {
    document.getElementById('total-count').textContent = filtered.length;
    document.getElementById('courses-count').textContent =
        new Set(filtered.map(t => t.course_id)).size;
    document.getElementById('lecturers-count').textContent =
        new Set(filtered.map(t => t.lecturer_id)).size;
    document.getElementById('rooms-count').textContent =
        new Set(filtered.map(t => t.room_id)).size;
}

function populateDropdowns() {
    const selCourse = document.getElementById('entry-course');
    const selLecturer = document.getElementById('entry-lecturer');
    const selRoom = document.getElementById('entry-room');
    const selSlot = document.getElementById('entry-timeslot');
    const selGroup = document.getElementById('entry-group');

    courses.forEach(c => {
        selCourse.innerHTML += `<option value="${c.id}">${c.course_code} - ${c.course_name}</option>`;
    });
    lecturers.forEach(l => {
        selLecturer.innerHTML += `<option value="${l.id}">${l.name}</option>`;
    });
    rooms.forEach(r => {
        selRoom.innerHTML += `<option value="${r.id}">Room ${r.room_number} (${r.building}) - ${r.capacity} seats</option>`;
    });
    timeSlots.filter(s => s.is_active).forEach(s => {
        selSlot.innerHTML += `<option value="${s.id}">${s.day} ${s.start_time.substring(0,5)} - ${s.end_time.substring(0,5)}</option>`;
    });
    groups.forEach(g => {
        selGroup.innerHTML += `<option value="${g.id}">${g.name}</option>`;
    });
}

function populateFilters() {
    const fLec = document.getElementById('filterLecturer');
    const fRoom = document.getElementById('filterRoom');
    const fGroup = document.getElementById('filterGroup');

    lecturers.forEach(l => {
        fLec.innerHTML += `<option value="${l.id}">${l.name}</option>`;
    });
    rooms.forEach(r => {
        fRoom.innerHTML += `<option value="${r.id}">Room ${r.room_number}</option>`;
    });
    groups.forEach(g => {
        fGroup.innerHTML += `<option value="${g.id}">${g.name}</option>`;
    });
}

function filterTimetable() {
    const lec = document.getElementById('filterLecturer').value;
    const room = document.getElementById('filterRoom').value;
    const group = document.getElementById('filterGroup').value;
    const day = document.getElementById('filterDay').value;

    filtered = timetables.filter(t => {
        const matchLec = !lec || t.lecturer_id == lec;
        const matchRoom = !room || t.room_id == room;
        const matchGroup = !group || t.group_id == group;
        const matchDay = !day || t.time_slot?.day === day;
        return matchLec && matchRoom && matchGroup && matchDay;
    });

    renderView();
    updateStats();
}

function switchView(view) {
    currentView = view;
    document.getElementById('weekly-view').classList.toggle('d-none', view !== 'weekly');
    document.getElementById('list-view').classList.toggle('d-none', view !== 'list');
    document.getElementById('btn-weekly').className =
        view === 'weekly' ? 'btn btn-sm btn-dark' : 'btn btn-sm btn-outline-dark';
    document.getElementById('btn-list').className =
        view === 'list' ? 'btn btn-sm btn-dark' : 'btn btn-sm btn-outline-dark';
    renderView();
}

function renderView() {
    if (currentView === 'weekly') renderWeekly();
    else renderList();
}

function renderWeekly() {
    const allSlots = [...new Set(timetables.map(t =>
        t.time_slot ? `${t.time_slot.start_time.substring(0,5)}-${t.time_slot.end_time.substring(0,5)}` : ''
    ))].filter(Boolean).sort();

    if (allSlots.length === 0) {
        document.getElementById('weekly-body').innerHTML =
            '<tr><td colspan="7" class="text-center py-4 text-muted">No entries yet</td></tr>';
        return;
    }

    const tbody = document.getElementById('weekly-body');
    tbody.innerHTML = allSlots.map(slot => {
        const [start, end] = slot.split('-');
        let row = `<tr>
            <td class="fw-bold text-center p-2" style="background:#f8f9fa;">
                <small>${start}</small><br><small class="text-muted">to ${end}</small>
            </td>`;

        days.forEach(day => {
            const entry = filtered.find(t =>
                t.time_slot &&
                t.time_slot.start_time.substring(0,5) === start &&
                t.time_slot.day === day
            );

            if (entry) {
                row += `<td class="p-1">
                    <div class="rounded p-2 text-white text-center"
                        style="background:#2c3e50;font-size:0.75rem">
                        <div class="fw-bold">${entry.course?.course_code ?? '-'}</div>
                        <div class="opacity-75">${entry.lecturer?.name?.split(' ')[0] ?? '-'}</div>
                        <div><i class="fas fa-door-open me-1"></i>${entry.room?.room_number ?? '-'}</div>
                        <div class="mt-1 d-flex gap-1 justify-content-center">
                            <button class="btn btn-xs btn-light"
                                style="padding:1px 5px;font-size:0.65rem"
                                onclick="openDeleteModal(${entry.id}, '${entry.course?.course_name ?? ''}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </td>`;
            } else {
                row += `<td class="p-1" style="background:#fafafa">
                    <div class="text-center text-muted" style="font-size:0.7rem;padding:10px">—</div>
                </td>`;
            }
        });

        row += '</tr>';
        return row;
    }).join('');
}

function renderList() {
    const tbody = document.getElementById('list-body');

    if (filtered.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted">No entries found</td></tr>';
        return;
    }

    tbody.innerHTML = filtered.map(t => `
        <tr>
            <td>
                <span class="badge bg-warning text-dark me-1">${t.course?.course_code ?? '-'}</span>
                ${t.course?.course_name ?? '-'}
            </td>
            <td><i class="fas fa-user-tie me-1 text-muted"></i>${t.lecturer?.name ?? '-'}</td>
            <td><i class="fas fa-door-open me-1 text-muted"></i>${t.room?.room_number ?? '-'}</td>
            <td>
                <span class="badge bg-${dayColors[t.time_slot?.day] ?? 'secondary'} me-1">
                    ${t.time_slot?.day ?? '-'}
                </span>
                ${t.time_slot?.start_time?.substring(0,5) ?? ''} -
                ${t.time_slot?.end_time?.substring(0,5) ?? ''}
            </td>
            <td><i class="fas fa-users me-1 text-muted"></i>${t.student_group?.name ?? '-'}</td>
            <td class="text-center">
                <button class="btn btn-sm btn-outline-danger"
                    onclick="openDeleteModal(${t.id}, '${t.course?.course_name ?? ''}')">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function openAddModal() {
    document.getElementById('modal-title').innerHTML =
        '<i class="fas fa-plus me-2"></i>Add Timetable Entry';
    document.getElementById('entry-id').value = '';
    document.getElementById('entry-course').value = '';
    document.getElementById('entry-lecturer').value = '';
    document.getElementById('entry-room').value = '';
    document.getElementById('entry-timeslot').value = '';
    document.getElementById('entry-group').value = '';
    document.getElementById('form-error').classList.add('d-none');
    document.getElementById('clash-warning').classList.add('d-none');
    timetableModal.show();
}

async function saveEntry() {
    const data = {
        course_id: document.getElementById('entry-course').value,
        lecturer_id: document.getElementById('entry-lecturer').value,
        room_id: document.getElementById('entry-room').value,
        time_slot_id: document.getElementById('entry-timeslot').value,
        group_id: document.getElementById('entry-group').value,
    };
    const errorDiv = document.getElementById('form-error');
    const clashDiv = document.getElementById('clash-warning');

    if (!data.course_id || !data.lecturer_id || !data.room_id ||
        !data.time_slot_id || !data.group_id) {
        errorDiv.textContent = 'All fields are required.';
        errorDiv.classList.remove('d-none');
        return;
    }

    try {
        await axios.post('/api/timetables', data);
        timetableModal.hide();
        loadData();
    } catch(e) {
        const msg = e.response?.data?.error || e.response?.data?.message || 'Error saving entry.';
        if (msg.includes('clash')) {
            clashDiv.querySelector('#clash-message').textContent = msg;
            clashDiv.classList.remove('d-none');
            errorDiv.classList.add('d-none');
        } else {
            errorDiv.textContent = msg;
            errorDiv.classList.remove('d-none');
        }
    }
}

function openDeleteModal(id, name) {
    deleteId = id;
    document.getElementById('delete-name').textContent = name;
    deleteModal.show();
}

async function confirmDelete() {
    try {
        await axios.delete(`/api/timetables/${deleteId}`);
        deleteModal.hide();
        loadData();
    } catch(e) {
        alert('Error deleting entry.');
    }
}

function exportPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('landscape');

    doc.setFontSize(16);
    doc.text('Academic Timetable', 14, 15);
    doc.setFontSize(10);
    doc.text(`Generated: ${new Date().toLocaleDateString()}`, 14, 22);

    const rows = filtered.map(t => [
        t.course?.course_code ?? '-',
        t.course?.course_name ?? '-',
        t.lecturer?.name ?? '-',
        t.room?.room_number ?? '-',
        t.time_slot?.day ?? '-',
        `${t.time_slot?.start_time?.substring(0,5) ?? ''} - ${t.time_slot?.end_time?.substring(0,5) ?? ''}`,
        t.student_group?.name ?? '-'
    ]);

    doc.autoTable({
        startY: 28,
        head: [['Code', 'Course', 'Lecturer', 'Room', 'Day', 'Time', 'Group']],
        body: rows,
        styles: { fontSize: 8 },
        headStyles: { fillColor: [44, 62, 80] }
    });

    doc.save('timetable.pdf');
}

loadData();
</script>

<style>
.hover-card { transition: transform 0.2s, box-shadow 0.2s; }
.hover-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
</style>
@endsection