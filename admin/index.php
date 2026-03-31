<?php
require_once 'auth_check.php';
require_once '../config.php';

$db = new Database();

// ── Stats ──────────────────────────────────────────────────────────
$total_users      = mysqli_fetch_assoc($db->query("SELECT COUNT(*) AS cnt FROM users"))['cnt'] ?? 0;
$total_problems   = mysqli_fetch_assoc($db->query("SELECT COUNT(*) AS cnt FROM problems"))['cnt'] ?? 0;
$total_attempts   = mysqli_fetch_assoc($db->query("SELECT COUNT(*) AS cnt FROM attempts"))['cnt'] ?? 0;
$total_contests   = mysqli_fetch_assoc($db->query("SELECT COUNT(*) AS cnt FROM contests"))['cnt'] ?? 0;
$accepted_count   = mysqli_fetch_assoc($db->query("SELECT COUNT(*) AS cnt FROM attempts WHERE status = 'Accept'"))['cnt'] ?? 0;

// ── Recent submissions (last 10) ───────────────────────────────────
$recent_attempts_result = $db->query("
    SELECT a.id, a.status, a.language, a.runTime, a.created_at,
           u.fullname, u.username,
           p.title AS problem_title, p.difficulty
    FROM attempts a
    JOIN users u ON u.id = a.user_id
    JOIN problems p ON p.id = a.problem_id
    ORDER BY a.created_at DESC
    LIMIT 10
");
$recent_attempts = [];
while ($row = mysqli_fetch_assoc($recent_attempts_result)) {
    $recent_attempts[] = $row;
}

// ── Top solvers ────────────────────────────────────────────────────
$top_users_result = $db->query("
    SELECT u.fullname, u.username,
           COUNT(DISTINCT CASE WHEN a.status = 'Accept' THEN a.problem_id END) AS solved,
           COUNT(a.id) AS total_attempts
    FROM users u
    LEFT JOIN attempts a ON a.user_id = u.id
    GROUP BY u.id
    ORDER BY solved DESC, total_attempts ASC
    LIMIT 5
");
$top_users = [];
while ($row = mysqli_fetch_assoc($top_users_result)) {
    $top_users[] = $row;
}

// ── Active contest ─────────────────────────────────────────────────
$active_contests_result = $db->query("SELECT COUNT(*) AS cnt FROM contests WHERE status = 1");
$active_contests = mysqli_fetch_assoc($active_contests_result)['cnt'] ?? 0;
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

            <!-- Page title -->
            <div style="margin-bottom: 24px;">
                <h1 style="font-size:22px; font-weight:700; color:#0f172a; margin:0 0 4px;">Dashboard</h1>
                <p style="color:#64748b; font-size:13px; margin:0;">
                    Welcome back, <strong><?= htmlspecialchars($_SESSION['fullname'] ?? 'Admin') ?></strong> — here's what's happening.
                </p>
            </div>

            <!-- ── Stats Cards ─────────────────────────────────────────── -->
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:28px;">

                <!-- Users -->
                <div class="stat-card" style="display:flex; align-items:center; gap:16px;">
                    <div style="width:52px; height:52px; background:linear-gradient(135deg,#3b82f6,#2563eb); border-radius:14px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fas fa-users" style="color:#fff; font-size:20px;"></i>
                    </div>
                    <div>
                        <div style="font-size:26px; font-weight:800; color:#0f172a; line-height:1;"><?= number_format($total_users) ?></div>
                        <div style="font-size:12px; color:#64748b; font-weight:500; margin-top:3px;">Total Users</div>
                    </div>
                </div>

                <!-- Problems -->
                <div class="stat-card" style="display:flex; align-items:center; gap:16px;">
                    <div style="width:52px; height:52px; background:linear-gradient(135deg,#8b5cf6,#7c3aed); border-radius:14px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fas fa-code" style="color:#fff; font-size:20px;"></i>
                    </div>
                    <div>
                        <div style="font-size:26px; font-weight:800; color:#0f172a; line-height:1;"><?= number_format($total_problems) ?></div>
                        <div style="font-size:12px; color:#64748b; font-weight:500; margin-top:3px;">Total Problems</div>
                    </div>
                </div>

                <!-- Submissions -->
                <div class="stat-card" style="display:flex; align-items:center; gap:16px;">
                    <div style="width:52px; height:52px; background:linear-gradient(135deg,#f59e0b,#d97706); border-radius:14px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fas fa-paper-plane" style="color:#fff; font-size:20px;"></i>
                    </div>
                    <div>
                        <div style="font-size:26px; font-weight:800; color:#0f172a; line-height:1;"><?= number_format($total_attempts) ?></div>
                        <div style="font-size:12px; color:#64748b; font-weight:500; margin-top:3px;">Submissions</div>
                    </div>
                </div>

                <!-- Accepted -->
                <div class="stat-card" style="display:flex; align-items:center; gap:16px;">
                    <div style="width:52px; height:52px; background:linear-gradient(135deg,#10b981,#059669); border-radius:14px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fas fa-circle-check" style="color:#fff; font-size:20px;"></i>
                    </div>
                    <div>
                        <div style="font-size:26px; font-weight:800; color:#0f172a; line-height:1;"><?= number_format($accepted_count) ?></div>
                        <div style="font-size:12px; color:#64748b; font-weight:500; margin-top:3px;">Accepted</div>
                    </div>
                </div>

                <!-- Contests -->
                <div class="stat-card" style="display:flex; align-items:center; gap:16px;">
                    <div style="width:52px; height:52px; background:linear-gradient(135deg,#ec4899,#db2777); border-radius:14px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fas fa-trophy" style="color:#fff; font-size:20px;"></i>
                    </div>
                    <div>
                        <div style="font-size:26px; font-weight:800; color:#0f172a; line-height:1;"><?= number_format($total_contests) ?></div>
                        <div style="font-size:12px; color:#64748b; font-weight:500; margin-top:3px;">
                            Contests
                            <?php if ($active_contests > 0): ?>
                                <span style="margin-left:4px; background:#dcfce7; color:#15803d; border-radius:99px; padding:1px 6px; font-size:10px;">
                                    <?= $active_contests ?> live
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ── Bottom grid: Recent Submissions + Top Users ────────── -->
            <div style="display:grid; grid-template-columns: 1fr 340px; gap:20px; align-items:start;">

                <!-- Recent Submissions -->
                <div class="content-card">
                    <div class="card-header">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-clock-rotate-left" style="color:#64748b;"></i>
                            <span style="font-size:14px; font-weight:600; color:#0f172a;">Recent Submissions</span>
                        </div>
                        <a href="submissions.php" style="font-size:12px; color:#2563eb; text-decoration:none; font-weight:500;">
                            View all <i class="fas fa-arrow-right" style="font-size:10px;"></i>
                        </a>
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Problem</th>
                                    <th>Status</th>
                                    <th>Lang</th>
                                    <th>Time</th>
                                    <th>When</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($recent_attempts)): ?>
                                <tr>
                                    <td colspan="7" style="text-align:center; color:#94a3b8; padding:32px;">
                                        No submissions yet
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recent_attempts as $a):
                                    $status = $a['status'];
                                    if ($status === 'Accept') { $badgeClass = 'badge-accepted'; $icon = 'fa-circle-check'; }
                                    elseif (str_contains($status, 'Wrong')) { $badgeClass = 'badge-wrong'; $icon = 'fa-circle-xmark'; }
                                    elseif (str_contains($status, 'Runtime')) { $badgeClass = 'badge-runtime'; $icon = 'fa-triangle-exclamation'; }
                                    else { $badgeClass = 'badge-pending'; $icon = 'fa-circle'; }

                                    $diff = strtolower($a['difficulty'] ?? 'easy');
                                    $diffMap = ['beginner'=>'diff-beginner','easy'=>'diff-easy','medium'=>'diff-medium','hard'=>'diff-hard','expert'=>'diff-expert'];
                                    $diffClass = $diffMap[$diff] ?? 'diff-easy';
                                ?>
                                <tr>
                                    <td style="color:#94a3b8; font-size:12px;">#<?= $a['id'] ?></td>
                                    <td>
                                        <div style="font-weight:600; color:#1e293b; font-size:13px;"><?= htmlspecialchars($a['fullname']) ?></div>
                                        <div style="color:#94a3b8; font-size:11px;">@<?= htmlspecialchars($a['username']) ?></div>
                                    </td>
                                    <td>
                                        <div style="font-size:13px; color:#374151; max-width:160px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                            <?= htmlspecialchars($a['problem_title']) ?>
                                        </div>
                                        <span class="diff-badge <?= $diffClass ?>"><?= htmlspecialchars($diff) ?></span>
                                    </td>
                                    <td>
                                        <span class="<?= $badgeClass ?>">
                                            <i class="fas <?= $icon ?>" style="margin-right:3px; font-size:10px;"></i>
                                            <?= $status === 'Accept' ? 'Accepted' : (str_contains($status, 'Wrong') ? 'Wrong Answer' : (str_contains($status, 'Runtime') ? 'Runtime Err' : $status)) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span style="background:#f1f5f9; color:#475569; border-radius:6px; padding:2px 8px; font-size:11px; font-weight:600;">
                                            <?= htmlspecialchars($a['language']) ?>
                                        </span>
                                    </td>
                                    <td style="color:#64748b; font-size:12px;"><?= intval($a['runTime']) ?>ms</td>
                                    <td style="color:#94a3b8; font-size:11px; white-space:nowrap;">
                                        <?= date('d M, H:i', strtotime($a['created_at'])) ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top Users -->
                <div class="content-card">
                    <div class="card-header">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-ranking-star" style="color:#64748b;"></i>
                            <span style="font-size:14px; font-weight:600; color:#0f172a;">Top Solvers</span>
                        </div>
                        <a href="users.php" style="font-size:12px; color:#2563eb; text-decoration:none; font-weight:500;">
                            View all <i class="fas fa-arrow-right" style="font-size:10px;"></i>
                        </a>
                    </div>
                    <div style="padding: 8px 0;">
                        <?php if (empty($top_users)): ?>
                            <div style="text-align:center; color:#94a3b8; padding:24px; font-size:13px;">No data available</div>
                        <?php else: ?>
                            <?php foreach ($top_users as $i => $user): ?>
                            <div style="display:flex; align-items:center; gap:12px; padding:10px 16px; transition: background 0.15s;"
                                 onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='';">
                                <!-- Rank -->
                                <div style="width:26px; height:26px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0;
                                    <?= $i===0 ? 'background:#fef3c7; color:#b45309;' : ($i===1 ? 'background:#f1f5f9; color:#475569;' : ($i===2 ? 'background:#fff7ed; color:#c2410c;' : 'background:#f8fafc; color:#94a3b8;')) ?>">
                                    <?= $i + 1 ?>
                                </div>
                                <!-- Avatar -->
                                <div style="width:34px; height:34px; border-radius:10px; background:linear-gradient(135deg,#6366f1,#8b5cf6); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <span style="color:#fff; font-size:13px; font-weight:700;"><?= strtoupper(substr($user['fullname'], 0, 1)) ?></span>
                                </div>
                                <!-- Info -->
                                <div style="flex:1; min-width:0;">
                                    <div style="font-size:13px; font-weight:600; color:#1e293b; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                        <?= htmlspecialchars($user['fullname']) ?>
                                    </div>
                                    <div style="font-size:11px; color:#94a3b8;">@<?= htmlspecialchars($user['username']) ?></div>
                                </div>
                                <!-- Score -->
                                <div style="text-align:right; flex-shrink:0;">
                                    <div style="font-size:15px; font-weight:700; color:#2563eb;"><?= $user['solved'] ?></div>
                                    <div style="font-size:10px; color:#94a3b8;">solved</div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div><!-- /grid -->

        </div><!-- /main-content -->
    </div><!-- /content -->
</div>

<!-- JS -->
<script src="../js/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
