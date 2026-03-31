<?php
require_once 'auth_check.php';
require_once '../config.php';

$db = new Database();

// Users with their stats
$users_result = $db->query("
    SELECT
        u.id,
        u.fullname,
        u.username,
        u.email,
        u.role,
        u.created_at,
        COUNT(DISTINCT a.id) AS total_attempts,
        COUNT(DISTINCT CASE WHEN a.status = 'Accept' THEN a.problem_id END) AS solved_problems
    FROM users u
    LEFT JOIN attempts a ON a.user_id = u.id
    GROUP BY u.id
    ORDER BY solved_problems DESC, total_attempts DESC
");
$users = [];
while ($row = mysqli_fetch_assoc($users_result)) {
    $users[] = $row;
}

$total_users  = count($users);
$admin_count  = count(array_filter($users, fn($u) => $u['role'] === 'admin'));
$active_count = count(array_filter($users, fn($u) => $u['total_attempts'] > 0));
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once 'head.php' ?>
<body>
<div class="admin-root">

    <?php include_once 'sidebar.php' ?>

    <div id="content">
        <?php include_once 'topbar.php' ?>

        <div id="main-content">

            <!-- Header -->
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
                <div>
                    <h1 style="font-size:22px; font-weight:700; color:#0f172a; margin:0 0 4px;">Users</h1>
                    <p style="color:#64748b; font-size:13px; margin:0;">Manage and monitor all registered users</p>
                </div>
            </div>

            <!-- Quick stats -->
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap:14px; margin-bottom:24px;">
                <div class="stat-card" style="display:flex; align-items:center; gap:12px;">
                    <div style="width:44px; height:44px; background:linear-gradient(135deg,#3b82f6,#2563eb); border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fas fa-users" style="color:#fff; font-size:17px;"></i>
                    </div>
                    <div>
                        <div style="font-size:22px; font-weight:800; color:#0f172a;"><?= $total_users ?></div>
                        <div style="font-size:11px; color:#64748b; font-weight:500;">Total Users</div>
                    </div>
                </div>
                <div class="stat-card" style="display:flex; align-items:center; gap:12px;">
                    <div style="width:44px; height:44px; background:linear-gradient(135deg,#10b981,#059669); border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fas fa-circle-check" style="color:#fff; font-size:17px;"></i>
                    </div>
                    <div>
                        <div style="font-size:22px; font-weight:800; color:#0f172a;"><?= $active_count ?></div>
                        <div style="font-size:11px; color:#64748b; font-weight:500;">Active Users</div>
                    </div>
                </div>
                <div class="stat-card" style="display:flex; align-items:center; gap:12px;">
                    <div style="width:44px; height:44px; background:linear-gradient(135deg,#8b5cf6,#7c3aed); border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fas fa-user-shield" style="color:#fff; font-size:17px;"></i>
                    </div>
                    <div>
                        <div style="font-size:22px; font-weight:800; color:#0f172a;"><?= $admin_count ?></div>
                        <div style="font-size:11px; color:#64748b; font-weight:500;">Admins</div>
                    </div>
                </div>
            </div>

            <!-- Users table -->
            <div class="content-card">
                <div class="card-header">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <i class="fas fa-list" style="color:#64748b;"></i>
                        <span style="font-size:14px; font-weight:600; color:#0f172a;">All Users</span>
                    </div>
                    <!-- Search -->
                    <div style="position:relative;">
                        <i class="fas fa-search" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:13px;"></i>
                        <input type="text" id="userSearch" placeholder="Search users..."
                            style="border:1px solid #e2e8f0; border-radius:8px; padding:6px 12px 6px 32px; font-size:13px; color:#374151; outline:none; width:200px;"
                            oninput="filterUsers(this.value)"
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='';">
                    </div>
                </div>

                <div style="overflow-x:auto;">
                    <table class="admin-table" id="usersTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Solved</th>
                                <th>Attempts</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="8" style="text-align:center; color:#94a3b8; padding:40px; font-size:13px;">
                                    No users registered yet
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $i => $user): ?>
                            <tr class="user-row">
                                <td style="color:#94a3b8; font-size:12px; font-weight:600;"><?= str_pad($user['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div style="width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg,#6366f1,#8b5cf6); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <span style="color:#fff; font-size:13px; font-weight:700;"><?= strtoupper(substr($user['fullname'], 0, 1)) ?></span>
                                        </div>
                                        <div>
                                            <div style="font-weight:600; color:#1e293b; font-size:13px;" class="user-name">
                                                <?= htmlspecialchars($user['fullname']) ?>
                                            </div>
                                            <div style="color:#94a3b8; font-size:11px;" class="user-username">
                                                @<?= htmlspecialchars($user['username']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td style="color:#64748b; font-size:13px;" class="user-email">
                                    <?= htmlspecialchars($user['email'] ?? '—') ?>
                                </td>
                                <td>
                                    <?php if ($user['role'] === 'admin'): ?>
                                        <span style="background:#ede9fe; color:#7c3aed; border-radius:99px; padding:3px 10px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.04em;">
                                            <i class="fas fa-shield-halved" style="margin-right:3px;"></i>Admin
                                        </span>
                                    <?php else: ?>
                                        <span style="background:#f1f5f9; color:#475569; border-radius:99px; padding:3px 10px; font-size:11px; font-weight:600;">
                                            User
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span style="font-size:16px; font-weight:700; color:#2563eb;"><?= $user['solved_problems'] ?></span>
                                </td>
                                <td style="color:#64748b; font-size:13px;"><?= $user['total_attempts'] ?></td>
                                <td style="color:#94a3b8; font-size:12px; white-space:nowrap;">
                                    <?= $user['created_at'] ? date('d M Y', strtotime($user['created_at'])) : '—' ?>
                                </td>
                                <td>
                                    <a href="submissions.php?user_id=<?= $user['id'] ?>"
                                       style="display:inline-flex; align-items:center; gap:5px; background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; border-radius:7px; padding:5px 10px; font-size:12px; font-weight:500; text-decoration:none; transition: all 0.15s;"
                                       onmouseover="this.style.background='#dbeafe';" onmouseout="this.style.background='#eff6ff';">
                                        <i class="fas fa-eye" style="font-size:10px;"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination info -->
                <div style="padding:12px 16px; border-top:1px solid #f1f5f9; color:#94a3b8; font-size:12px;">
                    Showing <span id="visibleCount"><?= $total_users ?></span> of <?= $total_users ?> users
                </div>
            </div>

        </div>
    </div>
</div>

<script src="../js/jquery.min.js"></script>
<script>
function filterUsers(query) {
    const q = query.toLowerCase();
    let visible = 0;
    document.querySelectorAll('#usersTable tbody tr.user-row').forEach(row => {
        const name     = row.querySelector('.user-name')?.textContent.toLowerCase() || '';
        const username = row.querySelector('.user-username')?.textContent.toLowerCase() || '';
        const email    = row.querySelector('.user-email')?.textContent.toLowerCase() || '';
        const match    = name.includes(q) || username.includes(q) || email.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('visibleCount').textContent = visible;
}
</script>
</body>
</html>
