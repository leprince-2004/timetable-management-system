@extends('layout')
@section('page-title', 'Time Slots')

@section('content')

<div class="page-header">
    <div>
        <h4 class="page-title"><i class="fas fa-clock me-2" style="color:#4cc9f0"></i>Time Slots</h4>
        <p class="page-subtitle">Manage all available time slots</p>
    </div>
    <button class="btn btn-info" onclick="openAddModal()">
        <i class="fas fa-plus me-2"></i>Add Time Slot
    </button>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #4cc9f0, #2196f3);">
            <div class="stat-number" id="total-count">—</div>
            <div class="stat-label">Total Time Slots</div>
            <i class="fas fa-clock stat-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #2dc653, #1a7a35);">
            <div class="stat-number" id="active-count">—</div>
            <div class="stat-label">Active Slots</div>
            <i class="fas fa-check-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #718096, #4a5568);">
            <div class="stat-number" id="inactive-count">—</div>
            <div class="stat-label">Inactive Slots</div>
            <i class="fas fa-times-circle stat-icon"></i>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="search-card">
    <div class="row g-3">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" id="searchInput" class="form-control"
                    placeholder="Search time slots…" oninput="filterSlots()">
            </div>
        </div>
        <div class="col-md-4">
            <select id="filterDay" class="form-select" onchange="filterSlots()">
                <option value="">All Days</option>
                <option>Monday</option><option>Tuesday</option><option>Wednesday</option>
                <option>Thursday</option><option>Friday</option><option>Saturday</option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="filterStatus" class="form-select" onchange="filterSlots()">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
    </div>
</div>

{{-- Weekly view --}}
<div id="weekly-view"></div>

<div id="empty-state" class="text-center py-5 d-none">
    <div class="empty-state-icon"><i class="fas fa-clock"></i></div>
    <h6 class="text-muted mt-2">No time slots yet</h6>
    <p class="text-muted small">Click "Add Time Slot" to get started</p>
</div>

<div id="loading" class="text-center py-5">
    <div class="spinner-border" style="color:#4cc9f0"></div>
    <p class="mt-2 text-muted small">Loading time slots…</p>
</div>

