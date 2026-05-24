@extends('layout')
@section('page-title', 'Student Groups')

@section('content')

<div class="page-header">
    <div>
        <h4 class="page-title"><i class="fas fa-users me-2" style="color:#7209b7"></i>Student Groups</h4>
        <p class="page-subtitle">Manage all student groups</p>
    </div>
    <button class="btn" style="background:linear-gradient(135deg,#7209b7,#3a0ca3);color:#fff;
        box-shadow:0 4px 14px rgba(114,9,183,0.35)" onclick="openAddModal()">
        <i class="fas fa-plus me-2"></i>Add Group
    </button>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #7209b7, #3a0ca3);">
            <div class="stat-number" id="total-count">—</div>
            <div class="stat-label">Total Groups</div>
            <i class="fas fa-users stat-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #2dc653, #1a7a35);">
            <div class="stat-number" id="total-students">—</div>
            <div class="stat-label">Total Students</div>
            <i class="fas fa-user-graduate stat-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #4cc9f0, #2196f3);">
            <div class="stat-number" id="dept-count">—</div>
            <div class="stat-label">Departments Covered</div>
            <i class="fas fa-building stat-icon"></i>
        </div>
    </div>
</div>

{{-- Search & Filter --}}
<div class="search-card">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" id="searchInput" class="form-control"
                    placeholder="Search groups…" oninput="filterGroups()">
            </div>
        </div>
        <div class="col-md-6">
            <select id="filterDept" class="form-select" onchange="filterGroups()">
                <option value="">All Departments</option>
            </select>
        </div>
    </div>
</div>

{{-- Grid --}}
<div class="row g-3" id="groups-grid"></div>

<div id="empty-state" class="text-center py-5 d-none">
    <div class="empty-state-icon"><i class="fas fa-users"></i></div>
    <h6 class="text-muted mt-2">No student groups yet</h6>
    <p class="text-muted small">Click "Add Group" to get started</p>
</div>

<div id="loading" class="text-center py-5">
    <div class="spinner-border" style="color:#7209b7"></div>
    <p class="mt-2 text-muted small">Loading student groups…</p>
</div>

