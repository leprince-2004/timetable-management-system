@extends('layout')
@section('page-title', 'Rooms')

@section('content')

<div class="page-header">
    <div>
        <h4 class="page-title"><i class="fas fa-door-open me-2" style="color:#ef233c"></i>Rooms</h4>
        <p class="page-subtitle">Manage all university rooms and facilities</p>
    </div>
    <button class="btn btn-danger" onclick="openAddModal()">
        <i class="fas fa-plus me-2"></i>Add Room
    </button>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #ef233c, #b01020);">
            <div class="stat-number" id="total-count">—</div>
            <div class="stat-label">Total Rooms</div>
            <i class="fas fa-door-open stat-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #4cc9f0, #2196f3);">
            <div class="stat-number" id="projector-count">—</div>
            <div class="stat-label">With Projector</div>
            <i class="fas fa-tv stat-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #7209b7, #3a0ca3);">
            <div class="stat-number" id="lab-count">—</div>
            <div class="stat-label">Laboratories</div>
            <i class="fas fa-flask stat-icon"></i>
        </div>
    </div>
</div>

{{-- Search & Filters --}}
<div class="search-card">
    <div class="row g-3">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" id="searchInput" class="form-control"
                    placeholder="Search rooms…" oninput="filterRooms()">
            </div>
        </div>
        <div class="col-md-4">
            <select id="filterBuilding" class="form-select" onchange="filterRooms()">
                <option value="">All Buildings</option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="filterType" class="form-select" onchange="filterRooms()">
                <option value="">All Types</option>
                <option value="lab">Labs Only</option>
                <option value="projector">With Projector</option>
            </select>
        </div>
    </div>
</div>

{{-- Grid --}}
<div class="row g-3" id="rooms-grid"></div>

<div id="empty-state" class="text-center py-5 d-none">
    <div class="empty-state-icon"><i class="fas fa-door-open"></i></div>
    <h6 class="text-muted mt-2">No rooms yet</h6>
    <p class="text-muted small">Click "Add Room" to get started</p>
</div>

<div id="loading" class="text-center py-5">
    <div class="spinner-border" style="color:#ef233c"></div>
    <p class="mt-2 text-muted small">Loading rooms…</p>
</div>

