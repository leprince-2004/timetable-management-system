@extends('layout')
@section('page-title', 'Lecturers')

@section('content')

<div class="page-header">
    <div>
        <h4 class="page-title"><i class="fas fa-chalkboard-teacher me-2" style="color:#2dc653"></i>Lecturers</h4>
        <p class="page-subtitle">Manage all university lecturers</p>
    </div>
    <button class="btn btn-success" onclick="openAddModal()">
        <i class="fas fa-plus me-2"></i>Add Lecturer
    </button>
</div>

{{-- Stat --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #2dc653, #1a7a35);">
            <div class="stat-number" id="total-count">—</div>
            <div class="stat-label">Total Lecturers</div>
            <i class="fas fa-chalkboard-teacher stat-icon"></i>
        </div>
    </div>
</div>

{{-- Search --}}
<div class="search-card">
    <div class="input-group">
        <span class="input-group-text"><i class="fas fa-search"></i></span>
        <input type="text" id="searchInput" class="form-control"
            placeholder="Search by name, email or department…" oninput="filterLecturers()">
    </div>
</div>

{{-- Grid --}}
<div class="row g-3" id="lecturers-grid"></div>

<div id="empty-state" class="text-center py-5 d-none">
    <div class="empty-state-icon"><i class="fas fa-chalkboard-teacher"></i></div>
    <h6 class="text-muted mt-2">No lecturers yet</h6>
    <p class="text-muted small">Click "Add Lecturer" to get started</p>
</div>

<div id="loading" class="text-center py-5">
    <div class="spinner-border" style="color:#2dc653"></div>
    <p class="mt-2 text-muted small">Loading lecturers…</p>
</div>

{{-- ADD / EDIT MODAL --}}
<div class="modal fade" id="lecturerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg,#2dc653,#1a7a35)">
                <h5 class="modal-title text-white" id="modal-title">
                    <i class="fas fa-plus me-2"></i>Add Lecturer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="form-error" class="alert alert-danger d-none py-2" style="border-radius:10px;font-size:0.82rem"></div>
                <input type="hidden" id="lecturer-id">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="lecturer-name" class="form-control" placeholder="e.g. Dr. John Smith">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" id="lecturer-email" class="form-control" placeholder="e.g. john@university.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select id="lecturer-department" class="form-select">
                            <option value="">— Select Department —</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Max Hours / Week <span class="text-danger">*</span></label>
                        <input type="number" id="lecturer-hours" class="form-control" placeholder="e.g. 20" min="1">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success btn-sm" onclick="saveLecturer()">
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
                <h5 class="modal-title text-white"><i class="fas fa-trash me-2"></i>Delete Lecturer</h5>
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
let lecturers  = [];
let departments = [];
let deleteId   = null;
const lecturerModal = new bootstrap.Modal(document.getElementById('lecturerModal'));
const deleteModal   = new bootstrap.Modal(document.getElementById('deleteModal'));

async function loadData() {
    try {
        const [lecRes, deptRes] = await Promise.all([
            axios.get('/api/lecturers'),
            axios.get('/api/departments')
        ]);
        lecturers   = lecRes.data;
        departments = deptRes.data;
        renderLecturers(lecturers);
        document.getElementById('total-count').textContent = lecturers.length;
        document.getElementById('loading').classList.add('d-none');

        const sel = document.getElementById('lecturer-department');
        departments.forEach(d => { sel.innerHTML += `<option value="${d.id}">${d.name}</option>`; });
    } catch(e) {
        document.getElementById('loading').innerHTML = '<p class="text-danger">Error loading data.</p>';
    }
}

function renderLecturers(data) {
    const grid  = document.getElementById('lecturers-grid');
    const empty = document.getElementById('empty-state');
    if (data.length === 0) { grid.innerHTML = ''; empty.classList.remove('d-none'); return; }
    empty.classList.add('d-none');

    grid.innerHTML = data.map(l => {
        const initials = l.name.split(' ').map(w=>w[0]).join('').substring(0,2).toUpperCase();
        return `
        <div class="col-md-4 col-sm-6">
            <div class="card hover-card h-100 border-0" style="border-radius:16px">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width:50px;height:50px;border-radius:14px;
                            background: linear-gradient(135deg,#2dc653,#1a7a35);
                            display:flex;align-items:center;justify-content:center;
                            color:#fff;font-weight:700;font-size:1rem;flex-shrink:0">
                            ${initials}
                        </div>
                        <div class="min-w-0">
                            <h6 class="fw-bold mb-0 text-truncate">${l.name}</h6>
                            <small class="text-muted text-truncate d-block">${l.email}</small>
                        </div>
                    </div>
                    <div style="border-top:1px solid #f0f2f8;padding-top:12px">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted"><i class="fas fa-building me-1"></i>Department</small>
                            <small class="fw-600" style="font-weight:600">${l.department?.name ?? 'N/A'}</small>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <small class="text-muted"><i class="fas fa-clock me-1"></i>Max hrs/week</small>
                            <span class="badge" style="background:#e8faf0;color:#1a7a35">${l.max_hours_per_week}h</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm flex-grow-1" onclick="openEditModal(${l.id})">
                                <i class="fas fa-edit me-1"></i>Edit
                            </button>
                            <button class="btn btn-outline-danger btn-sm flex-grow-1"
                                onclick="openDeleteModal(${l.id}, '${l.name.replace(/'/g,"\\'")}')">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');
}

function filterLecturers() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    renderLecturers(lecturers.filter(l =>
        l.name.toLowerCase().includes(q) ||
        l.email.toLowerCase().includes(q) ||
        (l.department?.name ?? '').toLowerCase().includes(q)
    ));
}

function openAddModal() {
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-plus me-2"></i>Add Lecturer';
    ['lecturer-id','lecturer-name','lecturer-email','lecturer-hours'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('lecturer-department').value = '';
    document.getElementById('form-error').classList.add('d-none');
    lecturerModal.show();
}

function openEditModal(id) {
    const l = lecturers.find(x => x.id === id);
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Lecturer';
    document.getElementById('lecturer-id').value         = l.id;
    document.getElementById('lecturer-name').value       = l.name;
    document.getElementById('lecturer-email').value      = l.email;
    document.getElementById('lecturer-hours').value      = l.max_hours_per_week;
    document.getElementById('lecturer-department').value = l.department_id;
    document.getElementById('form-error').classList.add('d-none');
    lecturerModal.show();
}

async function saveLecturer() {
    const id   = document.getElementById('lecturer-id').value;
    const data = {
        name:              document.getElementById('lecturer-name').value.trim(),
        email:             document.getElementById('lecturer-email').value.trim(),
        max_hours_per_week: document.getElementById('lecturer-hours').value,
        department_id:     document.getElementById('lecturer-department').value,
    };
    const err = document.getElementById('form-error');
    if (!data.name || !data.email || !data.max_hours_per_week || !data.department_id) {
        err.textContent = 'All fields are required.'; err.classList.remove('d-none'); return;
    }
    try {
        id ? await axios.put(`/api/lecturers/${id}`, data)
           : await axios.post('/api/lecturers', data);
        lecturerModal.hide(); loadData();
    } catch(e) {
        err.textContent = e.response?.data?.message || 'Error saving lecturer.';
        err.classList.remove('d-none');
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
        deleteModal.hide(); loadData();
    } catch(e) { alert('Error deleting lecturer.'); }
}

loadData();
</script>
@endsection
