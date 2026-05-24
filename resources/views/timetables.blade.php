@extends('layout')
@section('page-title', 'Timetables')

@section('content')

<div class="page-header">
    <div>
        <h4 class="page-title"><i class="fas fa-table me-2" style="color:#4361ee"></i>Timetables</h4>
        <p class="page-subtitle">Manage and view academic timetables</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" onclick="exportPDF()">
            <i class="fas fa-file-pdf me-2"></i>Export PDF
        </button>
        <button class="btn btn-primary" onclick="openAddModal()">
            <i class="fas fa-plus me-2"></i>Add Entry
        </button>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card" style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
            <div class="stat-number" id="total-count">—</div>
            <div class="stat-label">Total Entries</div>
            <i class="fas fa-table stat-icon"></i>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card" style="background: linear-gradient(135deg, #2dc653, #1a7a35);">
            <div class="stat-number" id="courses-count">—</div>
            <div class="stat-label">Courses Scheduled</div>
            <i class="fas fa-book stat-icon"></i>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card" style="background: linear-gradient(135deg, #f8961e, #c97a08);">
            <div class="stat-number" id="lecturers-count">—</div>
            <div class="stat-label">Lecturers Assigned</div>
            <i class="fas fa-chalkboard-teacher stat-icon"></i>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card" style="background: linear-gradient(135deg, #ef233c, #b01020);">
            <div class="stat-number" id="rooms-count">—</div>
            <div class="stat-label">Rooms Used</div>
            <i class="fas fa-door-open stat-icon"></i>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="search-card">
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
                <option>Monday</option><option>Tuesday</option><option>Wednesday</option>
                <option>Thursday</option><option>Friday</option><option>Saturday</option>
            </select>
        </div>
    </div>
</div>

{{-- View Toggle --}}
<div class="d-flex gap-2 mb-3">
    <button class="btn btn-sm btn-primary" id="btn-weekly" onclick="switchView('weekly')">
        <i class="fas fa-calendar-week me-1"></i>Weekly View
    </button>
    <button class="btn btn-sm btn-outline-dark" id="btn-list" onclick="switchView('list')">
        <i class="fas fa-list me-1"></i>List View
    </button>
</div>

{{-- Weekly View --}}
<div id="weekly-view">
    <div class="card border-0" style="border-radius:16px;overflow:hidden">
        <div class="table-responsive">
            <table class="table table-bordered mb-0" id="weekly-table">
                <thead style="background: linear-gradient(90deg,#4361ee,#7209b7)">
                    <tr>
                        <th class="p-3 text-white" style="min-width:90px;font-size:0.75rem">Time</th>
                        <th class="p-3 text-white text-center" style="font-size:0.75rem">Monday</th>
                        <th class="p-3 text-white text-center" style="font-size:0.75rem">Tuesday</th>
                        <th class="p-3 text-white text-center" style="font-size:0.75rem">Wednesday</th>
                        <th class="p-3 text-white text-center" style="font-size:0.75rem">Thursday</th>
                        <th class="p-3 text-white text-center" style="font-size:0.75rem">Friday</th>
                        <th class="p-3 text-white text-center" style="font-size:0.75rem">Saturday</th>
                    </tr>
                </thead>
                <tbody id="weekly-body">
                    <tr><td colspan="7" class="text-center py-5 text-muted">Loading…</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- List View --}}
<div id="list-view" class="d-none">
    <div class="card border-0" style="border-radius:16px;overflow:hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: linear-gradient(90deg,#4361ee,#7209b7)">
                    <tr>
                        <th class="p-3 text-white" style="font-size:0.75rem">Course</th>
                        <th class="p-3 text-white" style="font-size:0.75rem">Lecturer</th>
                        <th class="p-3 text-white" style="font-size:0.75rem">Room</th>
                        <th class="p-3 text-white" style="font-size:0.75rem">Day & Time</th>
                        <th class="p-3 text-white" style="font-size:0.75rem">Group</th>
                        <th class="p-3 text-white text-center" style="font-size:0.75rem">Actions</th>
                    </tr>
                </thead>
                <tbody id="list-body">
                    <tr><td colspan="6" class="text-center py-5 text-muted">Loading…</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Empty State --}}
<div id="empty-state" class="text-center py-5 d-none">
    <div class="empty-state-icon"><i class="fas fa-calendar-times"></i></div>
    <h6 class="text-muted mt-2">No timetable entries yet</h6>
    <p class="text-muted small">Click "Add Entry" to schedule a course</p>
