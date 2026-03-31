<?php
require_once 'auth_check.php';
require_once '../config.php';
$db = new Database();
$authorFilter = isset($_GET['author_id']) ? intval($_GET['author_id']) : 0;
$where = '';
if ($authorFilter > 0) {
    $where = "WHERE p.author_id = {$authorFilter}";
    $author = $db->get_data_by_table('users', ['id' => $authorFilter]);
}
$problems = [];
$result = $db->query(
    "SELECT p.*, u.fullname AS author_name, u.username AS author_username " .
    "FROM problems p LEFT JOIN users u ON u.id = p.author_id {$where} ORDER BY p.id DESC"
);
while ($row = mysqli_fetch_assoc($result)) {
    $problems[] = $row;
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
                    <h1 style="font-size:22px; font-weight:700; color:#0f172a; margin:0 0 4px;">Problems</h1>
                    <p style="color:#64748b; font-size:13px; margin:0;">
                        <?= isset($author) ? 'Problems added by ' . htmlspecialchars($author['fullname']) : 'Manage all programming problems' ?>
                    </p>
                </div>
                <a href="add_problem.php" style="display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; border-radius:10px; padding:10px 18px; font-size:13px; font-weight:600; text-decoration:none;">
                    <i class="fas fa-circle-plus"></i> Add New Problem
                </a>
            </div>

            <!-- Table card -->
            <div class="content-card">
                <div class="card-header">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <i class="fas fa-code" style="color:#64748b;"></i>
                        <span style="font-size:14px; font-weight:600; color:#0f172a;">All Problems</span>
                        <span style="background:#f1f5f9; color:#64748b; border-radius:99px; padding:2px 8px; font-size:11px; font-weight:600;"><?= count($problems) ?></span>
                    </div>
                </div>

                <div style="overflow-x:auto;">
                    <table id="problemsTable" class="admin-table" style="width:100%;">
                        <thead>
                            <tr>
                                <th style="width:70px;">ID</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Difficulty</th>
                                <th>Category</th>
                                <th style="width:120px;">Tests</th>
                                <th style="width:90px;">Edit</th>
                                <th style="width:90px;">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($problems as $problem):
                                $diff  = strtolower($problem['difficulty'] ?? 'easy');
                                $diffMap = ['beginner'=>'diff-beginner','easy'=>'diff-easy','medium'=>'diff-medium','hard'=>'diff-hard','expert'=>'diff-expert'];
                                $diffClass = $diffMap[$diff] ?? 'diff-easy';
                            ?>
                            <tr>
                                <td>
                                    <a href="solution.php?id=<?= (int)$problem['id'] ?>"
                                       style="font-weight:700; color:#2563eb; font-size:12px; text-decoration:none; font-family:monospace;"
                                       onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';">
                                        #<?= str_pad($problem['id'], 4, '0', STR_PAD_LEFT) ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="solution.php?id=<?= (int)$problem['id'] ?>"
                                       style="font-weight:600; color:#1e293b; font-size:13px; text-decoration:none;"
                                       onmouseover="this.style.color='#2563eb';" onmouseout="this.style.color='#1e293b';">
                                        <?= htmlspecialchars($problem['title']) ?>
                                    </a>
                                </td>
                                <td>
                                    <div style="font-size:13px; color:#1e293b; font-weight:600;">
                                        <?= htmlspecialchars($problem['author_name'] ?? 'System') ?>
                                    </div>
                                    <div style="font-size:11px; color:#94a3b8;">
                                        <?= htmlspecialchars($problem['author_username'] ? '@' . $problem['author_username'] : '—') ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="diff-badge <?= $diffClass ?>">
                                        <?= htmlspecialchars($problem['difficulty']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="background:#f1f5f9; color:#475569; border-radius:6px; padding:3px 9px; font-size:11px; font-weight:600;">
                                        <?= htmlspecialchars($problem['category'] ?? '—') ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="test_views.php?id=<?= (int)$problem['id'] ?>"
                                       style="display:inline-flex; align-items:center; gap:5px; background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; border-radius:7px; padding:5px 10px; font-size:11px; font-weight:500; text-decoration:none; transition: all 0.15s;"
                                       onmouseover="this.style.background='#dbeafe';" onmouseout="this.style.background='#eff6ff';">
                                        <i class="fas fa-eye" style="font-size:10px;"></i> View Tests
                                    </a>
                                </td>
                                <td>
                                    <a href="update.php?id=<?= (int)$problem['id'] ?>"
                                       style="display:inline-flex; align-items:center; gap:5px; background:#fffbeb; color:#b45309; border:1px solid #fde68a; border-radius:7px; padding:5px 10px; font-size:11px; font-weight:500; text-decoration:none; transition: all 0.15s;"
                                       onmouseover="this.style.background='#fef3c7';" onmouseout="this.style.background='#fffbeb';">
                                        <i class="fas fa-pen-to-square" style="font-size:10px;"></i> Edit
                                    </a>
                                </td>
                                <td>
                                    <button onclick="confirmDelete(<?= (int)$problem['id'] ?>)"
                                        style="display:inline-flex; align-items:center; gap:5px; background:#fff5f5; color:#dc2626; border:1px solid #fecaca; border-radius:7px; padding:5px 10px; font-size:11px; font-weight:500; cursor:pointer; transition: all 0.15s;"
                                        onmouseover="this.style.background='#fee2e2';" onmouseout="this.style.background='#fff5f5';">
                                        <i class="fas fa-trash" style="font-size:10px;"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div><!-- /main-content -->
    </div><!-- /content -->
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; display:none; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; padding:28px; max-width:400px; width:90%; box-shadow:0 25px 50px rgba(0,0,0,0.2); animation: slideUp 0.2s ease;">
        <div style="text-align:center; margin-bottom:20px;">
            <div style="width:56px; height:56px; background:#fee2e2; border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
                <i class="fas fa-trash" style="color:#dc2626; font-size:22px;"></i>
            </div>
            <h3 style="font-size:17px; font-weight:700; color:#0f172a; margin:0 0 6px;">Delete Problem?</h3>
            <p style="color:#64748b; font-size:13px; margin:0;">This action cannot be undone. All tests and attempts for this problem will also be removed.</p>
        </div>
        <div style="display:flex; gap:10px;">
            <button onclick="closeDeleteModal()"
                style="flex:1; padding:10px; border:1px solid #e2e8f0; background:#f8fafc; border-radius:10px; font-size:13px; font-weight:600; color:#475569; cursor:pointer; transition: background 0.15s;"
                onmouseover="this.style.background='#f1f5f9';" onmouseout="this.style.background='#f8fafc';">
                Cancel
            </button>
            <button id="confirmDeleteBtn"
                style="flex:1; padding:10px; border:none; background:linear-gradient(135deg,#dc2626,#b91c1c); border-radius:10px; font-size:13px; font-weight:600; color:#fff; cursor:pointer; transition: opacity 0.15s;"
                onmouseover="this.style.opacity='0.9';" onmouseout="this.style.opacity='1';">
                <i class="fas fa-trash" style="margin-right:6px;"></i>Yes, Delete
            </button>
        </div>
    </div>
</div>

<style>
@keyframes slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>

<script src="../js/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
let deleteId = null;

function confirmDelete(id) {
    deleteId = id;
    document.getElementById('deleteModal').style.display = 'flex';
}
function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    deleteId = null;
}

// Close on backdrop click
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!deleteId) return;
    this.textContent = 'Deleting...';
    this.disabled = true;

    fetch('delete/delete_problem.php?id=' + deleteId)
        .then(r => r.json())
        .then(data => {
            closeDeleteModal();
            if (data.success) {
                toastr.success(data.message || 'Problem deleted!');
                setTimeout(() => location.reload(), 1500);
            } else {
                toastr.error(data.message || 'Delete failed.');
            }
        })
        .catch(() => {
            closeDeleteModal();
            toastr.error('Server error. Please try again.');
        });
});

$(document).ready(function() {
    $('#problemsTable').DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        order: [[0, "desc"]],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ problems",
            paginate: { next: "Next →", previous: "← Prev" }
        }
    });
});
</script>
</body>
</html>
