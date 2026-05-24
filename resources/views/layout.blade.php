<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Dashboard') — AcadSchedule</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary:        #4361ee;
            --primary-dark:   #3a0ca3;
            --secondary:      #7209b7;
            --accent:         #4cc9f0;
            --success:        #2dc653;
            --warning:        #f8961e;
            --danger:         #ef233c;
            --sidebar-bg:     #0f0e17;
            --sidebar-text:   rgba(255,255,255,0.6);
            --body-bg:        #f0f2f8;
            --card-shadow:    0 4px 24px rgba(67,97,238,0.08);
            --transition:     all 0.25s ease;
        }

        * { font-family: 'Poppins', sans-serif; box-sizing: border-box; }
        body { background: var(--body-bg); color: #2d3748; margin: 0; }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 255px;
            min-width: 255px;
            min-height: 100vh;
            height: 100vh;
            position: sticky;
            top: 0;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            box-shadow: 4px 0 30px rgba(0,0,0,0.25);
            z-index: 100;
        }

        .sidebar-brand {
            padding: 22px 18px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .brand-logo {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.1rem;
            box-shadow: 0 4px 18px rgba(67,97,238,0.45);
            flex-shrink: 0;
        }

        .brand-name  { font-size: 0.95rem; font-weight: 700; color: #fff; line-height: 1.2; }
        .brand-sub   { font-size: 0.6rem; color: rgba(255,255,255,0.35); text-transform: uppercase; letter-spacing: 1.2px; }

        .sidebar-user {
            padding: 14px 18px;
            display: flex; align-items: center; gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .user-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), var(--primary));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 0.8rem; font-weight: 700;
            flex-shrink: 0;
        }

        .user-name { font-size: 0.82rem; font-weight: 600; color: #fff; line-height: 1.2; }
        .user-role { font-size: 0.62rem; color: rgba(255,255,255,0.35); }

        .online-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--success);
            box-shadow: 0 0 6px var(--success);
            margin-left: auto; flex-shrink: 0;
        }

        /* ── NAV ── */
        .sidebar-nav { padding: 10px 10px; flex: 1; }

        .nav-label {
            font-size: 0.58rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 1.5px;
            color: rgba(255,255,255,0.2);
            padding: 14px 10px 5px;
        }

        .nav-link-item {
            display: flex; align-items: center; gap: 11px;
            padding: 10px 12px;
            border-radius: 10px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.84rem; font-weight: 500;
            transition: var(--transition);
            margin-bottom: 2px;
        }

        .nav-link-item:hover {
            background: rgba(255,255,255,0.06);
            color: #fff;
            transform: translateX(3px);
        }

        .nav-link-item.active {
            background: linear-gradient(90deg, rgba(67,97,238,0.85), rgba(114,9,183,0.55));
            color: #fff;
            box-shadow: 0 3px 14px rgba(67,97,238,0.3);
        }

        .nav-icon {
            width: 30px; height: 30px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
            background: rgba(255,255,255,0.05);
            flex-shrink: 0;
            transition: var(--transition);
        }

        .nav-link-item.active .nav-icon { background: rgba(255,255,255,0.15); }
        .nav-link-item:hover  .nav-icon { background: rgba(255,255,255,0.09); }

        .sidebar-footer {
            padding: 12px 10px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .logout-link {
            display: flex; align-items: center; gap: 11px;
            padding: 10px 12px;
            border-radius: 10px;
            color: rgba(255,255,255,0.4);
            text-decoration: none;
            font-size: 0.84rem; font-weight: 500;
            transition: var(--transition);
        }

        .logout-link:hover { background: rgba(239,35,60,0.12); color: #ef233c; }

        /* ── TOPBAR ── */
        .topbar {
            background: #fff;
            height: 65px;
            padding: 0 28px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 2px 20px rgba(0,0,0,0.05);
            position: sticky; top: 0; z-index: 50;
        }

        .topbar-title    { font-size: 1.1rem; font-weight: 700; color: #1a1a2e; margin: 0; }
        .topbar-subtitle { font-size: 0.7rem; color: #a0aec0; margin: 0; }

        .topbar-badge {
            font-size: 0.78rem; color: #718096;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 6px 14px;
        }

        /* ── MAIN CONTENT ── */
        .main-content { padding: 26px; }

        /* ── CARDS ── */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .card:hover { box-shadow: 0 8px 30px rgba(67,97,238,0.12); }

        /* Stat cards */
        .stat-card {
            border-radius: 16px;
            padding: 22px 20px;
            color: white;
            position: relative;
            overflow: hidden;
            border: none;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -25px; right: -25px;
            width: 110px; height: 110px;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            bottom: -30px; right: 30px;
            width: 75px; height: 75px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }

        .stat-number { font-size: 2rem; font-weight: 700; line-height: 1; }
        .stat-label  { font-size: 0.75rem; opacity: 0.75; margin-top: 4px; }
        .stat-icon   {
            position: absolute; right: 20px; bottom: 16px;
            font-size: 2.2rem; opacity: 0.18;
        }

        /* ── PAGE HEADER ── */
        .page-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 24px;
        }

        .page-title    { font-size: 1.35rem; font-weight: 700; color: #1a1a2e; margin: 0; }
        .page-subtitle { font-size: 0.78rem; color: #a0aec0; margin: 2px 0 0; }

        /* ── BUTTONS ── */
        .btn {
            border-radius: 10px; font-weight: 500;
            font-size: 0.85rem; padding: 9px 20px;
            transition: var(--transition); border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 14px rgba(67,97,238,0.35);
        }
        .btn-primary:hover  { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(67,97,238,0.45); color: white; }

        .btn-success {
            background: linear-gradient(135deg, #2dc653, #1a7a35);
            color: white;
            box-shadow: 0 4px 14px rgba(45,198,83,0.3);
        }
        .btn-success:hover  { transform: translateY(-2px); color: white; }

        .btn-danger  {
            background: linear-gradient(135deg, var(--danger), #b01020);
            color: white;
            box-shadow: 0 4px 14px rgba(239,35,60,0.3);
        }
        .btn-danger:hover   { transform: translateY(-2px); color: white; }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning), #c97a08);
            color: white !important;
            box-shadow: 0 4px 14px rgba(248,150,30,0.3);
        }
        .btn-warning:hover  { transform: translateY(-2px); color: white !important; }

        .btn-info    {
            background: linear-gradient(135deg, var(--accent), #2196f3);
            color: white !important;
            box-shadow: 0 4px 14px rgba(76,201,240,0.3);
        }
        .btn-info:hover     { transform: translateY(-2px); color: white !important; }

        .btn-outline-primary { border: 1.5px solid var(--primary); color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); color: white; transform: translateY(-1px); }

        .btn-outline-danger  { border: 1.5px solid var(--danger); color: var(--danger); }
        .btn-outline-danger:hover  { background: var(--danger); color: white; transform: translateY(-1px); }

        .btn-outline-dark { border: 1.5px solid #2d3748; color: #2d3748; }
        .btn-outline-dark:hover { background: #2d3748; color: white; }

        .btn-outline-secondary { border: 1.5px solid #a0aec0; color: #718096; }
        .btn-outline-secondary:hover { background: #f7fafc; color: #4a5568; }

        /* ── FORM CONTROLS ── */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 10px 14px;
            font-size: 0.85rem;
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67,97,238,0.12);
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            background: #f7fafc;
            color: #a0aec0;
        }

        .input-group .form-control { border-radius: 0 10px 10px 0; }

        .form-label {
            font-size: 0.78rem; font-weight: 600;
            color: #4a5568; margin-bottom: 6px;
        }

        /* ── MODALS ── */
        .modal-content {
            border-radius: 20px; border: none;
            box-shadow: 0 25px 70px rgba(0,0,0,0.18);
        }

        .modal-header {
            border-radius: 20px 20px 0 0;
            padding: 20px 24px; border-bottom: none;
        }

        .modal-body   { padding: 22px 24px; }

        .modal-footer {
            padding: 14px 24px;
            border-top: 1px solid #f0f2f5;
            border-radius: 0 0 20px 20px;
        }

        /* ── TABLE ── */
        .table th {
            font-size: 0.74rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .table td { font-size: 0.85rem; vertical-align: middle; }

        /* ── BADGE ── */
        .badge {
            border-radius: 6px; font-weight: 500;
            font-size: 0.7rem; padding: 4px 8px;
        }

        /* ── HOVER CARD ── */
        .hover-card { transition: transform 0.25s ease, box-shadow 0.25s ease; }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(67,97,238,0.14) !important;
        }

        /* ── SEARCH CARD ── */
        .search-card {
            background: white;
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            padding: 16px 20px;
            margin-bottom: 22px;
        }

        /* ── SPINNER ── */
        .spinner-border { width: 2rem; height: 2rem; border-width: 3px; }

        /* ── EMPTY STATE ── */
        .empty-state-icon {
            width: 80px; height: 80px;
            border-radius: 20px;
            background: #edf2ff;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 2rem; color: #7a90e0; margin-bottom: 16px;
        }
    </style>
</head>
<body>
<div class="d-flex">

    {{-- ── SIDEBAR ── --}}
    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo"><i class="fas fa-calendar-alt"></i></div>
            <div>
                <div class="brand-name">AcadSchedule</div>
                <div class="brand-sub">Timetable System</div>
            </div>
        </div>

        <div class="sidebar-user">
            <div class="user-avatar">AD</div>
            <div>
                <div class="user-name">Administrator</div>
                <div class="user-role">System Admin</div>
            </div>
            <div class="online-dot"></div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Overview</div>
            <a href="/dashboard" class="nav-link-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-home"></i></div>Dashboard
            </a>

            <div class="nav-label">Management</div>
            <a href="/departments" class="nav-link-item {{ request()->is('departments') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-building"></i></div>Departments
            </a>
            <a href="/lecturers" class="nav-link-item {{ request()->is('lecturers') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-chalkboard-teacher"></i></div>Lecturers
            </a>
            <a href="/courses" class="nav-link-item {{ request()->is('courses') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-book"></i></div>Courses
            </a>
            <a href="/rooms" class="nav-link-item {{ request()->is('rooms') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-door-open"></i></div>Rooms
            </a>

            <div class="nav-label">Scheduling</div>
            <a href="/time-slots" class="nav-link-item {{ request()->is('time-slots') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-clock"></i></div>Time Slots
            </a>
            <a href="/student-groups" class="nav-link-item {{ request()->is('student-groups') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-users"></i></div>Student Groups
            </a>
            <a href="/timetables" class="nav-link-item {{ request()->is('timetables') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-table"></i></div>Timetables
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="/logout" class="logout-link">
                <div class="nav-icon" style="background:rgba(239,35,60,0.1)">
                    <i class="fas fa-sign-out-alt" style="color:#ef233c"></i>
                </div>
                Sign Out
            </a>
        </div>
    </div>

    {{-- ── MAIN AREA ── --}}
    <div class="flex-grow-1 d-flex flex-column" style="min-width:0">
        <div class="topbar">
            <div>
                <p class="topbar-title">@yield('page-title', 'Dashboard')</p>
                <p class="topbar-subtitle">Academic Timetable Management System</p>
            </div>
            <div class="topbar-badge">
                <i class="fas fa-calendar-day me-2"></i>
                <span id="current-date"></span>
            </div>
        </div>

        <div class="main-content flex-grow-1">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.getElementById('current-date').textContent =
        new Date().toLocaleDateString('en-US', { weekday:'short', month:'short', day:'numeric', year:'numeric' });
</script>
@yield('scripts')
</body>
</html>