</div>

{{-- ADD MODAL --}}
<div class="modal fade" id="timetableModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg,#4361ee,#3a0ca3)">
                <h5 class="modal-title text-white" id="modal-title">
                    <i class="fas fa-plus me-2"></i>Add Timetable Entry
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="form-error" class="alert alert-danger d-none py-2" style="border-radius:10px;font-size:0.82rem"></div>
                <div id="clash-warning" class="alert alert-warning d-none py-2" style="border-radius:10px;font-size:0.82rem">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="clash-message"></span>
                </div>
                <input type="hidden" id="entry-id">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Course <span class="text-danger">*</span></label>
                        <select id="entry-course" class="form-select">
                            <option value="">— Select Course —</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Lecturer <span class="text-danger">*</span></label>
                        <select id="entry-lecturer" class="form-select">
                            <option value="">— Select Lecturer —</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Room <span class="text-danger">*</span></label>
                        <select id="entry-room" class="form-select">
                            <option value="">— Select Room —</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Time Slot <span class="text-danger">*</span></label>
                        <select id="entry-timeslot" class="form-select">
                            <option value="">— Select Time Slot —</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Student Group <span class="text-danger">*</span></label>
                        <select id="entry-group" class="form-select">
                            <option value="">— Select Group —</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary btn-sm" onclick="saveEntry()">
                    <i class="fas fa-save me-1"></i>Save Entry
                </button>
            </div>
        </div>
    </div>
</div>

