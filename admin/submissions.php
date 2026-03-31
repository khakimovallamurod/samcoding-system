<?php
require_once 'auth_check.php';
require_once '../config.php';

$db = new Database();

// Optional filter: user_id from query string
$filter_user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
$filter_status  = isset($_GET['status']) ? trim($_GET['status']) : '';

// Build WHERE clause
$where_parts = [];
if ($filter_user_id) {
    $where_parts[] = "a.user_id = " . $filter_user_id;
}
if ($filter_status === 'accepted') {
    $where_parts[] = "a.status = 'Accept'";
} elseif ($filter_status === 'wrong') {
    $where_parts[] = "a.status LIKE 'Wrong Answer%'";
} elseif ($filter_status === 'runtime') {
    $where_parts[] = "a.status LIKE 'Runtime Error%'";
}
$where_sql = count($where_parts) ? "WHERE " . implode(" AND ", $where_parts) : "";

// Main query
$attempts_result = $db->query("
    SELECT
        a.id, a.status, a.language, a.runTime, a.memory,
        a.tests_passed, a.created_at,
        u.fullname, u.username, u.id AS user_id,
        p.title AS problem_title, p.difficulty, p.id AS problem_id
    FROM attempts a
    JOIN users u ON u.id = a.user_id
    JOIN problems p ON p.id = a.problem_id
    $where_sql
    ORDER BY a.created_at DESC
    LIMIT 200
");
$attempts = [];
while ($row = mysqli_fetch_assoc($attempts_result)) {
    $attempts[] = $row;
}

// Quick stats
$total = count($attempts);
$accepted = count(array_filter($attempts, fn($a) => $a['status'] === 'Accept'));
$wrong    = count(array_filter($attempts, fn($a) => str_contains($a['status'], 'Wrong')));
$runtime  = count(array_filter($attempts, fn($a) => str_contains($a['status'], 'Runtime')));

// If filtering by user, get user info
$filtered_user = null;
if ($filter_user_id) {
    $filtered_user = $db->get_data_by_table('users', ['id' => $filter_user_id]);
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

            <!-- Header -->
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
                <div>
                    <h1 style="font-size:22px; font-weight:700; color:#0f172a; margin:0 0 4px;">
                        Submissions
                        <?php if ($filtered_user): ?>
                            <span style="font-size:14px; font-weight:500; color:#64748b;">
                                — <?= htmlspecialchars($filtered_user['fullname']) ?>
                            </span>
                        <?php endif; ?>
                    </h1>
                    <p style="color:#64748b; font-size:13px; margin:0;">
                        <?= $filter_user_id ? 'Showing submissions for this user' : 'All code submissions across the platform' ?>
                    </p>
                </div>
                <?php if ($filter_user_id): ?>
                <a href="submissions.php" style="display:inline-flex; align-items:center; gap:6px; background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; border-radius:9px; padding:7px 14px; font-size:13px; font-weight:500; text-decoration:none; transition: all 0.15s;"
                   onmouseover="this.style.background='#e2e8f0';" onmouseout="this.style.background='#f1f5f9';">
                    <i class="fas fa-arrow-left" style="font-size:11px;"></i> All Submissions
                </a>
                <?php endif; ?>
            </div>

            <!-- Stats row -->
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap:12px; margin-bottom:24px;">
                <div class="stat-card" style="display:flex; align-items:center; gap:10px;">
                    <div style="width:40px; height:40px; background:#f1f5f9; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-paper-plane" style="color:#64748b; font-size:16px;"></i>
                    </div>
                    <div>
                        <div style="font-size:20px; font-weight:800; color:#0f172a;"><?= $total ?></div>
                        <div style="font-size:11px; color:#64748b;">Total</div>
                    </div>
                </div>
                <div class="stat-card" style="display:flex; align-items:center; gap:10px; cursor:pointer;" onclick="setFilter('accepted')" id="filter-accepted">
                    <div style="width:40px; height:40px; background:#dcfce7; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-circle-check" style="color:#15803d; font-size:16px;"></i>
                    </div>
                    <div>
                        <div style="font-size:20px; font-weight:800; color:#0f172a;"><?= $accepted ?></div>
                        <div style="font-size:11px; color:#15803d; font-weight:500;">Accepted</div>
                    </div>
                </div>
                <div class="stat-card" style="display:flex; align-items:center; gap:10px; cursor:pointer;" onclick="setFilter('wrong')" id="filter-wrong">
                    <div style="width:40px; height:40px; background:#fee2e2; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-circle-xmark" style="color:#dc2626; font-size:16px;"></i>
                    </div>
                    <div>
                        <div style="font-size:20px; font-weight:800; color:#0f172a;"><?= $wrong ?></div>
                        <div style="font-size:11px; color:#dc2626; font-weight:500;">Wrong Ans</div>
                    </div>
                </div>
                <div class="stat-card" style="display:flex; align-items:center; gap:10px; cursor:pointer;" onclick="setFilter('runtime')" id="filter-runtime">
                    <div style="width:40px; height:40px; background:#fef3c7; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-triangle-exclamation" style="color:#d97706; font-size:16px;"></i>
                    </div>
                    <div>
                        <div style="font-size:20px; font-weight:800; color:#0f172a;"><?= $runtime ?></div>
                        <div style="font-size:11px; color:#d97706; font-weight:500;">Runtime Err</div>
                    </div>
                </div>
            </div>

            <!-- Filter bar -->
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px; flex-wrap:wrap;">
                <span style="font-size:13px; color:#64748b; font-weight:500;">Filter:</span>
                <button onclick="setFilter('')" class="filter-btn active" id="btn-all">All</button>
                <button onclick="setFilter('accepted')" class="filter-btn" id="btn-accepted">
                    <i class="fas fa-circle-check" style="color:#15803d; margin-right:4px;"></i>Accepted
                </button>
                <button onclick="setFilter('wrong')" class="filter-btn" id="btn-wrong">
                    <i class="fas fa-circle-xmark" style="color:#dc2626; margin-right:4px;"></i>Wrong Answer
                </button>
                <button onclick="setFilter('runtime')" class="filter-btn" id="btn-runtime">
                    <i class="fas fa-triangle-exclamation" style="color:#d97706; margin-right:4px;"></i>Runtime Error
                </button>

                <div style="margin-left:auto; position:relative;">
                    <i class="fas fa-search" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:12px;"></i>
                    <input type="text" id="subSearch" placeholder="Search problem or user..."
                        style="border:1px solid #e2e8f0; border-radius:8px; padding:6px 12px 6px 30px; font-size:13px; color:#374151; outline:none; width:220px;"
                        oninput="searchRows(this.value)"
                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)';"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='';">
                </div>
            </div>

            <!-- Submissions table -->
            <div class="content-card">
                <div style="overflow-x:auto;">
                    <table class="admin-table" id="submissionsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Problem</th>
                                <th>Status</th>
                                <th>Language</th>
                                <th>Runtime</th>
                                <th>Memory</th>
                                <th>Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($attempts)): ?>
                            <tr>
                                <td colspan="8" style="text-align:center; color:#94a3b8; padding:48px; font-size:13px;">
                                    <i class="fas fa-inbox" style="font-size:32px; display:block; margin-bottom:10px; color:#cbd5e1;"></i>
                                    No submissions found
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($attempts as $a):
                                $status = $a['status'];
                                if ($status === 'Accept') {
                                    $badgeClass = 'badge-accepted';
                                    $statusText = 'Accepted';
                                    $rowStatus  = 'accepted';
                                    $icon       = 'fa-circle-check';
                                } elseif (str_contains($status, 'Wrong')) {
                                    $badgeClass = 'badge-wrong';
                                    $statusText = 'Wrong Answer';
                                    $rowStatus  = 'wrong';
                                    $icon       = 'fa-circle-xmark';
                                } elseif (str_contains($status, 'Runtime')) {
                                    $badgeClass = 'badge-runtime';
                                    $statusText = 'Runtime Error';
                                    $rowStatus  = 'runtime';
                                    $icon       = 'fa-triangle-exclamation';
                                } else {
                                    $badgeClass = 'badge-pending';
                                    $statusText = $status;
                                    $rowStatus  = 'other';
                                    $icon       = 'fa-circle';
                                }

                                $diff = strtolower($a['difficulty'] ?? '');
                                $diffMap = ['beginner'=>'diff-beginner','easy'=>'diff-easy','medium'=>'diff-medium','hard'=>'diff-hard','expert'=>'diff-expert'];
                                $diffClass = $diffMap[$diff] ?? 'diff-easy';
                            ?>
                            <tr class="sub-row" data-status="<?= $rowStatus ?>">
                                <td style="color:#94a3b8; font-size:12px; font-weight:600;">#<?= $a['id'] ?></td>
                                <td>
                                    <a href="submissions.php?user_id=<?= $a['user_id'] ?>"
                                       style="display:flex; align-items:center; gap:8px; text-decoration:none;"
                                       onmouseover="this.querySelector('.u-name').style.color='#2563eb';"
                                       onmouseout="this.querySelector('.u-name').style.color='#1e293b';">
                                        <div style="width:30px; height:30px; border-radius:8px; background:linear-gradient(135deg,#6366f1,#8b5cf6); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <span style="color:#fff; font-size:11px; font-weight:700;"><?= strtoupper(substr($a['fullname'], 0, 1)) ?></span>
                                        </div>
                                        <div>
                                            <div class="u-name" style="font-size:12px; font-weight:600; color:#1e293b; transition: color 0.15s;">
                                                <?= htmlspecialchars($a['fullname']) ?>
                                            </div>
                                            <div style="font-size:11px; color:#94a3b8;">@<?= htmlspecialchars($a['username']) ?></div>
                                        </div>
                                    </a>
                                </td>
                                <td class="sub-problem">
                                    <div style="font-size:13px; color:#374151; font-weight:500; max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                        <?= htmlspecialchars($a['problem_title']) ?>
                                    </div>
                                    <span class="diff-badge <?= $diffClass ?>"><?= htmlspecialchars($diff) ?></span>
                                </td>
                                <td>
                                    <span class="<?= $badgeClass ?>">
                                        <i class="fas <?= $icon ?>" style="margin-right:3px; font-size:10px;"></i>
                                        <?= $statusText ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="background:#f1f5f9; color:#475569; border-radius:6px; padding:3px 8px; font-size:11px; font-weight:600;">
                                        <?= htmlspecialchars($a['language']) ?>
                                    </span>
                                </td>
                                <td style="color:#64748b; font-size:12px;">
                                    <?= $a['runTime'] ? intval($a['runTime']) . ' ms' : '—' ?>
                                </td>
                                <td style="color:#64748b; font-size:12px;">
                                    <?= $a['memory'] ? intval($a['memory'] / 1024) . ' KB' : '—' ?>
                                </td>
                                <td style="color:#94a3b8; font-size:11px; white-space:nowrap;">
                                    <?= date('d M Y, H:i', strtotime($a['created_at'])) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div style="padding:12px 16px; border-top:1px solid #f1f5f9; color:#94a3b8; font-size:12px;">
                    Showing <span id="visibleSubs"><?= count($attempts) ?></span> submissions
                    <?= count($attempts) >= 200 ? '(limited to 200 most recent)' : '' ?>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.filter-btn {
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #475569;
    border-radius: 8px;
    padding: 5px 12px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s;
}
.filter-btn:hover { background: #f8fafc; border-color: #cbd5e1; }
.filter-btn.active { background: #0f172a; color: #fff; border-color: #0f172a; }
</style>

<script src="../js/jquery.min.js"></script>
<script>
let currentFilter = '';

function setFilter(type) {
    currentFilter = type;

    // Update button states
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('btn-' + (type || 'all')).classList.add('active');

    applyFilters();
}

function searchRows(q) {
    applyFilters();
}

function applyFilters() {
    const q = (document.getElementById('subSearch')?.value || '').toLowerCase();
    let visible = 0;
    document.querySelectorAll('#submissionsTable tbody tr.sub-row').forEach(row => {
        const statusMatch = !currentFilter || row.dataset.status === currentFilter;
        const problem     = row.querySelector('.sub-problem')?.textContent.toLowerCase() || '';
        const userName    = row.querySelector('.u-name')?.textContent.toLowerCase() || '';
        const textMatch   = !q || problem.includes(q) || userName.includes(q);

        const show = statusMatch && textMatch;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById('visibleSubs').textContent = visible;
}
</script>
</body>
</html>
