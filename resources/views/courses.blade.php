@extends('layout')
@section('page-title', 'Courses')

@section('content')

<div class="page-header">
    <div>
        <h4 class="page-title"><i class="fas fa-book me-2" style="color:#f8961e"></i>Courses</h4>
        <p class="page-subtitle">Manage all university courses</p>
    </div>
    <button class="btn btn-warning" onclick="openAddModal()">
        <i class="fas fa-plus me-2"></i>Add Course
    </button>
</div>

{{-- Stat --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #f8961e, #c97a08);">
            <div class="stat-number" id="total-count">—</div>
            <div class="stat-label">Total Courses</div>
            <i class="fas fa-book stat-icon"></i>
        </div>
    </div>
</div>

{{-- Search & Filters --}}
<div class="search-card">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" id="searchInput" class="form-control"
                    placeholder="Search courses…" oninput="filterCourses()">
            </div>
        </div>
        <div class="col-md-3">
            <select id="filterDept" class="form-select" onchange="filterCourses()">
                <option value="">All Departments</option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="filterSemester" class="form-select" onchange="filterCourses()">
                <option value="">All Semesters</option>
                <option>Semester 1</option><option>Semester 2</option>
                <option>Semester 3</option><option>Semester 4</option>
                <option>Semester 5</option><option>Semester 6</option>
            </select>
        </div>
    </div>
</div>

{{-- Grid --}}
<div class="row g-3" id="courses-grid"></div>

<div id="empty-state" class="text-center py-5 d-none">
    <div class="empty-state-icon"><i class="fas fa-book"></i></div>
    <h6 class="text-muted mt-2">No courses yet</h6>
    <p class="text-muted small">Click "Add Course" to get started</p>
</div>

<div id="loading" class="text-center py-5">
    <div class="spinner-border" style="color:#f8961e"></div>
    <p class="mt-2 text-muted small">Loading courses…</p>
</div>

{{-- ADD / EDIT MODAL --}}
<div class="modal fade" id="courseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg,#f8961e,#c97a08)">
                <h5 class="modal-title text-white" id="modal-title">
                    <i class="fas fa-plus me-2"></i>Add Course
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="form-error" class="alert alert-danger d-none py-2" style="border-radius:10px;font-size:0.82rem"></div>
                <input type="hidden" id="course-id">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Course Code <span class="text-danger">*</span></label>
                        <input type="text" id="course-code" class="form-control" placeholder="e.g. CS3424">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Course Name <span class="text-danger">*</span></label>
                        <input type="text" id="course-name" class="form-control" placeholder="e.g. Web Development">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select id="course-department" class="form-select">
                            <option value="">— Select Department —</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Lecturer <span class="text-danger">*</span></label>
                        <select id="course-lecturer" class="form-select">
                            <option value="">— Select Lecturer —</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Duration (Hours) <span class="text-danger">*</span></label>
                        <input type="number" id="course-duration" class="form-control" placeholder="e.g. 45" min="1">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Semester <span class="text-danger">*</span></label>
                        <select id="course-semester" class="form-select">
                            <option value="">— Select Semester —</option>
                            <option>Semester 1</option><option>Semester 2</option>
                            <option>Semester 3</option><option>Semester 4</option>
                            <option>Semester 5</option><option>Semester 6</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-warning btn-sm" onclick="saveCourse()">
                    <i class="fas fa-save me-1"></i>Save
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
                <h5 class="modal-title text-white"><i class="fas fa-trash me-2"></i>Delete Course</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="empty-state-icon mb-3" style="background:#fff0f2;color:#ef233c">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p class="mb-1">Are you sure you want to delete</p>
                <p class="fw-bold fs-6" id="delete-name"></p>
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
<script>
let courses = [], departments = [], lecturers = [];
let deleteId = null;
const courseModal = new bootstrap.Modal(document.getElementById('courseModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

const semColors = { 'Semester 1':'#4361ee','Semester 2':'#7209b7','Semester 3':'#2dc653',
                    'Semester 4':'#ef233c','Semester 5':'#f8961e','Semester 6':'#4cc9f0' };

async function loadData() {
    try {
        const [cRes, dRes, lRes] = await Promise.all([
            axios.get('/api/courses'), axios.get('/api/departments'), axios.get('/api/lecturers')
        ]);
        courses = cRes.data; departments = dRes.data; lecturers = lRes.data;
        renderCourses(courses);
        document.getElementById('total-count').textContent = courses.length;
        document.getElementById('loading').classList.add('d-none');

        const dSel = document.getElementById('course-department');
        const fDept = document.getElementById('filterDept');
        departments.forEach(d => {
            dSel.innerHTML  += `<option value="${d.id}">${d.name}</option>`;
            fDept.innerHTML += `<option value="${d.id}">${d.name}</option>`;
        });

        const lSel = document.getElementById('course-lecturer');
        lecturers.forEach(l => { lSel.innerHTML += `<option value="${l.id}">${l.name}</option>`; });
    } catch(e) {
        document.getElementById('loading').innerHTML = '<p class="text-danger">Error loading data.</p>';
    }
}

function renderCourses(data) {
    const grid = document.getElementById('courses-grid');
    const empty = document.getElementById('empty-state');
    if (data.length === 0) { grid.innerHTML = ''; empty.classList.remove('d-none'); return; }
    empty.classList.add('d-none');

    grid.innerHTML = data.map(c => {
        const sc = semColors[c.semester] ?? '#718096';
        return `
        <div class="col-md-4 col-sm-6">
            <div class="card hover-card h-100 border-0" style="border-radius:16px;overflow:hidden">
                <div style="height:4px;background:linear-gradient(90deg,${sc},${sc}99)"></div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div style="width:44px;height:44px;border-radius:12px;
                            background:${sc}1a;display:flex;align-items:center;
                            justify-content:center;flex-shrink:0">
                            <i class="fas fa-book" style="color:${sc}"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="badge mb-1" style="background:${sc}1a;color:${sc}">${c.course_code}</span>
                            <h6 class="fw-bold mb-0 text-truncate">${c.course_name}</h6>
                        </div>
                    </div>
                    <div style="border-top:1px solid #f0f2f8;padding-top:10px">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted"><i class="fas fa-building me-1"></i>Dept</small>
                            <small class="fw-bold text-truncate ms-2" style="max-width:55%">${c.department?.name ?? 'N/A'}</small>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted"><i class="fas fa-user-tie me-1"></i>Lecturer</small>
                            <small class="fw-bold text-truncate ms-2" style="max-width:55%">${c.lecturer?.name ?? 'N/A'}</small>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted"><i class="fas fa-clock me-1"></i>Duration</small>
                            <span class="badge" style="background:#e8f4ff;color:#2196f3">${c.duration_hours}h</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <small class="text-muted"><i class="fas fa-calendar me-1"></i>Semester</small>
                            <span class="badge" style="background:${sc}1a;color:${sc}">${c.semester}</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm flex-grow-1" onclick="openEditModal(${c.id})">
                                <i class="fas fa-edit me-1"></i>Edit
                            </button>
                            <button class="btn btn-outline-danger btn-sm flex-grow-1"
                                onclick="openDeleteModal(${c.id}, '${c.course_name.replace(/'/g,"\\'")}')">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');
}

function filterCourses() {
    const q    = document.getElementById('searchInput').value.toLowerCase();
    const dept = document.getElementById('filterDept').value;
    const sem  = document.getElementById('filterSemester').value;
    renderCourses(courses.filter(c =>
        (c.course_name.toLowerCase().includes(q) || c.course_code.toLowerCase().includes(q)) &&
        (!dept || c.department_id == dept) &&
        (!sem  || c.semester === sem)
    ));
}

function openAddModal() {
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-plus me-2"></i>Add Course';
    ['course-id','course-code','course-name','course-duration'].forEach(id => document.getElementById(id).value = '');
    ['course-department','course-lecturer','course-semester'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('form-error').classList.add('d-none');
    courseModal.show();
}

function openEditModal(id) {
    const c = courses.find(x => x.id === id);
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Course';
    document.getElementById('course-id').value         = c.id;
    document.getElementById('course-code').value       = c.course_code;
    document.getElementById('course-name').value       = c.course_name;
    document.getElementById('course-department').value = c.department_id;
    document.getElementById('course-lecturer').value   = c.lecturer_id;
    document.getElementById('course-duration').value   = c.duration_hours;
    document.getElementById('course-semester').value   = c.semester;
    document.getElementById('form-error').classList.add('d-none');
    courseModal.show();
}

async function saveCourse() {
    const id   = document.getElementById('course-id').value;
    const data = {
        course_code:   document.getElementById('course-code').value.trim(),
        course_name:   document.getElementById('course-name').value.trim(),
        department_id: document.getElementById('course-department').value,
        lecturer_id:   document.getElementById('course-lecturer').value,
        duration_hours: document.getElementById('course-duration').value,
        semester:      document.getElementById('course-semester').value,
    };
    const err = document.getElementById('form-error');
    if (!data.course_code || !data.course_name || !data.department_id || !data.lecturer_id || !data.duration_hours || !data.semester) {
        err.textContent = 'All fields are required.'; err.classList.remove('d-none'); return;
    }
    try {
        id ? await axios.put(`/api/courses/${id}`, data)
           : await axios.post('/api/courses', data);
        courseModal.hide(); loadData();
    } catch(e) {
        err.textContent = e.response?.data?.message || 'Error saving course.';
        err.classList.remove('d-none');
    }
}

function openDeleteModal(id, name) { deleteId = id; document.getElementById('delete-name').textContent = name; deleteModal.show(); }

async function confirmDelete() {
    try { await axios.delete(`/api/courses/${deleteId}`); deleteModal.hide(); loadData(); }
    catch(e) { alert('Error deleting course.'); }
}

loadData();
</script>
@endsection
