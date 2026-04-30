<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Timetable Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 30px;
            text-align: center;
        }
        .login-body { padding: 30px; }
        .btn-login {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-size: 1rem;
        }
        .btn-login:hover { opacity: 0.9; color: white; }
    </style>
</head>
<body>
<div class="login-card card">
    <div class="login-header">
        <i class="fas fa-calendar-alt fa-3x mb-3"></i>
        <h4>Timetable Manager</h4>
        <p class="mb-0 opacity-75">Academic Schedule System</p>
    </div>
    <div class="login-body">
        <div id="error-msg" class="alert alert-danger d-none"></div>
        <div class="mb-3">
            <label class="form-label fw-bold">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" id="email" class="form-control" placeholder="admin@example.com">
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label fw-bold">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" id="password" class="form-control" placeholder="••••••••">
            </div>
        </div>
        <button class="btn btn-login" onclick="doLogin()">
            <i class="fas fa-sign-in-alt me-2"></i>Login
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
function doLogin() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const errorMsg = document.getElementById('error-msg');

    if (!email || !password) {
        errorMsg.textContent = 'Please fill in all fields.';
        errorMsg.classList.remove('d-none');
        return;
    }

    if (email === 'admin@timetable.com' && password === 'admin123') {
        // Envoie au serveur Laravel pour créer la session
        axios.post('/do-login', { email, password })
            .then(() => {
                window.location.href = '/dashboard';
            })
            .catch(() => {
                errorMsg.textContent = 'Server error. Try again.';
                errorMsg.classList.remove('d-none');
            });
    } else {
        errorMsg.textContent = 'Invalid email or password.';
        errorMsg.classList.remove('d-none');
    }
}

document.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') doLogin();
});
</script>

</body>
</html>