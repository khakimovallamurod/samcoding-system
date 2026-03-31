<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SamCoding — Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #e0e7ff 0%, #f0f9ff 40%, #e0f2fe 70%, #ede9fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        /* Decorative blobs */
        .blob1 {
            position: fixed; top: -100px; left: -100px; width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(99,102,241,0.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .blob2 {
            position: fixed; bottom: -80px; right: -80px; width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(37,99,235,0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        .blob3 {
            position: fixed; top: 40%; left: 60%; width: 250px; height: 250px;
            background: radial-gradient(circle, rgba(139,92,246,0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.9);
            border-radius: 24px;
            padding: 40px 36px;
            width: 100%;
            max-width: 420px;
            box-shadow:
                0 4px 6px -1px rgba(0,0,0,0.04),
                0 20px 60px -8px rgba(37,99,235,0.12),
                0 0 0 1px rgba(99,102,241,0.05);
        }

        .brand-icon {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, #6366f1, #2563eb);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 20px rgba(99,102,241,0.3);
            margin: 0 auto 14px;
        }

        .input-group {
            position: relative;
            margin-bottom: 14px;
        }
        .input-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: #94a3b8; font-size: 14px; pointer-events: none;
        }
        .form-input {
            width: 100%;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 40px 12px 40px;
            font-size: 14px;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.2s;
            outline: none;
        }
        .form-input:focus {
            border-color: #6366f1;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
        }
        .form-input::placeholder { color: #94a3b8; }

        .toggle-pass {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            color: #94a3b8; cursor: pointer; background: none; border: none;
            transition: color 0.15s;
        }
        .toggle-pass:hover { color: #475569; }

        .label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #6366f1, #2563eb);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            box-shadow: 0 4px 14px rgba(99,102,241,0.35);
            margin-top: 6px;
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99,102,241,0.45);
        }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

        .alert {
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 16px;
            display: none;
            align-items: center;
            gap: 8px;
        }
        .alert-error { background: #fff5f5; border: 1px solid #fecaca; color: #dc2626; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }

        @keyframes spin { to { transform: rotate(360deg); } }
        .spin { animation: spin 0.8s linear infinite; }

        @media (max-width: 480px) {
            .login-card { padding: 28px 20px; border-radius: 20px; }
        }
    </style>
</head>
<body>
    <div class="blob1"></div>
    <div class="blob2"></div>
    <div class="blob3"></div>

    <div class="login-card">

        <!-- Brand inside card -->
        <div style="text-align:center; margin-bottom:28px;">
            <div class="brand-icon">
                <i class="fas fa-terminal" style="color:#fff; font-size:22px;"></i>
            </div>
            <h1 style="font-size:22px; font-weight:800; color:#0f172a; margin-bottom:4px; letter-spacing:-0.3px;">
                SamCoding
            </h1>
            <span style="display:inline-flex; align-items:center; gap:5px; background:#eef2ff; color:#4f46e5; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; border-radius:99px; padding:3px 10px;">
                <i class="fas fa-shield-halved" style="font-size:10px;"></i> Admin Panel
            </span>
            <p style="color:#64748b; font-size:13px; margin-top:10px;">
                Sign in to manage the platform
            </p>
        </div>

        <!-- Alert box -->
        <div class="alert" id="alertBox">
            <i class="fas fa-circle-exclamation"></i>
            <span id="alertText"></span>
        </div>

        <form id="loginForm" autocomplete="off">
            <!-- Username -->
            <div style="margin-bottom:16px;">
                <label class="label" for="username">Username</label>
                <div class="input-group" style="margin-bottom:0;">
                    <i class="fas fa-user input-icon"></i>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-input"
                        placeholder="Enter your username"
                        required
                        autocomplete="username"
                    >
                </div>
            </div>

            <!-- Password -->
            <div style="margin-bottom:22px;">
                <label class="label" for="password">Password</label>
                <div class="input-group" style="margin-bottom:0;">
                    <i class="fas fa-lock input-icon"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" class="toggle-pass" id="togglePass">
                        <i class="fas fa-eye" id="passIcon" style="font-size:14px;"></i>
                    </button>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-submit" id="submitBtn">
                <span id="btnLabel">Sign In</span>
                <i class="fas fa-arrow-right" id="btnArrow" style="font-size:13px;"></i>
                <svg id="btnSpinner" class="spin" style="display:none; width:16px; height:16px;" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10" stroke="rgba(255,255,255,0.3)" stroke-width="3"/>
                    <path d="M12 2a10 10 0 0 1 10 10" stroke="#fff" stroke-width="3" stroke-linecap="round"/>
                </svg>
            </button>
        </form>

        <!-- Footer hint -->
        <p style="text-align:center; color:#94a3b8; font-size:12px; margin-top:22px; line-height:1.5;">
            <i class="fas fa-lock" style="margin-right:4px; font-size:11px;"></i>
            Restricted access. Admins only.
        </p>

    </div>

    <script>
        // Toggle password
        document.getElementById('togglePass').addEventListener('click', function () {
            const inp  = document.getElementById('password');
            const icon = document.getElementById('passIcon');
            const show = inp.type === 'password';
            inp.type   = show ? 'text' : 'password';
            icon.className = show ? 'fas fa-eye-slash' : 'fas fa-eye';
            icon.style.fontSize = '14px';
        });

        function showAlert(msg, type) {
            const box  = document.getElementById('alertBox');
            const text = document.getElementById('alertText');
            text.textContent = msg;
            box.className = 'alert alert-' + type;
            box.style.display = 'flex';
        }
        function hideAlert() {
            document.getElementById('alertBox').style.display = 'none';
        }

        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault();
            hideAlert();

            const btn   = document.getElementById('submitBtn');
            const lbl   = document.getElementById('btnLabel');
            const arrow = document.getElementById('btnArrow');
            const spin  = document.getElementById('btnSpinner');

            btn.disabled = true;
            lbl.textContent = 'Signing in...';
            arrow.style.display = 'none';
            spin.style.display  = 'block';

            fetch('login-check.php', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                lbl.textContent = 'Sign In';
                arrow.style.display = 'block';
                spin.style.display  = 'none';

                if (data.error === 0) {
                    showAlert(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect === 'admin'
                            ? '../admin/index.php'
                            : '../index.php';
                    }, 700);
                } else {
                    document.getElementById('password').value = '';
                    showAlert(data.message, 'error');
                }
            })
            .catch(() => {
                btn.disabled = false;
                lbl.textContent = 'Sign In';
                arrow.style.display = 'block';
                spin.style.display  = 'none';
                showAlert('Connection error. Please try again.', 'error');
            });
        });
    </script>
</body>
</html>
