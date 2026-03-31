<?php
// Auth is guaranteed by auth_check.php included in the parent page.
// We only need to detect the current page for active highlighting.
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Mobile Overlay -->
<div id="sidebarOverlay" onclick="closeSidebar()"></div>

<nav id="sidebar">
    <!-- Brand -->
    <div style="padding: 20px 16px 16px; border-bottom: 1px solid #1e293b;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 36px; height: 36px; background: linear-gradient(135deg,#2563eb,#1d4ed8); border-radius: 10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="fas fa-code" style="color:#fff; font-size:15px;"></i>
            </div>
            <div>
                <div style="color:#f1f5f9; font-weight:700; font-size:15px; line-height:1.2;">SamCoding</div>
                <div style="color:#475569; font-size:11px; font-weight:500;">Admin Panel</div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div style="flex: 1; padding: 8px 0;">

        <div class="nav-section">Main</div>

        <a href="index.php" class="nav-item <?= $current_page === 'index.php' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fas fa-gauge-high"></i></span>
            Dashboard
        </a>

        <div class="nav-section">Content</div>

        <a href="problems.php" class="nav-item <?= $current_page === 'problems.php' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fas fa-code"></i></span>
            Problems
        </a>

        <a href="add_problem.php" class="nav-item <?= $current_page === 'add_problem.php' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fas fa-circle-plus"></i></span>
            Add Problem
        </a>

        <a href="olympiads.php" class="nav-item <?= $current_page === 'olympiads.php' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fas fa-trophy"></i></span>
            Olympiads
        </a>

        <div class="nav-section">Monitor</div>

        <a href="submissions.php" class="nav-item <?= $current_page === 'submissions.php' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fas fa-paper-plane"></i></span>
            Submissions
        </a>

        <a href="users.php" class="nav-item <?= $current_page === 'users.php' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fas fa-users"></i></span>
            Users
        </a>

        <a href="admins.php" class="nav-item <?= $current_page === 'admins.php' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fas fa-user-shield"></i></span>
            Admins
        </a>

        <a href="ranking.php" class="nav-item <?= $current_page === 'ranking.php' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fas fa-ranking-star"></i></span>
            Rankings
        </a>

        <div class="nav-section">Account</div>

        <a href="settings.php" class="nav-item <?= $current_page === 'settings.php' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fas fa-gear"></i></span>
            Settings
        </a>

    </div>

    <!-- Logout at bottom -->
    <div style="padding: 12px 8px; border-top: 1px solid #1e293b; margin-top: auto;">
        <a href="../auth/logout.php" class="nav-item" style="color: #f87171;"
           onmouseover="this.style.background='rgba(239,68,68,0.15)'; this.style.color='#fca5a5';"
           onmouseout="this.style.background=''; this.style.color='#f87171';">
            <span class="nav-icon"><i class="fas fa-right-from-bracket"></i></span>
            Logout
        </a>
    </div>
</nav>

<script>
function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebarOverlay').classList.add('show');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('show');
}
</script>
