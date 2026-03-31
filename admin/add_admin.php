<?php
require_once 'auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once 'head.php'?>
<body>
<div class="admin-root">
    <?php include_once 'sidebar.php'?>
    <div id="content">
        <?php include_once 'topbar.php'?>
        <div id="main-content">
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:24px;">
                <div>
                    <h1 style="font-size:22px; font-weight:700; color:#0f172a; margin:0 0 4px;">Add Admin</h1>
                    <p style="color:#64748b; font-size:13px; margin:0;">Create a new administrator account for the platform.</p>
                </div>
                <a href="admins.php" style="display:inline-flex; align-items:center; gap:8px; background:#f1f5f9; color:#2563eb; border-radius:10px; padding:10px 18px; font-size:13px; font-weight:600; text-decoration:none;">
                    <i class="fas fa-arrow-left"></i> Back to Admins
                </a>
            </div>

            <div class="content-card" style="padding:24px; max-width:760px; margin:auto;">
                <form id="addAdminForm">
                    <div style="display:grid; gap:18px;">
                        <div>
                            <label style="display:block; margin-bottom:8px; font-size:13px; font-weight:600; color:#334155;">Full Name *</label>
                            <input name="fullname" type="text" required placeholder="Enter full name" style="width:100%; padding:12px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#0f172a; background:#f8fafc;">
                        </div>
                        <div>
                            <label style="display:block; margin-bottom:8px; font-size:13px; font-weight:600; color:#334155;">Username *</label>
                            <input name="username" type="text" required placeholder="Enter username" style="width:100%; padding:12px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#0f172a; background:#f8fafc;">
                        </div>
                        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:18px;">
                            <div>
                                <label style="display:block; margin-bottom:8px; font-size:13px; font-weight:600; color:#334155;">Email *</label>
                                <input name="email" type="email" required placeholder="Enter email" style="width:100%; padding:12px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#0f172a; background:#f8fafc;">
                            </div>
                            <div>
                                <label style="display:block; margin-bottom:8px; font-size:13px; font-weight:600; color:#334155;">Password *</label>
                                <input name="password" type="password" required placeholder="Choose a password" style="width:100%; padding:12px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#0f172a; background:#f8fafc;">
                            </div>
                        </div>
                        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:18px;">
                            <div>
                                <label style="display:block; margin-bottom:8px; font-size:13px; font-weight:600; color:#334155;">Phone</label>
                                <input name="phone" type="text" placeholder="Phone number" style="width:100%; padding:12px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#0f172a; background:#f8fafc;">
                            </div>
                            <div>
                                <label style="display:block; margin-bottom:8px; font-size:13px; font-weight:600; color:#334155;">University</label>
                                <input name="otm" type="text" placeholder="University name" style="width:100%; padding:12px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#0f172a; background:#f8fafc;">
                            </div>
                            <div>
                                <label style="display:block; margin-bottom:8px; font-size:13px; font-weight:600; color:#334155;">Course</label>
                                <input name="course" type="text" placeholder="Course" style="width:100%; padding:12px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#0f172a; background:#f8fafc;">
                            </div>
                        </div>
                        <div style="display:flex; gap:12px; flex-wrap:wrap; justify-content:flex-end;">
                            <button type="button" onclick="window.location.href='admins.php'" style="background:#f8fafc; color:#1d4ed8; border:1px solid #c7d2fe; border-radius:12px; padding:12px 18px; font-weight:600; cursor:pointer;">Cancel</button>
                            <button type="submit" id="saveAdminBtn" style="background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; border:none; border-radius:12px; padding:12px 18px; font-weight:600; cursor:pointer;">Create Admin</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/perfect-scrollbar.min.js"></script>
<script>
    var ps = new PerfectScrollbar('#sidebar');

    document.getElementById('addAdminForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('saveAdminBtn');
        btn.disabled = true;
        btn.textContent = 'Creating...';

        const formData = new FormData(this);

        fetch('insert/add_admin.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Admin created', text: data.message, timer: 1800, showConfirmButton: false, position: 'top-end', toast: true });
                setTimeout(() => window.location.href = 'admins.php', 1500);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.message, position: 'top-end', toast: true, showConfirmButton: false, timer: 2500 });
                btn.disabled = false;
                btn.textContent = 'Create Admin';
            }
        })
        .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Server error occured.', position: 'top-end', toast: true, showConfirmButton: false, timer: 2500 });
            btn.disabled = false;
            btn.textContent = 'Create Admin';
        });
    });
</script>
</body>
</html>
