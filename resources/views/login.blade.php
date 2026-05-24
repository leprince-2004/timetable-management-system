<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — AcadSchedule</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: #0a0914;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Animated blobs */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.12;
            animation: blobFloat 10s ease-in-out infinite;
            pointer-events: none;
        }
        .blob-1 { width: 650px; height: 650px; background: #4361ee; top: -200px; left: -200px; animation-delay: 0s; }
        .blob-2 { width: 550px; height: 550px; background: #7209b7; bottom: -180px; right: -180px; animation-delay: -4s; }
        .blob-3 { width: 350px; height: 350px; background: #4cc9f0; top: 40%; left: 40%; animation-delay: -7s; }

        @keyframes blobFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%       { transform: translate(20px, -25px) scale(1.04); }
            66%       { transform: translate(-15px, 15px) scale(0.97); }
        }

        /* Grid overlay */
        .bg-grid {
            position: fixed; inset: 0; pointer-events: none;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        /* Wrapper */
        .login-wrapper {
            position: relative; z-index: 10;
            width: 100%; max-width: 430px;
            padding: 16px;
        }

        /* Card */
        .login-card {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,0.55), inset 0 1px 0 rgba(255,255,255,0.06);
        }

        /* Header */
        .login-header {
            padding: 38px 36px 26px;
            text-align: center;
        }

        .login-logo {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, #4361ee, #7209b7);
            border-radius: 18px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.6rem; color: white;
            margin-bottom: 20px;
            box-shadow: 0 8px 32px rgba(67,97,238,0.55);
            animation: logoPulse 3s ease-in-out infinite;
        }

        @keyframes logoPulse {
            0%, 100% { box-shadow: 0 8px 32px rgba(67,97,238,0.55); }
            50%       { box-shadow: 0 8px 45px rgba(114,9,183,0.7); }
        }

        .login-title    { font-size: 1.55rem; font-weight: 700; color: #fff; margin-bottom: 6px; }
        .login-subtitle { font-size: 0.82rem; color: rgba(255,255,255,0.38); }

        /* Body */
        .login-body { padding: 0 36px 36px; }

        .error-box {
            background: rgba(239,35,60,0.13);
            border: 1px solid rgba(239,35,60,0.28);
            border-radius: 10px;
            padding: 11px 15px;
            color: #ff7280;
            font-size: 0.81rem;
            display: none;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
        }
        .error-box.show { display: flex; }

        .field-label {
            display: block;
            font-size: 0.72rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.8px;
            color: rgba(255,255,255,0.45);
            margin-bottom: 8px;
        }

        .input-wrap { position: relative; margin-bottom: 16px; }

        .input-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.25);
            font-size: 0.85rem;
            pointer-events: none;
        }

        .login-input {
            width: 100%;
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 13px 16px 13px 42px;
            color: #fff;
            font-size: 0.88rem;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: all 0.25s ease;
        }

        .login-input::placeholder { color: rgba(255,255,255,0.2); }

        .login-input:focus {
            border-color: #4361ee;
            background: rgba(67,97,238,0.1);
            box-shadow: 0 0 0 4px rgba(67,97,238,0.15);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #4361ee, #7209b7);
            border: none; border-radius: 12px;
            color: white;
            font-size: 0.92rem; font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 8px 28px rgba(67,97,238,0.42);
            margin-top: 6px;
            letter-spacing: 0.2px;
        }

        .btn-login:hover    { transform: translateY(-2px); box-shadow: 0 12px 38px rgba(67,97,238,0.58); }
        .btn-login:active   { transform: translateY(0); }
        .btn-login:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

        .login-footer {
            text-align: center;
            margin-top: 22px;
            font-size: 0.7rem;
            color: rgba(255,255,255,0.15);
        }

        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 20px 0;
        }

        .divider-line { flex: 1; height: 1px; background: rgba(255,255,255,0.07); }
        .divider-text { font-size: 0.7rem; color: rgba(255,255,255,0.25); white-space: nowrap; }

        .hint-box {
            background: rgba(76,201,240,0.08);
            border: 1px solid rgba(76,201,240,0.15);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.75rem;
            color: rgba(76,201,240,0.7);
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="bg-grid"></div>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h1 class="login-title">Welcome back</h1>
                <p class="login-subtitle">Sign in to AcadSchedule to continue</p>
            </div>

            <div class="login-body">
                <div class="error-box" id="error-msg">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="error-text">Invalid credentials.</span>
                </div>

                <div>
                    <label class="field-label">Email address</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" class="login-input"
                            placeholder="admin@timetable.com" autocomplete="email">
                    </div>
                </div>

                <div>
                    <label class="field-label">Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" class="login-input"
                            placeholder="••••••••" autocomplete="current-password">
                    </div>
                </div>

                <button class="btn-login" onclick="doLogin()" id="login-btn">
                    <i class="fas fa-arrow-right me-2"></i>Sign In
                </button>

                <div class="divider">
                    <div class="divider-line"></div>
                    <span class="divider-text">demo credentials</span>
                    <div class="divider-line"></div>
                </div>

                <div class="hint-box">
                    <i class="fas fa-info-circle me-1"></i>
                    admin@timetable.com &nbsp;/&nbsp; admin123
                </div>

                <div class="login-footer">
                    Academic Timetable Management System &copy; 2026
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
    function doLogin() {
        const email    = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const errorMsg = document.getElementById('error-msg');
        const errorTxt = document.getElementById('error-text');
        const btn      = document.getElementById('login-btn');

        errorMsg.classList.remove('show');

        if (!email || !password) {
            errorTxt.textContent = 'Please fill in all fields.';
            errorMsg.classList.add('show');
            return;
        }

        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin me-2"></i>Signing in…';
        btn.disabled  = true;

        if (email === 'admin@timetable.com' && password === 'admin123') {
            axios.post('/do-login', { email, password })
                .then(() => {
                    btn.innerHTML = '<i class="fas fa-check me-2"></i>Success!';
                    setTimeout(() => { window.location.href = '/dashboard'; }, 500);
                })
                .catch(() => {
                    btn.innerHTML = '<i class="fas fa-arrow-right me-2"></i>Sign In';
                    btn.disabled  = false;
                    errorTxt.textContent = 'Server error. Try again.';
                    errorMsg.classList.add('show');
                });
        } else {
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-arrow-right me-2"></i>Sign In';
                btn.disabled  = false;
                errorTxt.textContent = 'Invalid email or password.';
                errorMsg.classList.add('show');
            }, 600);
        }
    }

    document.addEventListener('keypress', e => { if (e.key === 'Enter') doLogin(); });
    </script>
</body>
</html>