{{-- ADD / EDIT MODAL --}}
<div class="modal fade" id="slotModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg,#4cc9f0,#2196f3)">
                <h5 class="modal-title text-white" id="modal-title">
                    <i class="fas fa-plus me-2"></i>Add Time Slot
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="form-error" class="alert alert-danger d-none py-2" style="border-radius:10px;font-size:0.82rem"></div>
                <input type="hidden" id="slot-id">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Day <span class="text-danger">*</span></label>
                        <select id="slot-day" class="form-select">
                            <option value="">— Select Day —</option>
                            <option>Monday</option><option>Tuesday</option><option>Wednesday</option>
                            <option>Thursday</option><option>Friday</option><option>Saturday</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Start Time <span class="text-danger">*</span></label>
                        <input type="time" id="slot-start" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Time <span class="text-danger">*</span></label>
                        <input type="time" id="slot-end" class="form-control">
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="slot-active" checked>
                            <label class="form-check-label form-label mb-0" for="slot-active">Active</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-info btn-sm" onclick="saveSlot()">
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
                <h5 class="modal-title text-white"><i class="fas fa-trash me-2"></i>Delete Time Slot</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="empty-state-icon mb-3" style="background:#fff0f2;color:#ef233c">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p class="mb-1">Are you sure you want to delete this time slot?</p>
                <p class="text-muted small fw-bold" id="delete-name"></p>
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
let slots = [], filtered = [];
let deleteId = null;
const slotModal   = new bootstrap.Modal(document.getElementById('slotModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

const days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
const dayColors = {
    Monday:'#4361ee', Tuesday:'#2dc653', Wednesday:'#f8961e',
    Thursday:'#ef233c', Friday:'#4cc9f0', Saturday:'#7209b7'
};

async function loadSlots() {
    try {
        const res = await axios.get('/api/time-slots');
        slots    = res.data;
        filtered = slots;
        renderSlots(slots);
        updateStats(slots);
        document.getElementById('loading').classList.add('d-none');
    } catch(e) {
        document.getElementById('loading').innerHTML = '<p class="text-danger">Error loading time slots.</p>';
    }
}

function updateStats(data) {
    document.getElementById('total-count').textContent    = data.length;
    document.getElementById('active-count').textContent   = data.filter(s => s.is_active).length;
    document.getElementById('inactive-count').textContent = data.filter(s => !s.is_active).length;
}

function renderSlots(data) {
    const view  = document.getElementById('weekly-view');
    const empty = document.getElementById('empty-state');
    if (data.length === 0) { view.innerHTML = ''; empty.classList.remove('d-none'); return; }
    empty.classList.add('d-none');

    let html = '';
    days.forEach(day => {
        const daySlots = data.filter(s => s.day === day);
        if (!daySlots.length) return;
        const color = dayColors[day];

        html += `
        <div class="card border-0 mb-3" style="border-radius:16px;overflow:hidden">
            <div class="d-flex align-items-center justify-content-between px-4 py-3"
                 style="background:${color}15;border-bottom:2px solid ${color}30">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:10px;height:10px;border-radius:50%;background:${color}"></div>
                    <h6 class="mb-0 fw-bold" style="color:${color}">${day}</h6>
                    <span class="badge" style="background:${color}20;color:${color};font-size:0.7rem">
                        ${daySlots.length} slot${daySlots.length > 1 ? 's' : ''}
                    </span>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="row g-2">
                    ${daySlots.map(s => `
                    <div class="col-md-2 col-sm-3 col-4">
                        <div class="text-center p-3 rounded-3 hover-card position-relative"
                             style="background:${s.is_active ? color+'12' : '#f8faff'};
                                    border:1.5px solid ${s.is_active ? color+'40' : '#e2e8f0'};
                                    ${s.is_active ? '' : 'opacity:0.6'}">
                            <div class="fw-bold mb-0" style="color:${color};font-size:0.95rem">
                                ${s.start_time.substring(0,5)}
                            </div>
                            <div class="text-muted" style="font-size:0.65rem">to</div>
                            <div class="fw-bold" style="color:${color};font-size:0.95rem">
                                ${s.end_time.substring(0,5)}
                            </div>
                            <div class="mt-1">
                                <span class="badge" style="font-size:0.58rem;
                                    background:${s.is_active ? '#e8faf0' : '#f0f2f8'};
                                    color:${s.is_active ? '#1a7a35' : '#718096'}">
                                    ${s.is_active ? 'Active' : 'Inactive'}
                                </span>
                            </div>
                            <div class="mt-2 d-flex gap-1 justify-content-center">
                                <button class="btn btn-outline-primary"
                                    style="padding:2px 8px;font-size:0.65rem;border-radius:6px"
                                    onclick="openEditModal(${s.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger"
                                    style="padding:2px 8px;font-size:0.65rem;border-radius:6px"
                                    onclick="openDeleteModal(${s.id}, '${day} ${s.start_time.substring(0,5)}-${s.end_time.substring(0,5)}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>`).join('')}
                </div>
            </div>
        </div>`;
    });

    view.innerHTML = html;
}

function filterSlots() {
    const q      = document.getElementById('searchInput').value.toLowerCase();
    const day    = document.getElementById('filterDay').value;
    const status = document.getElementById('filterStatus').value;
    filtered = slots.filter(s =>
        (s.day.toLowerCase().includes(q) || s.start_time.includes(q) || s.end_time.includes(q)) &&
        (!day || s.day === day) &&
        (status === '' || s.is_active == status)
    );
    renderSlots(filtered);
    updateStats(filtered);
}

function openAddModal() {
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-plus me-2"></i>Add Time Slot';
    ['slot-id','slot-day','slot-start','slot-end'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('slot-active').checked = true;
    document.getElementById('form-error').classList.add('d-none');
    slotModal.show();
}

function openEditModal(id) {
    const s = slots.find(x => x.id === id);
    document.getElementById('modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Time Slot';
    document.getElementById('slot-id').value    = s.id;
    document.getElementById('slot-day').value   = s.day;
    document.getElementById('slot-start').value = s.start_time.substring(0,5);
    document.getElementById('slot-end').value   = s.end_time.substring(0,5);
    document.getElementById('slot-active').checked = s.is_active == 1;
    document.getElementById('form-error').classList.add('d-none');
    slotModal.show();
}

async function saveSlot() {
    const id   = document.getElementById('slot-id').value;
    const data = {
        day:        document.getElementById('slot-day').value,
        start_time: document.getElementById('slot-start').value,
        end_time:   document.getElementById('slot-end').value,
        is_active:  document.getElementById('slot-active').checked ? 1 : 0,
    };
    const err = document.getElementById('form-error');
    if (!data.day || !data.start_time || !data.end_time) {
        err.textContent = 'Day, start time and end time are required.'; err.classList.remove('d-none'); return;
    }
    if (data.start_time >= data.end_time) {
        err.textContent = 'End time must be after start time.'; err.classList.remove('d-none'); return;
    }
    try {
        id ? await axios.put(`/api/time-slots/${id}`, data)
           : await axios.post('/api/time-slots', data);
        slotModal.hide(); loadSlots();
    } catch(e) {
        err.textContent = e.response?.data?.message || 'Error saving time slot.';
        err.classList.remove('d-none');
    }
}

function openDeleteModal(id, label) { deleteId = id; document.getElementById('delete-name').textContent = label; deleteModal.show(); }

async function confirmDelete() {
    try { await axios.delete(`/api/time-slots/${deleteId}`); deleteModal.hide(); loadSlots(); }
    catch(e) { alert('Error deleting time slot.'); }
}

loadSlots();
</script>
@endsection
