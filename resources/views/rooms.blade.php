@extends('layout')

@section('page-title', 'Rooms')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-door-open text-danger me-2"></i>Rooms</h4>
        <p class="text-muted mb-0">Manage all university rooms</p>
    </div>
    <button class="btn btn-danger px-4" onclick="openAddModal()">
        <i class="fas fa-plus me-2"></i>Add Room
    </button>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 bg-danger bg-gradient text-white">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="fas fa-door-open fa-2x opacity-75"></i>
                <div>
                    <div class="fs-4 fw-bold" id="total-count">0</div>
                    <div class="small opacity-75">Total Rooms</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-info bg-gradient text-white">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="fas fa-projector fa-2x opacity-75"></i>
                <div>
                    <div class="fs-4 fw-bold" id="projector-count">0</div>
                    <div class="small opacity-75">With Projector</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-secondary bg-gradient text-white">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="fas fa-flask fa-2x opacity-75"></i>
                <div>
                    <div class="fs-4 fw-bold" id="lab-count">0</div>
                    <div class="small opacity-75">Laboratories</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control border-start-0"
                        placeholder="Search rooms..." oninput="filterRooms()">
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
</div>

<!-- Rooms Grid -->
<div class="row g-3" id="rooms-grid"></div>

<!-- Empty State -->
<div id="empty-state" class="text-center py-5 d-none">
    <i class="fas fa-door-open fa-4x text-muted mb-3"></i>
    <h5 class="text-muted">No rooms yet</h5>
    <p class="text-muted">Click "Add Room" to get started</p>
</div>

<!-- Loading -->
<div id="loading" class="text-center py-5">
    <div class="spinner-border text-danger"></div>
    <p class="mt-2 text-muted">Loading rooms...</p>
</div>

