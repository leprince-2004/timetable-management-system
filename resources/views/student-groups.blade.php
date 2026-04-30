@extends('layout')

@section('page-title', 'Student Groups')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-users text-purple me-2" style="color:#6f42c1"></i>Student Groups</h4>
        <p class="text-muted mb-0">Manage all student groups</p>
    </div>
    <button class="btn px-4 text-white" style="background:#6f42c1" onclick="openAddModal()">
        <i class="fas fa-plus me-2"></i>Add Group
    </button>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 text-white" style="background:linear-gradient(135deg,#6f42c1,#9b59b6)">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="fas fa-users fa-2x opacity-75"></i>
                <div>
                    <div class="fs-4 fw-bold" id="total-count">0</div>
                    <div class="small opacity-75">Total Groups</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-success bg-gradient text-white">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="fas fa-user-graduate fa-2x opacity-75"></i>
                <div>
                    <div class="fs-4 fw-bold" id="total-students">0</div>
                    <div class="small opacity-75">Total Students</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-info bg-gradient text-white">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="fas fa-building fa-2x opacity-75"></i>
                <div>
                    <div class="fs-4 fw-bold" id="dept-count">0</div>
                    <div class="small opacity-75">Departments</div>
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
                        placeholder="Search groups..." oninput="filterGroups()">
                </div>
            </div>
            <div class="col-md-6">
                <select id="filterDept" class="form-select" onchange="filterGroups()">
                    <option value="">All Departments</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Groups Grid -->
<div class="row g-3" id="groups-grid"></div>

<!-- Empty State -->
<div id="empty-state" class="text-center py-5 d-none">
    <i class="fas fa-users fa-4x text-muted mb-3"></i>
    <h5 class="text-muted">No student groups yet</h5>
    <p class="text-muted">Click "Add Group" to get started</p>
</div>

<!-- Loading -->
<div id="loading" class="text-center py-5">
    <div class="spinner-border" style="color:#6f42c1"></div>
    <p class="mt-2 text-muted">Loading student groups...</p>
</div>

<!-- ADD/EDIT MODAL -->
<div class="modal fade" id="groupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background:#6f42c1">
                <h5 class="modal-title" id="modal-title">
                    <i class="fas fa-plus me-2"></i>Add Student Group
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="form-error" class="alert alert-danger d-none"></div>
                <input type="hidden" id="group-id">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Group Name <span class="text-danger">*</span></label>
                        <input type="text" id="group-name" class="form-control"
                            placeholder="e.g. Group A - CS Year 2">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Department <span class="text-danger">*</span></label>
                        <select id="group-department" class="form-select">
                            <option value="">-- Select Department --</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Group Size <span class="text-danger">*</span></label>
                        <input type="number" id="group-size" class="form-control"
                            placeholder="e.g. 30" min="1">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="btn text-white px-4" style="background:#6f42c1" onclick="saveGroup()">
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
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i>Delete Group</h5>
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
let groups = [];
let departments = [];
let deleteId = null;
const groupModal = new bootstrap.Modal(document.getElementById('groupModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

async function loadData() {
    try {
        const [gRes, dRes] = await Promise.all([
            axios.get('/api/student-groups'),
            axios.get('/api/departments')
        ]);
        groups = gRes.data;
        departments = dRes.data;
        renderGroups(groups);
        updateStats(groups);
        document.getElementById('loading').classList.add('d-none');

        // Fill department dropdowns
        const deptSelect = document.getElementById('group-department');
        const filterDept = document.getElementById('filterDept');
        departments.forEach(d => {
            deptSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`;
            filterDept.innerHTML += `<option value="${d.id}">${d.name}</option>`;
        });
    } catch(e) {
        document.getElementById('loading').innerHTML =
            '<p class="text-danger">Error loading data.</p>';
    }
}

function updateStats(data) {
    document.getElementById('total-count').textContent = data.length;
    document.getElementById('total-students').textContent =
        data.reduce((sum, g) => sum + g.size, 0);
    const uniqueDepts = [...new Set(data.map(g => g.department_id))];
    document.getElementById('dept-count').textContent = uniqueDepts.length;
}

function renderGroups(data) {
    const grid = document.getElementById('groups-grid');
    const empty = document.getElementById('empty-state');

    if (data.length === 0) {
        grid.innerHTML = '';
        empty.classList.remove('d-none');
        return;
    }

    empty.classList.add('d-none');
    const colors = ['#6f42c1','#3498db','#2ecc71','#e74c3c','#f39c12','#1abc9c'];

    grid.innerHTML = data.map((g, i) => {
        const color = colors[i % colors.length];
        const initials = g.name.split(' ').map(w => w[0]).join('').substring(0,2).toUpperCase();
        return `
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center
                            text-white fw-bold me-3"
                            style="width:50px;height:50px;background:${color};font-size:1.1rem">
                            ${initials}
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">${g.name}</h6>
                            <small class="text-muted">ID: #${g.id}</small>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-muted"><i class="fas fa-building me-1"></i>Department</span>
                        <span class="fw-bold">${g.department?.name ?? 'N/A'}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-3">
                        <span class="text-muted"><i class="fas fa-users me-1"></i>Group Size</span>
                        <span class="badge text-white" style="background:${color}">
                            ${g.size} students
                        </span>
                    </div>

                    <!-- Progress bar showing size -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Capacity</span>
                            <span>${g.size}/50</span>
                        </div>
                        <div class="progress" style="height:6px">
                            <div class="progress-bar" style="width:${Math.min(g.size/50*100,100)}%;
                                background:${color}"></div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary flex-grow-1"
                            onclick="openEditModal(${g.id})">
                            <i class="fas fa-edit me-1"></i>Edit
                        </button>
                        <button class="btn btn-sm btn-outline-danger flex-grow-1"
                            onclick="openDeleteModal(${g.id}, '${g.name}')">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');
}

function filterGroups() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const dept = document.getElementById('filterDept').value;

    const filtered = groups.filter(g => {
        const matchQ = g.name.toLowerCase().includes(q);
        const matchDept = !dept || g.department_id == dept;
        return matchQ && matchDept;
    });
    renderGroups(filtered);
    updateStats(filtered);
}

function openAddModal() {
    document.getElementById('modal-title').innerHTML =
        '<i class="fas fa-plus me-2"></i>Add Student Group';
    document.getElementById('group-id').value = '';
    document.getElementById('group-name').value = '';
    document.getElementById('group-department').value = '';
    document.getElementById('group-size').value = '';
    document.getElementById('form-error').classList.add('d-none');
    groupModal.show();
}

function openEditModal(id) {
    const g = groups.find(x => x.id === id);
    document.getElementById('modal-title').innerHTML =
        '<i class="fas fa-edit me-2"></i>Edit Student Group';
    document.getElementById('group-id').value = g.id;
    document.getElementById('group-name').value = g.name;
    document.getElementById('group-department').value = g.department_id;
    document.getElementById('group-size').value = g.size;
    document.getElementById('form-error').classList.add('d-none');
    groupModal.show();
}

async function saveGroup() {
    const id = document.getElementById('group-id').value;
    const data = {
        name: document.getElementById('group-name').value.trim(),
        department_id: document.getElementById('group-department').value,
        size: document.getElementById('group-size').value,
    };
    const errorDiv = document.getElementById('form-error');

    if (!data.name || !data.department_id || !data.size) {
        errorDiv.textContent = 'All fields are required.';
        errorDiv.classList.remove('d-none');
        return;
    }

    try {
        if (id) {
            await axios.put(`/api/student-groups/${id}`, data);
        } else {
            await axios.post('/api/student-groups', data);
        }
        groupModal.hide();
        loadData();
    } catch(e) {
        errorDiv.textContent = e.response?.data?.message || 'Error saving group.';
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
        await axios.delete(`/api/student-groups/${deleteId}`);
        deleteModal.hide();
        loadData();
    } catch(e) {
        alert('Error deleting group.');
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