{{-- ADD / EDIT MODAL --}}
<div class="modal fade" id="roomModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg,#ef233c,#b01020)">
                <h5 class="modal-title text-white" id="modal-title">
                    <i class="fas fa-plus me-2"></i>Add Room
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="form-error" class="alert alert-danger d-none py-2" style="border-radius:10px;font-size:0.82rem"></div>
                <input type="hidden" id="room-id">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Room Number <span class="text-danger">*</span></label>
                        <input type="text" id="room-number" class="form-control" placeholder="e.g. A101">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Building <span class="text-danger">*</span></label>
                        <input type="text" id="room-building" class="form-control" placeholder="e.g. Block A">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Capacity <span class="text-danger">*</span></label>
                        <input type="number" id="room-capacity" class="form-control" placeholder="e.g. 50" min="1">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Features</label>
                        <div class="d-flex gap-4 mt-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="room-projector">
                                <label class="form-check-label small" for="room-projector">
                                    <i class="fas fa-tv me-1 text-info"></i>Has Projector
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="room-lab">
                                <label class="form-check-label small" for="room-lab">
                                    <i class="fas fa-flask me-1" style="color:#7209b7"></i>Is Laboratory
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger btn-sm" onclick="saveRoom()">
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
                <h5 class="modal-title text-white"><i class="fas fa-trash me-2"></i>Delete Room</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="empty-state-icon mb-3" style="background:#fff0f2;color:#ef233c">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p class="mb-1">Are you sure you want to delete room</p>
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
let rooms    = [];
let deleteId = null;
const roomModal   = new bootstrap.Modal(document.getElementById('roomModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

async function loadRooms() {
    try {
        const res = await axios.get('/api/rooms');
        rooms = res.data;
        renderRooms(rooms);
        document.getElementById('total-count').textContent     = rooms.length;
        document.getElementById('projector-count').textContent = rooms.filter(r => r.has_projector).length;
        document.getElementById('lab-count').textContent       = rooms.filter(r => r.is_lab).length;
        document.getElementById('loading').classList.add('d-none');

        const buildings = [...new Set(rooms.map(r => r.building))];
        const bSel = document.getElementById('filterBuilding');
        buildings.forEach(b => { bSel.innerHTML += `<option value="${b}">${b}</option>`; });
    } catch(e) {
        document.getElementById('loading').innerHTML = '<p class="text-danger">Error loading rooms.</p>';
    }
}

function renderRooms(data) {
    const grid  = document.getElementById('rooms-grid');
    const empty = document.getElementById('empty-state');
    if (data.length === 0) { grid.innerHTML = ''; empty.classList.remove('d-none'); return; }
    empty.classList.add('d-none');

    grid.innerHTML = data.map(r => {
        const tags = [];
        if (r.has_projector) tags.push(`<span class="badge" style="background:#e8f7ff;color:#2196f3"><i class="fas fa-tv me-1"></i>Projector</span>`);
        if (r.is_lab)        tags.push(`<span class="badge" style="background:#f3e8ff;color:#7209b7"><i class="fas fa-flask me-1"></i>Laboratory</span>`);
        if (!r.has_projector && !r.is_lab) tags.push(`<span class="badge" style="background:#f0f2f8;color:#718096"><i class="fas fa-chalkboard me-1"></i>Standard</span>`);

        const fill = Math.min(r.capacity / 100 * 100, 100);
        return `
        <div class="col-md-4 col-sm-6">
            <div class="card hover-card h-100 border-0" style="border-radius:16px">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width:50px;height:50px;border-radius:14px;
                            background:linear-gradient(135deg,#ef233c,#b01020);
                            display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="fas fa-door-open" style="color:#fff;font-size:1.1rem"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Room ${r.room_number}</h6>
                            <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>${r.building}</small>
                        </div>
                    </div>
                    <div style="border-top:1px solid #f0f2f8;padding-top:10px">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted"><i class="fas fa-users me-1"></i>Capacity</small>
                            <span class="badge" style="background:#fff0f2;color:#ef233c">${r.capacity} seats</span>
                        </div>
                        <div class="mb-2">
                            <div class="progress" style="height:5px;border-radius:3px;background:#f0f2f8">
                                <div class="progress-bar" style="width:${fill}%;background:linear-gradient(90deg,#ef233c,#f8961e);border-radius:3px"></div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-1 mb-3">${tags.join('')}</div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm flex-grow-1" onclick="openEditModal(${r.id})">
                                <i class="fas fa-edit me-1"></i>Edit
                            </button>
                            <button class="btn btn-outline-danger btn-sm flex-grow-1"
                                onclick="openDeleteModal(${r.id}, '${r.room_number}')">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');
}

function filterRooms() {
    const q        = document.getElementById('searchInput').value.toLowerCase();
    const building = document.getElementById('filterBuilding').value;
    const type     = document.getElementById('filterType').value;
    renderRooms(rooms.filter(r =>
        (r.room_number.toLowerCase().includes(q) || r.building.toLowerCase().includes(q)) &&
        (!building || r.building === building) &&
        (!type || (type === 'lab' && r.is_lab) || (type === 'projector' && r.has_projector))
    ));
}

function openAddModal() {
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-plus me-2"></i>Add Room';
    ['room-id','room-number','room-building','room-capacity'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('room-projector').checked = false;
    document.getElementById('room-lab').checked = false;
    document.getElementById('form-error').classList.add('d-none');
    roomModal.show();
}

function openEditModal(id) {
    const r = rooms.find(x => x.id === id);
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Room';
    document.getElementById('room-id').value       = r.id;
    document.getElementById('room-number').value   = r.room_number;
    document.getElementById('room-building').value = r.building;
    document.getElementById('room-capacity').value = r.capacity;
    document.getElementById('room-projector').checked = r.has_projector == 1;
    document.getElementById('room-lab').checked        = r.is_lab == 1;
    document.getElementById('form-error').classList.add('d-none');
    roomModal.show();
}

async function saveRoom() {
    const id   = document.getElementById('room-id').value;
    const data = {
        room_number:   document.getElementById('room-number').value.trim(),
        building:      document.getElementById('room-building').value.trim(),
        capacity:      document.getElementById('room-capacity').value,
        has_projector: document.getElementById('room-projector').checked ? 1 : 0,
        is_lab:        document.getElementById('room-lab').checked ? 1 : 0,
    };
    const err = document.getElementById('form-error');
    if (!data.room_number || !data.building || !data.capacity) {
        err.textContent = 'Room number, building and capacity are required.'; err.classList.remove('d-none'); return;
    }
    try {
        id ? await axios.put(`/api/rooms/${id}`, data)
           : await axios.post('/api/rooms', data);
        roomModal.hide(); loadRooms();
    } catch(e) {
        err.textContent = e.response?.data?.message || 'Error saving room.';
        err.classList.remove('d-none');
    }
}

function openDeleteModal(id, number) { deleteId = id; document.getElementById('delete-name').textContent = number; deleteModal.show(); }

async function confirmDelete() {
    try { await axios.delete(`/api/rooms/${deleteId}`); deleteModal.hide(); loadRooms(); }
    catch(e) { alert('Error deleting room.'); }
}

loadRooms();
</script>
@endsection
