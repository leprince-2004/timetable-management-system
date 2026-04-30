<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Timetable Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
            color: white;
        }
        .sidebar a {
            color: #bdc3c7;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            border-radius: 5px;
            margin: 2px 10px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #3498db;
            color: white;
        }
        .sidebar .brand {
            padding: 20px;
            font-size: 1.2rem;
            font-weight: bold;
            border-bottom: 1px solid #34495e;
            color: white;
        }
        .main-content { padding: 30px; }
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .card-header { font-weight: bold; }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar" style="width: 250px; min-width: 250px;">
        <div class="brand">
            <i class="fas fa-calendar-alt me-2"></i>
            Timetable Manager
        </div>
        <nav class="mt-3">
            <a href="/dashboard"><i class="fas fa-home me-2"></i>Dashboard</a>
            <a href="/departments"><i class="fas fa-building me-2"></i>Departments</a>
            <a href="/lecturers"><i class="fas fa-chalkboard-teacher me-2"></i>Lecturers</a>
            <a href="/courses"><i class="fas fa-book me-2"></i>Courses</a>
            <a href="/rooms"><i class="fas fa-door-open me-2"></i>Rooms</a>
            <a href="/time-slots"><i class="fas fa-clock me-2"></i>Time Slots</a>
            <a href="/student-groups"><i class="fas fa-users me-2"></i>Student Groups</a>
            <a href="/timetables"><i class="fas fa-table me-2"></i>Timetables</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1">
        <nav class="navbar navbar-light bg-white shadow-sm px-4">
            <span class="navbar-brand mb-0 h1">
                @yield('page-title', 'Dashboard')
            </span>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">
                    <i class="fas fa-user-circle me-1"></i>Admin
                </span>
                <a href="/logout" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </nav>
        <div class="main-content">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@yield('scripts')
</body>
</html>