{{-- ADD / EDIT MODAL --}}
<div class="modal fade" id="groupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg,#7209b7,#3a0ca3)">
                <h5 class="modal-title text-white" id="modal-title">
                    <i class="fas fa-plus me-2"></i>Add Student Group
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="form-error" class="alert alert-danger d-none py-2" style="border-radius:10px;font-size:0.82rem"></div>
                <input type="hidden" id="group-id">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Group Name <span class="text-danger">*</span></label>
                        <input type="text" id="group-name" class="form-control" placeholder="e.g. Group A - CS Year 2">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select id="group-department" class="form-select">
                            <option value="">— Select Department —</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Group Size <span class="text-danger">*</span></label>
                        <input type="number" id="group-size" class="form-control" placeholder="e.g. 30" min="1">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-sm text-white" style="background:linear-gradient(135deg,#7209b7,#3a0ca3)"
                    onclick="saveGroup()">
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
                <h5 class="modal-title text-white"><i class="fas fa-trash me-2"></i>Delete Group</h5>
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
let groups = [], departments = [];
let deleteId = null;
const groupModal  = new bootstrap.Modal(document.getElementById('groupModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

const palette = ['#7209b7','#4361ee','#2dc653','#ef233c','#f8961e','#4cc9f0','#3a0ca3','#1a7a35'];

async function loadData() {
    try {
        const [gRes, dRes] = await Promise.all([
            axios.get('/api/student-groups'), axios.get('/api/departments')
        ]);
        groups      = gRes.data;
        departments = dRes.data;
        renderGroups(groups);
        updateStats(groups);
        document.getElementById('loading').classList.add('d-none');

        const dSel  = document.getElementById('group-department');
        const fDept = document.getElementById('filterDept');
        departments.forEach(d => {
            dSel.innerHTML  += `<option value="${d.id}">${d.name}</option>`;
            fDept.innerHTML += `<option value="${d.id}">${d.name}</option>`;
        });
    } catch(e) {
        document.getElementById('loading').innerHTML = '<p class="text-danger">Error loading data.</p>';
    }
}

function updateStats(data) {
    document.getElementById('total-count').textContent    = data.length;
    document.getElementById('total-students').textContent = data.reduce((s, g) => s + g.size, 0);
    document.getElementById('dept-count').textContent     = new Set(data.map(g => g.department_id)).size;
}

function renderGroups(data) {
    const grid  = document.getElementById('groups-grid');
    const empty = document.getElementById('empty-state');
    if (data.length === 0) { grid.innerHTML = ''; empty.classList.remove('d-none'); return; }
    empty.classList.add('d-none');

    grid.innerHTML = data.map((g, i) => {
        const color    = palette[i % palette.length];
        const initials = g.name.split(' ').map(w => w[0]).join('').substring(0,2).toUpperCase();
        const pct      = Math.min(g.size / 50 * 100, 100);
        return `
        <div class="col-md-4 col-sm-6">
            <div class="card hover-card h-100 border-0" style="border-radius:16px">
                <div style="height:4px;background:linear-gradient(90deg,${color},${color}88)"></div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width:50px;height:50px;border-radius:14px;
                            background:${color};display:flex;align-items:center;
                            justify-content:center;color:#fff;font-weight:700;
                            font-size:1.05rem;flex-shrink:0">
                            ${initials}
                        </div>
                        <div class="min-w-0">
                            <h6 class="fw-bold mb-0 text-truncate">${g.name}</h6>
                            <small class="text-muted">Group #${g.id}</small>
                        </div>
                    </div>
                    <div style="border-top:1px solid #f0f2f8;padding-top:10px">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted"><i class="fas fa-building me-1"></i>Department</small>
                            <small class="fw-bold text-truncate ms-2" style="max-width:55%">
                                ${g.department?.name ?? 'N/A'}
                            </small>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted"><i class="fas fa-users me-1"></i>Group size</small>
                            <span class="badge" style="background:${color}18;color:${color}">
                                ${g.size} students
                            </span>
                        </div>
                        <div class="mb-3">
                            <div class="progress" style="height:5px;border-radius:3px;background:#f0f2f8">
                                <div class="progress-bar" style="width:${pct}%;background:${color};border-radius:3px"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted" style="font-size:0.65rem">Capacity</small>
                                <small class="text-muted" style="font-size:0.65rem">${g.size}/50</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm flex-grow-1" onclick="openEditModal(${g.id})">
                                <i class="fas fa-edit me-1"></i>Edit
                            </button>
                            <button class="btn btn-outline-danger btn-sm flex-grow-1"
                                onclick="openDeleteModal(${g.id}, '${g.name.replace(/'/g,"\\'")}')">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');
}

function filterGroups() {
    const q    = document.getElementById('searchInput').value.toLowerCase();
    const dept = document.getElementById('filterDept').value;
    const res  = groups.filter(g =>
        g.name.toLowerCase().includes(q) && (!dept || g.department_id == dept)
    );
    renderGroups(res);
    updateStats(res);
}

function openAddModal() {
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-plus me-2"></i>Add Student Group';
    ['group-id','group-name','group-size'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('group-department').value = '';
    document.getElementById('form-error').classList.add('d-none');
    groupModal.show();
}

function openEditModal(id) {
    const g = groups.find(x => x.id === id);
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Student Group';
    document.getElementById('group-id').value         = g.id;
    document.getElementById('group-name').value       = g.name;
    document.getElementById('group-department').value = g.department_id;
    document.getElementById('group-size').value       = g.size;
    document.getElementById('form-error').classList.add('d-none');
    groupModal.show();
}

async function saveGroup() {
    const id   = document.getElementById('group-id').value;
    const data = {
        name:          document.getElementById('group-name').value.trim(),
        department_id: document.getElementById('group-department').value,
        size:          document.getElementById('group-size').value,
    };
    const err = document.getElementById('form-error');
    if (!data.name || !data.department_id || !data.size) {
        err.textContent = 'All fields are required.'; err.classList.remove('d-none'); return;
    }
    try {
        id ? await axios.put(`/api/student-groups/${id}`, data)
           : await axios.post('/api/student-groups', data);
        groupModal.hide(); loadData();
    } catch(e) {
        err.textContent = e.response?.data?.message || 'Error saving group.';
        err.classList.remove('d-none');
    }
}

function openDeleteModal(id, name) { deleteId = id; document.getElementById('delete-name').textContent = name; deleteModal.show(); }

async function confirmDelete() {
    try { await axios.delete(`/api/student-groups/${deleteId}`); deleteModal.hide(); loadData(); }
    catch(e) { alert('Error deleting group.'); }
}

loadData();
</script>
@endsection
