<?php
   require_once 'auth_check.php';
   require_once '../config.php';
   $db = new Database();
   $contest_id = intval($_GET['contest_id'] ?? 0);
   $contest = $db->get_data_by_table('contests', ['id' => $contest_id]);
   $contest_problems = [];
   if ($contest_id > 0) {
       $res = $db->query("SELECT cp.*, u.fullname AS author_name, u.username AS author_username FROM contest_problems cp LEFT JOIN users u ON u.id = cp.author_id WHERE cp.contest_id = {$contest_id} ORDER BY cp.id ASC");
       while ($row = mysqli_fetch_assoc($res)) {
           $contest_problems[] = $row;
       }
   }
?>
<html lang="en">
   <?php include_once 'head.php'?>
   <body>
      <div class="admin-root">
            <!-- Sidebar  -->
            <?php include_once 'sidebar.php'?>
            <!-- end sidebar -->
            <!-- right content -->
            <div id="content">
               <!-- topbar -->
               <?php include_once 'topbar.php'?>
               <!-- end topbar -->
               <div id="main-content">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
                    <div>
                        <h1 style="font-size:22px; font-weight:700; color:#0f172a; margin:0 0 4px;">Contest Problems</h1>
                        <p style="color:#64748b; font-size:13px; margin:0;">
                            <?= htmlspecialchars($contest['title'] ?? 'Contest') ?> — View and manage every olympiad problem.
                        </p>
                    </div>
                    <div style="display:flex; gap:10px; flex-wrap:wrap; justify-content:flex-end;">
                        <a href="olympiads.php" style="display:inline-flex; align-items:center; gap:8px; background:#f8fafc; border:1px solid #e2e8f0; color:#2563eb; border-radius:10px; padding:10px 16px; font-size:13px; text-decoration:none; font-weight:600;">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <a href="add_contest_problem.php?contest_id=<?= $contest_id ?>" style="display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; border-radius:10px; padding:10px 16px; font-size:13px; text-decoration:none; font-weight:600;">
                            <i class="fas fa-plus"></i> Add Problem
                        </a>
                    </div>
                </div>

                <div class="content-card">
                    <div class="card-header">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-list" style="color:#64748b;"></i>
                            <span style="font-size:14px; font-weight:600; color:#0f172a;">Contest Problem List</span>
                            <span style="background:#f1f5f9; color:#64748b; border-radius:99px; padding:3px 10px; font-size:11px; font-weight:600;"><?= count($contest_problems) ?></span>
                        </div>
                    </div>
                    <div style="overflow-x:auto;">
                        <table id="problemsTable" class="admin-table" style="min-width:900px;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Difficulty</th>
                                    <th>Category</th>
                                    <th>Submit</th>
                                    <th>Tests</th>
                                    <th>Update</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $letter = 'A'; 
                                    foreach ($contest_problems as $problem): 
                                ?>
                                <tr>
                                    <td style="font-weight:700; color:#2563eb;"><?= $letter ?></td>
                                    <td>
                                        <a href="cn_solution.php?id=<?= (int)$problem['id'] ?>" style="font-weight:600; color:#1e293b; text-decoration:none; font-size:14px;">
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
                                    <td><span class="diff-badge <?= strtolower($problem['difficulty']) === 'hard' ? 'diff-hard' : (strtolower($problem['difficulty']) === 'medium' ? 'diff-medium' : (strtolower($problem['difficulty']) === 'expert' ? 'diff-expert' : (strtolower($problem['difficulty']) === 'beginner' ? 'diff-beginner' : 'diff-easy')) ) ?>"><?= htmlspecialchars($problem['difficulty']) ?></span></td>
                                    <td><span style="background:#f1f5f9; color:#475569; border-radius:6px; padding:4px 10px; font-size:11px; font-weight:600;"><?= htmlspecialchars($problem['category'] ?? '—') ?></span></td>
                                    <td>
                                        <a href="cn_solution.php?id=<?= (int)$problem['id'] ?>" style="display:inline-flex; align-items:center; gap:6px; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; border-radius:8px; padding:7px 10px; font-size:12px; font-weight:600; text-decoration:none;">
                                            <i class="fas fa-paper-plane" style="font-size:11px;"></i> Submit
                                        </a>
                                    </td>
                                    <td>
                                        <a href="cn_test_views.php?id=<?= (int)$problem['id'] ?>" style="display:inline-flex; align-items:center; gap:6px; background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; border-radius:8px; padding:7px 10px; font-size:12px; font-weight:600; text-decoration:none;">
                                            <i class="fas fa-eye" style="font-size:11px;"></i> Tests
                                        </a>
                                    </td>
                                    <td>
                                        <a href="cn_update.php?id=<?= (int)$problem['id'] ?>" style="display:inline-flex; align-items:center; gap:6px; background:#fffbeb; color:#b45309; border:1px solid #fde68a; border-radius:8px; padding:7px 10px; font-size:12px; font-weight:600; text-decoration:none;">
                                            <i class="fas fa-pen" style="font-size:11px;"></i> Edit
                                        </a>
                                    </td>
                                    <td>
                                        <button onclick="confirmDelete(<?= (int)$problem['id'] ?>)" style="display:inline-flex; align-items:center; gap:6px; background:#fff5f5; color:#dc2626; border:1px solid #fecaca; border-radius:8px; padding:7px 10px; font-size:12px; font-weight:600; cursor:pointer;">
                                            <i class="fas fa-trash" style="font-size:11px;"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                                <?php 
                                    $letter ++;
                                    endforeach; 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
         </div>
      </div>
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">O‘chirishni tasdiqlaysizmi?</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Bu elementni o‘chirmoqchimisiz? Ushbu amalni qaytarib bo‘lmaydi.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Bekor qilish</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Ha, o‘chirish</button>
                </div>
            </div>
        </div>
        </div>

      <!-- jQuery -->
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="../js/bootstrap.min.js"></script>
      <script src="../js/perfect-scrollbar.min.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
      <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
      <script>
            let deleteId = null;

            function confirmDelete(id) {
                deleteId = id;
                $('#deleteModal').modal('show');
            }

            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (deleteId) {
                fetch('delete/cn_delete_problem.php?id=' + deleteId)
                    .then(response => response.json())
                    .then(data => {
                        $('#deleteModal').modal('hide');
                        if (data.success) {
                            toastr.success(data.message, "Muvaffaqiyat ✅");
                            setTimeout(() => location.reload(), 2000); 
                        } else {
                            toastr.error(data.message, "Xatolik ❌");
                        }
                    })
                    .catch(error => {
                        toastr.error('Xatolik: ' + error.message, "Server bilan muammo");
                    });
            }
        });     
      </script>
      
      <script>
         var ps = new PerfectScrollbar('#sidebar');
      </script>
   </body>
</html>