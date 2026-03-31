<?php
require_once 'auth_check.php';
require_once '../config.php';
$db = new Database();

$admins = [];
$result = $db->query(
    "SELECT u.id, u.fullname, u.username, u.email, u.created_at, 
        COUNT(DISTINCT p.id) AS problems_added, 
        COUNT(DISTINCT a.id) AS total_attempts
    FROM users u
    LEFT JOIN problems p ON p.author_id = u.id
    LEFT JOIN attempts a ON a.user_id = u.id
    WHERE u.role = 'admin'
    GROUP BY u.id
    ORDER BY problems_added DESC, u.created_at ASC"
);
while ($row = mysqli_fetch_assoc($result)) {
    $admins[] = $row;
}
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
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:24px;">
                <div>
                    <h1 style="font-size:22px; font-weight:700; color:#0f172a; margin:0 0 4px;">Admins</h1>
                    <p style="color:#64748b; font-size:13px; margin:0;">View administrators, their added problems, and invite new admins.</p>
                </div>
                <a href="add_admin.php" style="display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; border-radius:10px; padding:10px 18px; font-size:13px; font-weight:600; text-decoration:none;">
                    <i class="fas fa-user-plus"></i> Add Admin
                </a>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:14px; margin-bottom:24px;">
                <div class="stat-card" style="display:flex; align-items:center; gap:12px;">
                    <div style="width:44px; height:44px; background:linear-gradient(135deg,#8b5cf6,#7c3aed); border-radius:12px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-user-shield" style="color:#fff; font-size:17px;"></i>
                    </div>
                    <div>
                        <div style="font-size:22px; font-weight:800; color:#0f172a;"><?= count($admins) ?></div>
                        <div style="font-size:11px; color:#64748b; font-weight:500;">Total Admins</div>
                    </div>
                </div>
                <div class="stat-card" style="display:flex; align-items:center; gap:12px;">
                    <div style="width:44px; height:44px; background:linear-gradient(135deg,#10b981,#059669); border-radius:12px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-code" style="color:#fff; font-size:17px;"></i>
                    </div>
                    <div>
                        <div style="font-size:22px; font-weight:800; color:#0f172a;">
                            <?= array_sum(array_column($admins, 'problems_added')) ?>
                        </div>
                        <div style="font-size:11px; color:#64748b; font-weight:500;">Problems Added</div>
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="card-header">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <i class="fas fa-list" style="color:#64748b;"></i>
                        <span style="font-size:14px; font-weight:600; color:#0f172a;">Admin Accounts</span>
                    </div>
                </div>
                <div style="overflow-x:auto;">
                    <table class="admin-table" style="min-width:900px;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Added Problems</th>
                                <th>Total Submissions</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($admins)): ?>
                                <tr>
                                    <td colspan="7" style="text-align:center; color:#94a3b8; padding:36px;">No admins created yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($admins as $index => $admin): ?>
                                    <tr>
                                        <td style="color:#94a3b8; font-size:12px; font-weight:600;">#<?= $index + 1 ?></td>
                                        <td>
                                            <div style="font-size:13px; font-weight:600; color:#1e293b;"><?= htmlspecialchars($admin['fullname']) ?></div>
                                            <div style="font-size:11px; color:#94a3b8;">@<?= htmlspecialchars($admin['username']) ?></div>
                                        </td>
                                        <td style="font-size:13px; color:#64748b;"><?= htmlspecialchars($admin['email']) ?></td>
                                        <td><?= htmlspecialchars($admin['problems_added']) ?></td>
                                        <td><?= htmlspecialchars($admin['total_attempts']) ?></td>
                                        <td style="color:#94a3b8; white-space:nowrap;"><?= date('d M Y', strtotime($admin['created_at'])) ?></td>
                                        <td>
                                            <a href="problems.php?author_id=<?= $admin['id'] ?>" style="display:inline-flex; align-items:center; gap:6px; background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; border-radius:8px; padding:6px 10px; font-size:12px; text-decoration:none;">
                                                <i class="fas fa-eye" style="font-size:11px;"></i> Problems
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/perfect-scrollbar.min.js"></script>
<script>
    var ps = new PerfectScrollbar('#sidebar');
</script>
</body>
</html>
