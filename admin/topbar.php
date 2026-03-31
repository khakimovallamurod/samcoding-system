<div id="topbar">
    <div style="display:flex; align-items:center; justify-content:space-between; padding: 0 20px; height: 60px;">

        <!-- Left: Mobile menu toggle + Page breadcrumb -->
        <div style="display:flex; align-items:center; gap: 12px;">
            <button onclick="openSidebar()"
                style="display:none; background:none; border:none; cursor:pointer; padding:6px; color:#64748b; border-radius:8px;"
                id="menuToggle">
                <i class="fas fa-bars" style="font-size:18px;"></i>
            </button>
            <div>
                <span style="color:#64748b; font-size:12px; font-weight:500; text-transform:uppercase; letter-spacing:0.05em;">
                    Admin Panel
                </span>
            </div>
        </div>

        <!-- Right: admin info + logout -->
        <div style="display:flex; align-items:center; gap:16px;">

            <!-- Notification bell (placeholder) -->
            <button style="position:relative; background:none; border:none; cursor:pointer; color:#64748b; padding:6px; border-radius:8px; transition: color 0.15s;"
                onmouseover="this.style.color='#1e293b'; this.style.background='#f1f5f9';"
                onmouseout="this.style.color='#64748b'; this.style.background='none';">
                <i class="fas fa-bell" style="font-size:17px;"></i>
            </button>

            <!-- Divider -->
            <div style="width:1px; height:24px; background:#e2e8f0;"></div>

            <!-- Admin user dropdown -->
            <div style="position:relative;" id="adminDropdown">
                <button onclick="toggleDropdown()" style="display:flex; align-items:center; gap:8px; background:none; border:none; cursor:pointer; padding:4px 8px; border-radius:10px; transition: background 0.15s;"
                    onmouseover="this.style.background='#f1f5f9';" onmouseout="this.style.background='none';">
                    <!-- Avatar -->
                    <div style="width:34px; height:34px; border-radius:10px; background:linear-gradient(135deg,#2563eb,#7c3aed); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <span style="color:#fff; font-size:13px; font-weight:700;">
                            <?= strtoupper(substr($_SESSION['fullname'] ?? 'A', 0, 1)) ?>
                        </span>
                    </div>
                    <div style="text-align:left;">
                        <div style="font-size:13px; font-weight:600; color:#1e293b; line-height:1.2;">
                            <?= htmlspecialchars($_SESSION['fullname'] ?? 'Admin') ?>
                        </div>
                        <div style="font-size:11px; color:#64748b; line-height:1.2; text-transform:capitalize;">
                            <?= htmlspecialchars($_SESSION['role'] ?? 'admin') ?>
                        </div>
                    </div>
                    <i class="fas fa-chevron-down" style="font-size:11px; color:#94a3b8; margin-left:2px;"></i>
                </button>

                <!-- Dropdown menu -->
                <div id="dropdownMenu" style="display:none; position:absolute; right:0; top:calc(100% + 6px); min-width:180px; background:#fff; border:1px solid #e2e8f0; border-radius:12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow:hidden; z-index:200;">
                    <div style="padding: 10px 14px 8px; border-bottom: 1px solid #f1f5f9;">
                        <div style="font-size:12px; color:#94a3b8; font-weight:500;">Signed in as</div>
                        <div style="font-size:13px; color:#1e293b; font-weight:600; margin-top:1px;">
                            <?= htmlspecialchars($_SESSION['username'] ?? 'admin') ?>
                        </div>
                    </div>
                    <a href="profile.php" style="display:flex; align-items:center; gap:8px; padding:9px 14px; color:#374151; font-size:13px; text-decoration:none; transition: background 0.15s;"
                       onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='';">
                        <i class="fas fa-user" style="width:14px; color:#64748b;"></i> My Profile
                    </a>
                    <a href="settings.php" style="display:flex; align-items:center; gap:8px; padding:9px 14px; color:#374151; font-size:13px; text-decoration:none; transition: background 0.15s;"
                       onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='';">
                        <i class="fas fa-gear" style="width:14px; color:#64748b;"></i> Settings
                    </a>
                    <div style="height:1px; background:#f1f5f9; margin: 4px 0;"></div>
                    <a href="../auth/logout.php" style="display:flex; align-items:center; gap:8px; padding:9px 14px; color:#ef4444; font-size:13px; text-decoration:none; transition: background 0.15s;"
                       onmouseover="this.style.background='#fff5f5';" onmouseout="this.style.background='';">
                        <i class="fas fa-right-from-bracket" style="width:14px;"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleDropdown() {
    const menu = document.getElementById('dropdownMenu');
    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
}
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('adminDropdown');
    if (dropdown && !dropdown.contains(e.target)) {
        document.getElementById('dropdownMenu').style.display = 'none';
    }
});

// Show mobile menu toggle on small screens
(function() {
    const toggle = document.getElementById('menuToggle');
    if (toggle) {
        const mq = window.matchMedia('(max-width: 768px)');
        const update = () => { toggle.style.display = mq.matches ? 'block' : 'none'; };
        mq.addEventListener('change', update);
        update();
    }
})();
</script>
