@extends('layout')
@section('page-title', 'Departments')

@section('content')

<div class="page-header">
    <div>
        <h4 class="page-title"><i class="fas fa-building me-2" style="color:#4361ee"></i>Departments</h4>
        <p class="page-subtitle">Manage all university departments</p>
    </div>
    <button class="btn btn-primary" onclick="openAddModal()">
        <i class="fas fa-plus me-2"></i>Add Department
    </button>
</div>

{{-- Stat --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
            <div class="stat-number" id="total-count">—</div>
            <div class="stat-label">Total Departments</div>
            <i class="fas fa-building stat-icon"></i>
        </div>
    </div>
</div>

{{-- Search --}}
<div class="search-card">
    <div class="input-group">
        <span class="input-group-text"><i class="fas fa-search"></i></span>
        <input type="text" id="searchInput" class="form-control"
            placeholder="Search departments…" oninput="filterDepartments()">
    </div>
</div>

{{-- Grid --}}
<div class="row g-3" id="departments-grid"></div>

{{-- Empty State --}}
<div id="empty-state" class="text-center py-5 d-none">
    <div class="empty-state-icon"><i class="fas fa-building"></i></div>
    <h6 class="text-muted mt-2">No departments yet</h6>
    <p class="text-muted small">Click "Add Department" to get started</p>
</div>

{{-- Loading --}}
<div id="loading" class="text-center py-5">
    <div class="spinner-border" style="color:#4361ee"></div>
    <p class="mt-2 text-muted small">Loading departments…</p>
</div>

{{-- ADD / EDIT MODAL --}}
<div class="modal fade" id="deptModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg,#4361ee,#3a0ca3)">
                <h5 class="modal-title text-white" id="modal-title">
                    <i class="fas fa-building me-2"></i>Add Department
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="form-error" class="alert alert-danger d-none py-2" style="border-radius:10px;font-size:0.82rem"></div>
                <input type="hidden" id="dept-id">
                <label class="form-label">Department Name <span class="text-danger">*</span></label>
                <input type="text" id="dept-name" class="form-control" placeholder="e.g. Computer Science">
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary btn-sm" onclick="saveDepartment()">
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
                <h5 class="modal-title text-white"><i class="fas fa-trash me-2"></i>Delete Department</h5>
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
let departments = [];
let deleteId    = null;
const deptModal   = new bootstrap.Modal(document.getElementById('deptModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

const palette = ['#4361ee','#7209b7','#2dc653','#ef233c','#f8961e','#4cc9f0','#3a0ca3','#1a7a35'];

async function loadDepartments() {
    try {
        const res   = await axios.get('/api/departments');
        departments = res.data;
        renderDepartments(departments);
        document.getElementById('total-count').textContent = departments.length;
        document.getElementById('loading').classList.add('d-none');
    } catch(e) {
        document.getElementById('loading').innerHTML = '<p class="text-danger">Error loading departments.</p>';
    }
}

function renderDepartments(data) {
    const grid  = document.getElementById('departments-grid');
    const empty = document.getElementById('empty-state');

    if (data.length === 0) { grid.innerHTML = ''; empty.classList.remove('d-none'); return; }
    empty.classList.add('d-none');

    grid.innerHTML = data.map((d, i) => {
        const color   = palette[i % palette.length];
        const initials = d.name.split(' ').map(w => w[0]).join('').substring(0,2).toUpperCase();
        return `
        <div class="col-md-4 col-sm-6">
            <div class="card hover-card h-100 border-0" style="border-radius:16px">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3 gap-3">
                        <div style="width:48px;height:48px;border-radius:14px;background:${color}1a;
                            display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <span style="font-size:1rem;font-weight:700;color:${color}">${initials}</span>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <h6 class="fw-bold mb-0 text-truncate">${d.name}</h6>
                            <small class="text-muted">Department #${d.id}</small>
                        </div>
                        <div style="width:6px;height:40px;border-radius:3px;background:${color};flex-shrink:0"></div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-outline-primary btn-sm flex-grow-1"
                            onclick="openEditModal(${d.id}, '${d.name.replace(/'/g,"\\'")}')">
                            <i class="fas fa-edit me-1"></i>Edit
                        </button>
                        <button class="btn btn-outline-danger btn-sm flex-grow-1"
                            onclick="openDeleteModal(${d.id}, '${d.name.replace(/'/g,"\\'")}')">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');
}

function filterDepartments() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    renderDepartments(departments.filter(d => d.name.toLowerCase().includes(q)));
}

function openAddModal() {
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-plus me-2"></i>Add Department';
    document.getElementById('dept-id').value   = '';
    document.getElementById('dept-name').value = '';
    document.getElementById('form-error').classList.add('d-none');
    deptModal.show();
}

function openEditModal(id, name) {
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Department';
    document.getElementById('dept-id').value   = id;
    document.getElementById('dept-name').value = name;
    document.getElementById('form-error').classList.add('d-none');
    deptModal.show();
}

async function saveDepartment() {
    const id   = document.getElementById('dept-id').value;
    const name = document.getElementById('dept-name').value.trim();
    const err  = document.getElementById('form-error');
    if (!name) { err.textContent = 'Department name is required.'; err.classList.remove('d-none'); return; }
    try {
        id ? await axios.put(`/api/departments/${id}`, { name })
           : await axios.post('/api/departments', { name });
        deptModal.hide();
        loadDepartments();
    } catch(e) {
        err.textContent = e.response?.data?.message || 'Error saving department.';
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
        await axios.delete(`/api/departments/${deleteId}`);
        deleteModal.hide();
        loadDepartments();
    } catch(e) { alert('Error deleting department.'); }
}

loadDepartments();
</script>
@endsection