<!-- ADD/EDIT MODAL -->
<div class="modal fade" id="roomModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modal-title">
                    <i class="fas fa-plus me-2"></i>Add Room
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="form-error" class="alert alert-danger d-none"></div>
                <input type="hidden" id="room-id">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Room Number <span class="text-danger">*</span></label>
                        <input type="text" id="room-number" class="form-control"
                            placeholder="e.g. A101">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Building <span class="text-danger">*</span></label>
                        <input type="text" id="room-building" class="form-control"
                            placeholder="e.g. Block A">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Capacity <span class="text-danger">*</span></label>
                        <input type="number" id="room-capacity" class="form-control"
                            placeholder="e.g. 50" min="1">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Room Type</label>
                        <div class="d-flex gap-4 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="room-projector">
                                <label class="form-check-label" for="room-projector">
                                    <i class="fas fa-projector me-1 text-info"></i>Has Projector
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="room-lab">
                                <label class="form-check-label" for="room-lab">
                                    <i class="fas fa-flask me-1 text-secondary"></i>Is Laboratory
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger px-4" onclick="saveRoom()">
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
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i>Delete Room</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <p class="fs-5">Are you sure you want to delete room <strong id="delete-name"></strong>?</p>
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
let rooms = [];
let deleteId = null;
const roomModal = new bootstrap.Modal(document.getElementById('roomModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

async function loadRooms() {
    try {
        const res = await axios.get('/api/rooms');
        rooms = res.data;
        renderRooms(rooms);
        document.getElementById('total-count').textContent = rooms.length;
        document.getElementById('projector-count').textContent =
            rooms.filter(r => r.has_projector).length;
        document.getElementById('lab-count').textContent =
            rooms.filter(r => r.is_lab).length;
        document.getElementById('loading').classList.add('d-none');

        // Fill building filter
        const buildings = [...new Set(rooms.map(r => r.building))];
        const buildingSelect = document.getElementById('filterBuilding');
        buildings.forEach(b => {
            buildingSelect.innerHTML += `<option value="${b}">${b}</option>`;
        });
    } catch(e) {
        document.getElementById('loading').innerHTML =
            '<p class="text-danger">Error loading rooms.</p>';
    }
}

function renderRooms(data) {
    const grid = document.getElementById('rooms-grid');
    const empty = document.getElementById('empty-state');

    if (data.length === 0) {
        grid.innerHTML = '';
        empty.classList.remove('d-none');
        return;
    }

    empty.classList.add('d-none');
    grid.innerHTML = data.map(r => `
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                            <i class="fas fa-door-open text-danger fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Room ${r.room_number}</h5>
                            <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>${r.building}</small>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-muted"><i class="fas fa-users me-1"></i>Capacity</span>
                        <span class="badge bg-danger">${r.capacity} seats</span>
                    </div>
                    <div class="d-flex gap-2 mb-3">
                        ${r.has_projector ?
                            `<span class="badge bg-info text-dark">
                                <i class="fas fa-tv me-1"></i>Projector
                            </span>` : ''}
                        ${r.is_lab ?
                            `<span class="badge bg-secondary">
                                <i class="fas fa-flask me-1"></i>Laboratory
                            </span>` : ''}
                        ${!r.has_projector && !r.is_lab ?
                            `<span class="badge bg-light text-dark">
                                <i class="fas fa-chalkboard me-1"></i>Standard
                            </span>` : ''}
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary flex-grow-1"
                            onclick="openEditModal(${r.id})">
                            <i class="fas fa-edit me-1"></i>Edit
                        </button>
                        <button class="btn btn-sm btn-outline-danger flex-grow-1"
                            onclick="openDeleteModal(${r.id}, '${r.room_number}')">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function filterRooms() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const building = document.getElementById('filterBuilding').value;
    const type = document.getElementById('filterType').value;

    const filtered = rooms.filter(r => {
        const matchQ = r.room_number.toLowerCase().includes(q) ||
                       r.building.toLowerCase().includes(q);
        const matchBuilding = !building || r.building === building;
        const matchType = !type ||
            (type === 'lab' && r.is_lab) ||
            (type === 'projector' && r.has_projector);
        return matchQ && matchBuilding && matchType;
    });
    renderRooms(filtered);
}

function openAddModal() {
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-plus me-2"></i>Add Room';
    document.getElementById('room-id').value = '';
    document.getElementById('room-number').value = '';
    document.getElementById('room-building').value = '';
    document.getElementById('room-capacity').value = '';
    document.getElementById('room-projector').checked = false;
    document.getElementById('room-lab').checked = false;
    document.getElementById('form-error').classList.add('d-none');
    roomModal.show();
}

function openEditModal(id) {
    const r = rooms.find(x => x.id === id);
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Room';
    document.getElementById('room-id').value = r.id;
    document.getElementById('room-number').value = r.room_number;
    document.getElementById('room-building').value = r.building;
    document.getElementById('room-capacity').value = r.capacity;
    document.getElementById('room-projector').checked = r.has_projector == 1;
    document.getElementById('room-lab').checked = r.is_lab == 1;
    document.getElementById('form-error').classList.add('d-none');
    roomModal.show();
}

async function saveRoom() {
    const id = document.getElementById('room-id').value;
    const data = {
        room_number: document.getElementById('room-number').value.trim(),
        building: document.getElementById('room-building').value.trim(),
        capacity: document.getElementById('room-capacity').value,
        has_projector: document.getElementById('room-projector').checked ? 1 : 0,
        is_lab: document.getElementById('room-lab').checked ? 1 : 0,
    };
    const errorDiv = document.getElementById('form-error');

    if (!data.room_number || !data.building || !data.capacity) {
        errorDiv.textContent = 'Room number, building and capacity are required.';
        errorDiv.classList.remove('d-none');
        return;
    }

    try {
        if (id) {
            await axios.put(`/api/rooms/${id}`, data);
        } else {
            await axios.post('/api/rooms', data);
        }
        roomModal.hide();
        loadRooms();
    } catch(e) {
        errorDiv.textContent = e.response?.data?.message || 'Error saving room.';
        errorDiv.classList.remove('d-none');
    }
}

function openDeleteModal(id, number) {
    deleteId = id;
    document.getElementById('delete-name').textContent = number;
    deleteModal.show();
}

async function confirmDelete() {
    try {
        await axios.delete(`/api/rooms/${deleteId}`);
        deleteModal.hide();
        loadRooms();
    } catch(e) {
        alert('Error deleting room.');
    }
}

loadRooms();
</script>

<style>
.hover-card { transition: transform 0.2s, box-shadow 0.2s; }
.hover-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
</style>
@endsection