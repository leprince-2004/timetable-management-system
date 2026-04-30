@extends('layout')

@section('page-title', 'Departments')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-building text-primary me-2"></i>Departments</h4>
        <p class="text-muted mb-0">Manage all university departments</p>
    </div>
    <button class="btn btn-primary px-4" onclick="openAddModal()">
        <i class="fas fa-plus me-2"></i>Add Department
    </button>
</div>

<!-- Stats Card -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 bg-primary bg-gradient text-white">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="fas fa-building fa-2x opacity-75"></i>
                <div>
                    <div class="fs-4 fw-bold" id="total-count">0</div>
                    <div class="small opacity-75">Total Departments</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Bar -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
                <i class="fas fa-search text-muted"></i>
            </span>
            <input type="text" id="searchInput" class="form-control border-start-0"
                placeholder="Search departments..." oninput="filterDepartments()">
        </div>
    </div>
</div>

<!-- Departments Grid -->
<div class="row g-3" id="departments-grid">
    <!-- Cards loaded here -->
</div>

<!-- Empty State -->
<div id="empty-state" class="text-center py-5 d-none">
    <i class="fas fa-building fa-4x text-muted mb-3"></i>
    <h5 class="text-muted">No departments yet</h5>
    <p class="text-muted">Click "Add Department" to get started</p>
</div>

<!-- Loading -->
<div id="loading" class="text-center py-5">
    <div class="spinner-border text-primary"></div>
    <p class="mt-2 text-muted">Loading departments...</p>
</div>

<!-- ADD / EDIT MODAL -->
<div class="modal fade" id="deptModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal-title">
                    <i class="fas fa-building me-2"></i>Add Department
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="form-error" class="alert alert-danger d-none"></div>
                <input type="hidden" id="dept-id">
                <div class="mb-3">
                    <label class="form-label fw-bold">Department Name <span class="text-danger">*</span></label>
                    <input type="text" id="dept-name" class="form-control form-control-lg"
                        placeholder="e.g. Computer Science">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary px-4" onclick="saveDepartment()">
                    <i class="fas fa-save me-2"></i>Save
                </button>
            </div>
        </div>
    </div>
</div>

<!-- DELETE CONFIRM MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i>Delete Department</h5>
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
let departments = [];
let deleteId = null;
const deptModal = new bootstrap.Modal(document.getElementById('deptModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

// Load all departments
async function loadDepartments() {
    try {
        const res = await axios.get('/api/departments');
        departments = res.data;
        renderDepartments(departments);
        document.getElementById('total-count').textContent = departments.length;
        document.getElementById('loading').classList.add('d-none');
    } catch(e) {
        document.getElementById('loading').innerHTML =
            '<p class="text-danger">Error loading departments.</p>';
    }
}

// Render department cards
function renderDepartments(data) {
    const grid = document.getElementById('departments-grid');
    const empty = document.getElementById('empty-state');

    if (data.length === 0) {
        grid.innerHTML = '';
        empty.classList.remove('d-none');
        return;
    }

    empty.classList.add('d-none');
    const colors = ['primary','success','warning','danger','info','secondary'];
    grid.innerHTML = data.map((d, i) => `
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-${colors[i % colors.length]} bg-opacity-10
                            p-3 me-3">
                            <i class="fas fa-building text-${colors[i % colors.length]} fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-0">${d.name}</h6>
                            <small class="text-muted">ID: #${d.id}</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-sm btn-outline-primary flex-grow-1"
                            onclick="openEditModal(${d.id}, '${d.name}')">
                            <i class="fas fa-edit me-1"></i>Edit
                        </button>
                        <button class="btn btn-sm btn-outline-danger flex-grow-1"
                            onclick="openDeleteModal(${d.id}, '${d.name}')">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

// Filter departments
function filterDepartments() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const filtered = departments.filter(d => d.name.toLowerCase().includes(q));
    renderDepartments(filtered);
}

// Open Add Modal
function openAddModal() {
    document.getElementById('modal-title').innerHTML =
        '<i class="fas fa-plus me-2"></i>Add Department';
    document.getElementById('dept-id').value = '';
    document.getElementById('dept-name').value = '';
    document.getElementById('form-error').classList.add('d-none');
    deptModal.show();
}

// Open Edit Modal
function openEditModal(id, name) {
    document.getElementById('modal-title').innerHTML =
        '<i class="fas fa-edit me-2"></i>Edit Department';
    document.getElementById('dept-id').value = id;
    document.getElementById('dept-name').value = name;
    document.getElementById('form-error').classList.add('d-none');
    deptModal.show();
}

// Save (Add or Edit)
async function saveDepartment() {
    const id = document.getElementById('dept-id').value;
    const name = document.getElementById('dept-name').value.trim();
    const errorDiv = document.getElementById('form-error');

    if (!name) {
        errorDiv.textContent = 'Department name is required.';
        errorDiv.classList.remove('d-none');
        return;
    }

    try {
        if (id) {
            await axios.put(`/api/departments/${id}`, { name });
        } else {
            await axios.post('/api/departments', { name });
        }
        deptModal.hide();
        loadDepartments();
    } catch(e) {
        errorDiv.textContent = e.response?.data?.message || 'Error saving department.';
        errorDiv.classList.remove('d-none');
    }
}

// Open Delete Modal
function openDeleteModal(id, name) {
    deleteId = id;
    document.getElementById('delete-name').textContent = name;
    deleteModal.show();
}

// Confirm Delete
async function confirmDelete() {
    try {
        await axios.delete(`/api/departments/${deleteId}`);
        deleteModal.hide();
        loadDepartments();
    } catch(e) {
        alert('Error deleting department.');
    }
}

// Init
loadDepartments();
</script>

<style>
.hover-card { transition: transform 0.2s, box-shadow 0.2s; }
.hover-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
</style>
@endsection