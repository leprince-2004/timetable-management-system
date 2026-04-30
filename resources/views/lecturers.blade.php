@extends('layout')

@section('page-title', 'Lecturers')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-chalkboard-teacher text-success me-2"></i>Lecturers</h4>
        <p class="text-muted mb-0">Manage all university lecturers</p>
    </div>
    <button class="btn btn-success px-4" onclick="openAddModal()">
        <i class="fas fa-plus me-2"></i>Add Lecturer
    </button>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 bg-success bg-gradient text-white">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="fas fa-chalkboard-teacher fa-2x opacity-75"></i>
                <div>
                    <div class="fs-4 fw-bold" id="total-count">0</div>
                    <div class="small opacity-75">Total Lecturers</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
                <i class="fas fa-search text-muted"></i>
            </span>
            <input type="text" id="searchInput" class="form-control border-start-0"
                placeholder="Search lecturers..." oninput="filterLecturers()">
        </div>
    </div>
</div>

<!-- Lecturers Grid -->
<div class="row g-3" id="lecturers-grid"></div>

<!-- Empty State -->
<div id="empty-state" class="text-center py-5 d-none">
    <i class="fas fa-chalkboard-teacher fa-4x text-muted mb-3"></i>
    <h5 class="text-muted">No lecturers yet</h5>
    <p class="text-muted">Click "Add Lecturer" to get started</p>
</div>

<!-- Loading -->
<div id="loading" class="text-center py-5">
    <div class="spinner-border text-success"></div>
    <p class="mt-2 text-muted">Loading lecturers...</p>
</div>

<!-- ADD/EDIT MODAL -->
<div class="modal fade" id="lecturerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modal-title">
                    <i class="fas fa-plus me-2"></i>Add Lecturer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="form-error" class="alert alert-danger d-none"></div>
                <input type="hidden" id="lecturer-id">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="lecturer-name" class="form-control" placeholder="e.g. Dr. John Smith">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                        <input type="email" id="lecturer-email" class="form-control" placeholder="e.g. john@university.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Department <span class="text-danger">*</span></label>
                        <select id="lecturer-department" class="form-select">
                            <option value="">-- Select Department --</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Max Hours/Week <span class="text-danger">*</span></label>
                        <input type="number" id="lecturer-hours" class="form-control" placeholder="e.g. 20" min="1">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success px-4" onclick="saveLecturer()">
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
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i>Delete Lecturer</h5>
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
let lecturers = [];
let departments = [];
let deleteId = null;
const lecturerModal = new bootstrap.Modal(document.getElementById('lecturerModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

async function loadData() {
    try {
        const [lecRes, deptRes] = await Promise.all([
            axios.get('/api/lecturers'),
            axios.get('/api/departments')
        ]);
        lecturers = lecRes.data;
        departments = deptRes.data;
        renderLecturers(lecturers);
        document.getElementById('total-count').textContent = lecturers.length;
        document.getElementById('loading').classList.add('d-none');

        // Fill department dropdown
        const select = document.getElementById('lecturer-department');
        departments.forEach(d => {
            select.innerHTML += `<option value="${d.id}">${d.name}</option>`;
        });
    } catch(e) {
        document.getElementById('loading').innerHTML =
            '<p class="text-danger">Error loading data.</p>';
    }
}

function renderLecturers(data) {
    const grid = document.getElementById('lecturers-grid');
    const empty = document.getElementById('empty-state');

    if (data.length === 0) {
        grid.innerHTML = '';
        empty.classList.remove('d-none');
        return;
    }

    empty.classList.add('d-none');
    const colors = ['success','primary','warning','danger','info'];
    grid.innerHTML = data.map((l, i) => `
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-user-tie text-success fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">${l.name}</h6>
                            <small class="text-muted">${l.email}</small>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted"><i class="fas fa-building me-1"></i>Department</span>
                        <span class="fw-bold">${l.department?.name ?? 'N/A'}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-3">
                        <span class="text-muted"><i class="fas fa-clock me-1"></i>Max Hours/Week</span>
                        <span class="badge bg-success">${l.max_hours_per_week}h</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary flex-grow-1"
                            onclick="openEditModal(${l.id})">
                            <i class="fas fa-edit me-1"></i>Edit
                        </button>
                        <button class="btn btn-sm btn-outline-danger flex-grow-1"
                            onclick="openDeleteModal(${l.id}, '${l.name}')">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function filterLecturers() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const filtered = lecturers.filter(l =>
        l.name.toLowerCase().includes(q) ||
        l.email.toLowerCase().includes(q) ||
        (l.department?.name ?? '').toLowerCase().includes(q)
    );
    renderLecturers(filtered);
}

function openAddModal() {
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-plus me-2"></i>Add Lecturer';
    document.getElementById('lecturer-id').value = '';
    document.getElementById('lecturer-name').value = '';
    document.getElementById('lecturer-email').value = '';
    document.getElementById('lecturer-hours').value = '';
    document.getElementById('lecturer-department').value = '';
    document.getElementById('form-error').classList.add('d-none');
    lecturerModal.show();
}

function openEditModal(id) {
    const l = lecturers.find(x => x.id === id);
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Lecturer';
    document.getElementById('lecturer-id').value = l.id;
    document.getElementById('lecturer-name').value = l.name;
    document.getElementById('lecturer-email').value = l.email;
    document.getElementById('lecturer-hours').value = l.max_hours_per_week;
    document.getElementById('lecturer-department').value = l.department_id;
    document.getElementById('form-error').classList.add('d-none');
    lecturerModal.show();
}

async function saveLecturer() {
    const id = document.getElementById('lecturer-id').value;
    const data = {
        name: document.getElementById('lecturer-name').value.trim(),
        email: document.getElementById('lecturer-email').value.trim(),
        max_hours_per_week: document.getElementById('lecturer-hours').value,
        department_id: document.getElementById('lecturer-department').value,
    };
    const errorDiv = document.getElementById('form-error');

    if (!data.name || !data.email || !data.max_hours_per_week || !data.department_id) {
        errorDiv.textContent = 'All fields are required.';
        errorDiv.classList.remove('d-none');
        return;
    }

    try {
        if (id) {
            await axios.put(`/api/lecturers/${id}`, data);
        } else {
            await axios.post('/api/lecturers', data);
        }
        lecturerModal.hide();
        loadData();
    } catch(e) {
        errorDiv.textContent = e.response?.data?.message || 'Error saving lecturer.';
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
        await axios.delete(`/api/lecturers/${deleteId}`);
        deleteModal.hide();
        loadData();
    } catch(e) {
        alert('Error deleting lecturer.');
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