@extends('layout')

@section('page-title', 'Courses')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-book text-warning me-2"></i>Courses</h4>
        <p class="text-muted mb-0">Manage all university courses</p>
    </div>
    <button class="btn btn-warning px-4 text-white" onclick="openAddModal()">
        <i class="fas fa-plus me-2"></i>Add Course
    </button>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 bg-warning bg-gradient text-white">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="fas fa-book fa-2x opacity-75"></i>
                <div>
                    <div class="fs-4 fw-bold" id="total-count">0</div>
                    <div class="small opacity-75">Total Courses</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control border-start-0"
                        placeholder="Search courses..." oninput="filterCourses()">
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
                    <option>Semester 1</option>
                    <option>Semester 2</option>
                    <option>Semester 3</option>
                    <option>Semester 4</option>
                    <option>Semester 5</option>
                    <option>Semester 6</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Courses Grid -->
<div class="row g-3" id="courses-grid"></div>

<!-- Empty State -->
<div id="empty-state" class="text-center py-5 d-none">
    <i class="fas fa-book fa-4x text-muted mb-3"></i>
    <h5 class="text-muted">No courses yet</h5>
    <p class="text-muted">Click "Add Course" to get started</p>
</div>

<!-- Loading -->
<div id="loading" class="text-center py-5">
    <div class="spinner-border text-warning"></div>
    <p class="mt-2 text-muted">Loading courses...</p>
</div>