{{-- DELETE MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg,#ef233c,#b01020)">
                <h5 class="modal-title text-white"><i class="fas fa-trash me-2"></i>Delete Entry</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="empty-state-icon mb-3" style="background:#fff0f2;color:#ef233c">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p class="mb-1">Are you sure you want to delete this timetable entry?</p>
                <p class="text-muted small fw-bold" id="delete-name"></p>
                <p class="text-muted small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger btn-sm" onclick="confirmDelete()">
                    <i class="fas fa-trash me-1"></i>Delete
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
let timetables = [], filtered = [];
let courses = [], lecturers = [], rooms = [], timeSlots = [], groups = [];
let deleteId = null, currentView = 'weekly';

const timetableModal = new bootstrap.Modal(document.getElementById('timetableModal'));
const deleteModal    = new bootstrap.Modal(document.getElementById('deleteModal'));

const days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
const dayColors = {
    Monday:'#4361ee', Tuesday:'#2dc653', Wednesday:'#f8961e',
    Thursday:'#ef233c', Friday:'#4cc9f0', Saturday:'#7209b7'
};
const dayBadge = {
    Monday:'primary', Tuesday:'success', Wednesday:'warning',
    Thursday:'danger', Friday:'info', Saturday:'secondary'
};

async function loadData() {
    try {
        const [tRes,cRes,lRes,rRes,tsRes,gRes] = await Promise.all([
            axios.get('/api/timetables'), axios.get('/api/courses'), axios.get('/api/lecturers'),
            axios.get('/api/rooms'), axios.get('/api/time-slots'), axios.get('/api/student-groups')
        ]);
        timetables = tRes.data; courses = cRes.data; lecturers = lRes.data;
        rooms = rRes.data; timeSlots = tsRes.data; groups = gRes.data;
        filtered = timetables;
        renderView(); updateStats(); populateDropdowns(); populateFilters();
    } catch(e) { console.error(e); }
}

function updateStats() {
    document.getElementById('total-count').textContent    = filtered.length;
    document.getElementById('courses-count').textContent  = new Set(filtered.map(t => t.course_id)).size;
    document.getElementById('lecturers-count').textContent= new Set(filtered.map(t => t.lecturer_id)).size;
    document.getElementById('rooms-count').textContent    = new Set(filtered.map(t => t.room_id)).size;
}

function populateDropdowns() {
    courses.forEach(c => {
        document.getElementById('entry-course').innerHTML += `<option value="${c.id}">${c.course_code} — ${c.course_name}</option>`;
    });
    lecturers.forEach(l => {
        document.getElementById('entry-lecturer').innerHTML += `<option value="${l.id}">${l.name}</option>`;
    });
    rooms.forEach(r => {
        document.getElementById('entry-room').innerHTML += `<option value="${r.id}">Room ${r.room_number} (${r.building}) — ${r.capacity} seats</option>`;
    });
    timeSlots.filter(s => s.is_active).forEach(s => {
        document.getElementById('entry-timeslot').innerHTML += `<option value="${s.id}">${s.day} ${s.start_time.substring(0,5)} – ${s.end_time.substring(0,5)}</option>`;
    });
    groups.forEach(g => {
        document.getElementById('entry-group').innerHTML += `<option value="${g.id}">${g.name}</option>`;
    });
}

function populateFilters() {
    lecturers.forEach(l => { document.getElementById('filterLecturer').innerHTML += `<option value="${l.id}">${l.name}</option>`; });
    rooms.forEach(r => { document.getElementById('filterRoom').innerHTML += `<option value="${r.id}">Room ${r.room_number}</option>`; });
    groups.forEach(g => { document.getElementById('filterGroup').innerHTML += `<option value="${g.id}">${g.name}</option>`; });
}

function filterTimetable() {
    const lec   = document.getElementById('filterLecturer').value;
    const room  = document.getElementById('filterRoom').value;
    const group = document.getElementById('filterGroup').value;
    const day   = document.getElementById('filterDay').value;
    filtered = timetables.filter(t =>
        (!lec   || t.lecturer_id == lec) &&
        (!room  || t.room_id == room) &&
        (!group || t.group_id == group) &&
        (!day   || t.time_slot?.day === day)
    );
    renderView(); updateStats();
}

function switchView(view) {
    currentView = view;
    document.getElementById('weekly-view').classList.toggle('d-none', view !== 'weekly');
    document.getElementById('list-view').classList.toggle('d-none', view !== 'list');
    document.getElementById('btn-weekly').className = view === 'weekly' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-dark';
    document.getElementById('btn-list').className   = view === 'list'   ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-dark';
    renderView();
}

function renderView() {
    if (currentView === 'weekly') renderWeekly(); else renderList();
}

function renderWeekly() {
    const allSlots = [...new Set(timetables.map(t =>
        t.time_slot ? `${t.time_slot.start_time.substring(0,5)}-${t.time_slot.end_time.substring(0,5)}` : ''
    ))].filter(Boolean).sort();

    if (allSlots.length === 0) {
        document.getElementById('weekly-body').innerHTML =
            '<tr><td colspan="7" class="text-center py-5 text-muted">No entries yet — click "Add Entry" to begin</td></tr>';
        return;
    }

    document.getElementById('weekly-body').innerHTML = allSlots.map(slot => {
        const [start, end] = slot.split('-');
        let row = `<tr>
            <td class="fw-bold text-center p-2" style="background:#f8faff;width:90px">
                <div style="font-size:0.8rem;color:#4361ee;font-weight:700">${start}</div>
                <div style="font-size:0.65rem;color:#a0aec0">to ${end}</div>
            </td>`;

        days.forEach(day => {
            const entry = filtered.find(t =>
                t.time_slot &&
                t.time_slot.start_time.substring(0,5) === start &&
                t.time_slot.day === day
            );
            const color = dayColors[day];

            if (entry) {
                row += `<td class="p-1" style="background:${color}06">
                    <div class="rounded-3 p-2 text-center"
                         style="background:linear-gradient(135deg,${color},${color}cc);font-size:0.72rem;color:#fff">
                        <div class="fw-bold mb-1">${entry.course?.course_code ?? '-'}</div>
                        <div style="opacity:0.85">${entry.lecturer?.name?.split(' ')[0] ?? '-'}</div>
                        <div style="opacity:0.75"><i class="fas fa-door-open me-1"></i>${entry.room?.room_number ?? '-'}</div>
                        <div class="mt-1">
                            <button class="btn btn-light"
                                style="padding:1px 7px;font-size:0.6rem;border-radius:5px;color:#ef233c"
                                onclick="openDeleteModal(${entry.id}, '${entry.course?.course_name ?? ''}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </td>`;
            } else {
                row += `<td style="background:#fafbff">
                    <div class="text-center text-muted" style="font-size:0.7rem;padding:12px 4px;opacity:0.3">—</div>
                </td>`;
            }
        });

        return row + '</tr>';
    }).join('');
}

function renderList() {
    const tbody = document.getElementById('list-body');
    if (filtered.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-muted">No entries found</td></tr>';
        return;
    }
    tbody.innerHTML = filtered.map(t => `
        <tr>
            <td class="px-3">
                <span class="badge me-1" style="background:#4361ee">${t.course?.course_code ?? '-'}</span>
                <span style="font-size:0.85rem">${t.course?.course_name ?? '-'}</span>
            </td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <div style="width:26px;height:26px;border-radius:7px;background:#f0f2ff;
                        display:flex;align-items:center;justify-content:center;
                        font-size:0.65rem;color:#4361ee;font-weight:700">
                        ${(t.lecturer?.name ?? 'N').charAt(0)}
                    </div>
                    <span style="font-size:0.85rem">${t.lecturer?.name ?? '-'}</span>
                </div>
            </td>
            <td>
                <span class="badge" style="background:#fff0f2;color:#ef233c">
                    <i class="fas fa-door-open me-1"></i>${t.room?.room_number ?? '-'}
                </span>
            </td>
            <td>
                <span class="badge bg-${dayBadge[t.time_slot?.day] ?? 'secondary'} me-1">${t.time_slot?.day ?? '-'}</span>
                <small class="text-muted">
                    ${t.time_slot?.start_time?.substring(0,5) ?? ''} – ${t.time_slot?.end_time?.substring(0,5) ?? ''}
                </small>
            </td>
            <td style="font-size:0.85rem">${t.student_group?.name ?? '-'}</td>
            <td class="text-center">
                <button class="btn btn-outline-danger btn-sm"
                    onclick="openDeleteModal(${t.id}, '${t.course?.course_name ?? ''}')">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function openAddModal() {
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-plus me-2"></i>Add Timetable Entry';
    ['entry-id','entry-course','entry-lecturer','entry-room','entry-timeslot','entry-group']
        .forEach(id => document.getElementById(id).value = '');
    document.getElementById('form-error').classList.add('d-none');
    document.getElementById('clash-warning').classList.add('d-none');
    timetableModal.show();
}

async function saveEntry() {
    const data = {
        course_id:    document.getElementById('entry-course').value,
        lecturer_id:  document.getElementById('entry-lecturer').value,
        room_id:      document.getElementById('entry-room').value,
        time_slot_id: document.getElementById('entry-timeslot').value,
        group_id:     document.getElementById('entry-group').value,
    };
    const errorDiv = document.getElementById('form-error');
    const clashDiv = document.getElementById('clash-warning');

    if (!data.course_id || !data.lecturer_id || !data.room_id || !data.time_slot_id || !data.group_id) {
        errorDiv.textContent = 'All fields are required.'; errorDiv.classList.remove('d-none'); return;
    }
    try {
        await axios.post('/api/timetables', data);
        timetableModal.hide(); loadData();
    } catch(e) {
        const msg = e.response?.data?.error || e.response?.data?.message || 'Error saving entry.';
        if (msg.includes('clash')) {
            clashDiv.querySelector('#clash-message').textContent = msg;
            clashDiv.classList.remove('d-none'); errorDiv.classList.add('d-none');
        } else {
            errorDiv.textContent = msg; errorDiv.classList.remove('d-none');
        }
    }
}

function openDeleteModal(id, name) { deleteId = id; document.getElementById('delete-name').textContent = name; deleteModal.show(); }

async function confirmDelete() {
    try { await axios.delete(`/api/timetables/${deleteId}`); deleteModal.hide(); loadData(); }
    catch(e) { alert('Error deleting entry.'); }
}

function exportPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('landscape');
    doc.setFontSize(16); doc.text('Academic Timetable', 14, 15);
    doc.setFontSize(10); doc.text(`Generated: ${new Date().toLocaleDateString()}`, 14, 22);
    doc.autoTable({
        startY: 28,
        head: [['Code','Course','Lecturer','Room','Day','Time','Group']],
        body: filtered.map(t => [
            t.course?.course_code ?? '-', t.course?.course_name ?? '-',
            t.lecturer?.name ?? '-', t.room?.room_number ?? '-',
            t.time_slot?.day ?? '-',
            `${t.time_slot?.start_time?.substring(0,5) ?? ''} – ${t.time_slot?.end_time?.substring(0,5) ?? ''}`,
            t.student_group?.name ?? '-'
        ]),
        styles: { fontSize: 8 },
        headStyles: { fillColor: [67, 97, 238] }
    });
    doc.save('timetable.pdf');
}

loadData();
</script>
@endsection