<!-- ADD/EDIT MODAL -->
<div class="modal fade" id="courseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modal-title">
                    <i class="fas fa-plus me-2"></i>Add Course
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="form-error" class="alert alert-danger d-none"></div>
                <input type="hidden" id="course-id">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Course Code <span class="text-danger">*</span></label>
                        <input type="text" id="course-code" class="form-control"
                            placeholder="e.g. CS3424">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Course Name <span class="text-danger">*</span></label>
                        <input type="text" id="course-name" class="form-control"
                            placeholder="e.g. Web Development">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Department <span class="text-danger">*</span></label>
                        <select id="course-department" class="form-select">
                            <option value="">-- Select Department --</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Lecturer <span class="text-danger">*</span></label>
                        <select id="course-lecturer" class="form-select">
                            <option value="">-- Select Lecturer --</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Duration (Hours) <span class="text-danger">*</span></label>
                        <input type="number" id="course-duration" class="form-control"
                            placeholder="e.g. 45" min="1">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Semester <span class="text-danger">*</span></label>
                        <select id="course-semester" class="form-select">
                            <option value="">-- Select Semester --</option>
                            <option>Semester 1</option>
                            <option>Semester 2</option>
                            <option>Semester 3</option>
                            <option>Semester 4</option>
                            <option>Semester 5</option>
                            <option>Semester 6</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-warning text-white px-4" onclick="saveCourse()">
                    <i class="fas fa-save me-2"></i>Save
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
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i>Delete Course</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <p class="fs-5">Are you sure you want to delete <strong id="delete-name"></strong>?</p>
                <p class="text-muted small">This action cannot be undone.</p>
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
<script>
let courses = [];
let departments = [];
let lecturers = [];
let deleteId = null;
const courseModal = new bootstrap.Modal(document.getElementById('courseModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

async function loadData() {
    try {
        const [cRes, dRes, lRes] = await Promise.all([
            axios.get('/api/courses'),
            axios.get('/api/departments'),
            axios.get('/api/lecturers')
        ]);
        courses = cRes.data;
        departments = dRes.data;
        lecturers = lRes.data;

        renderCourses(courses);
        document.getElementById('total-count').textContent = courses.length;
        document.getElementById('loading').classList.add('d-none');

        // Fill department dropdowns
        const deptSelect = document.getElementById('course-department');
        const filterDept = document.getElementById('filterDept');
        departments.forEach(d => {
            deptSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`;
            filterDept.innerHTML += `<option value="${d.id}">${d.name}</option>`;
        });

        // Fill lecturer dropdown
        const lecSelect = document.getElementById('course-lecturer');
        lecturers.forEach(l => {
            lecSelect.innerHTML += `<option value="${l.id}">${l.name}</option>`;
        });

    } catch(e) {
        document.getElementById('loading').innerHTML =
            '<p class="text-danger">Error loading data.</p>';
    }
}

function renderCourses(data) {
    const grid = document.getElementById('courses-grid');
    const empty = document.getElementById('empty-state');

    if (data.length === 0) {
        grid.innerHTML = '';
        empty.classList.remove('d-none');
        return;
    }

    empty.classList.add('d-none');
    grid.innerHTML = data.map(c => `
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-top bg-warning" style="height:5px; border-radius:8px 8px 0 0;"></div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-start mb-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="fas fa-book text-warning fa-lg"></i>
                        </div>
                        <div>
                            <span class="badge bg-warning text-dark mb-1">${c.course_code}</span>
                            <h6 class="fw-bold mb-0">${c.course_name}</h6>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted"><i class="fas fa-building me-1"></i>Department</span>
                        <span class="fw-bold">${c.department?.name ?? 'N/A'}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted"><i class="fas fa-user-tie me-1"></i>Lecturer</span>
                        <span class="fw-bold">${c.lecturer?.name ?? 'N/A'}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted"><i class="fas fa-clock me-1"></i>Duration</span>
                        <span class="badge bg-info text-dark">${c.duration_hours}h</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-3">
                        <span class="text-muted"><i class="fas fa-calendar me-1"></i>Semester</span>
                        <span class="badge bg-secondary">${c.semester}</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary flex-grow-1"
                            onclick="openEditModal(${c.id})">
                            <i class="fas fa-edit me-1"></i>Edit
                        </button>
                        <button class="btn btn-sm btn-outline-danger flex-grow-1"
                            onclick="openDeleteModal(${c.id}, '${c.course_name}')">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function filterCourses() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const dept = document.getElementById('filterDept').value;
    const sem = document.getElementById('filterSemester').value;

    const filtered = courses.filter(c => {
        const matchQ = c.course_name.toLowerCase().includes(q) ||
                       c.course_code.toLowerCase().includes(q);
        const matchDept = !dept || c.department_id == dept;
        const matchSem = !sem || c.semester === sem;
        return matchQ && matchDept && matchSem;
    });
    renderCourses(filtered);
}

function openAddModal() {
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-plus me-2"></i>Add Course';
    document.getElementById('course-id').value = '';
    document.getElementById('course-code').value = '';
    document.getElementById('course-name').value = '';
    document.getElementById('course-department').value = '';
    document.getElementById('course-lecturer').value = '';
    document.getElementById('course-duration').value = '';
    document.getElementById('course-semester').value = '';
    document.getElementById('form-error').classList.add('d-none');
    courseModal.show();
}

function openEditModal(id) {
    const c = courses.find(x => x.id === id);
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Course';
    document.getElementById('course-id').value = c.id;
    document.getElementById('course-code').value = c.course_code;
    document.getElementById('course-name').value = c.course_name;
    document.getElementById('course-department').value = c.department_id;
    document.getElementById('course-lecturer').value = c.lecturer_id;
    document.getElementById('course-duration').value = c.duration_hours;
    document.getElementById('course-semester').value = c.semester;
    document.getElementById('form-error').classList.add('d-none');
    courseModal.show();
}

async function saveCourse() {
    const id = document.getElementById('course-id').value;
    const data = {
        course_code: document.getElementById('course-code').value.trim(),
        course_name: document.getElementById('course-name').value.trim(),
        department_id: document.getElementById('course-department').value,
        lecturer_id: document.getElementById('course-lecturer').value,
        duration_hours: document.getElementById('course-duration').value,
        semester: document.getElementById('course-semester').value,
    };
    const errorDiv = document.getElementById('form-error');

    if (!data.course_code || !data.course_name || !data.department_id ||
        !data.lecturer_id || !data.duration_hours || !data.semester) {
        errorDiv.textContent = 'All fields are required.';
        errorDiv.classList.remove('d-none');
        return;
    }

    try {
        if (id) {
            await axios.put(`/api/courses/${id}`, data);
        } else {
            await axios.post('/api/courses', data);
        }
        courseModal.hide();
        loadData();
    } catch(e) {
        errorDiv.textContent = e.response?.data?.message || 'Error saving course.';
        errorDiv.classList.remove('d-none');
    }
}

function openDeleteModal(id, name) {
    deleteId = id;
    document.getElementById('delete-name').textContent = name;
    deleteModal.show();
}

async function confirmDelete() {
    try {
        await axios.delete(`/api/courses/${deleteId}`);
        deleteModal.hide();
        loadData();
    } catch(e) {
        alert('Error deleting course.');
    }
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