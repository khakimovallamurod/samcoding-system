<?php
   require_once 'auth_check.php';
   require_once '../config.php';
   $db = new Database();

   $rankings = [];
   $result = $db->query("SELECT u.id, u.fullname, u.username, u.role, u.created_at,
        COUNT(a.id) AS total_submissions,
        COUNT(CASE WHEN a.status = 'Accept' THEN 1 END) AS accepted_count,
        COUNT(CASE WHEN a.status LIKE 'Wrong Answer%' THEN 1 END) AS wrong_count,
        COUNT(DISTINCT CASE WHEN a.status = 'Accept' THEN a.problem_id END) AS solved_problems
    FROM users u
    LEFT JOIN attempts a ON a.user_id = u.id
    GROUP BY u.id
    ORDER BY solved_problems DESC, accepted_count DESC, total_submissions ASC, u.fullname ASC");
   while ($row = mysqli_fetch_assoc($result)) {
       $rankings[] = $row;
   }
?>
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
                       <h1 style="font-size:22px; font-weight:700; color:#0f172a; margin:0 0 4px;">Leaderboards</h1>
                       <p style="color:#64748b; font-size:13px; margin:0;">Top competitors and their solved problem counts.</p>
                   </div>
               </div>

               <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:14px; margin-bottom:24px;">
                   <div class="stat-card" style="display:flex; align-items:center; gap:12px;">
                       <div style="width:44px; height:44px; background:linear-gradient(135deg,#2563eb,#1d4ed8); border-radius:12px; display:flex; align-items:center; justify-content:center;">
                           <i class="fas fa-trophy" style="color:#fff; font-size:18px;"></i>
                       </div>
                       <div>
                           <div style="font-size:22px; font-weight:800; color:#0f172a;"><?= count($rankings) ?></div>
                           <div style="font-size:11px; color:#64748b; font-weight:500;">Total users ranked</div>
                       </div>
                   </div>
                   <div class="stat-card" style="display:flex; align-items:center; gap:12px;">
                       <div style="width:44px; height:44px; background:linear-gradient(135deg,#10b981,#059669); border-radius:12px; display:flex; align-items:center; justify-content:center;">
                           <i class="fas fa-chart-line" style="color:#fff; font-size:18px;"></i>
                       </div>
                       <div>
                           <div style="font-size:22px; font-weight:800; color:#0f172a;">Top <?= min(5, count($rankings)) ?></div>
                           <div style="font-size:11px; color:#64748b; font-weight:500;">Users with highest solved problems</div>
                       </div>
                   </div>
               </div>

               <div class="content-card">
                   <div class="card-header">
                       <div style="display:flex; align-items:center; gap:8px;">
                           <i class="fas fa-list" style="color:#64748b;"></i>
                           <span style="font-size:14px; font-weight:600; color:#0f172a;">Ranking Table</span>
                       </div>
                   </div>
                   <div style="overflow-x:auto;">
                       <table class="admin-table" style="min-width:900px;">
                           <thead>
                               <tr>
                                   <th>#</th>
                                   <th>User</th>
                                   <th>Role</th>
                                   <th>Solved</th>
                                   <th>Accepted</th>
                                   <th>Wrong</th>
                                   <th>Submissions</th>
                                   <th>Joined</th>
                               </tr>
                           </thead>
                           <tbody>
                               <?php foreach ($rankings as $index => $user): ?>
                                   <tr>
                                       <td style="color:#94a3b8; font-size:12px; font-weight:600;"><?= $index + 1 ?></td>
                                       <td>
                                           <div style="font-size:13px; font-weight:600; color:#1e293b;"><?= htmlspecialchars($user['fullname']) ?></div>
                                           <div style="color:#94a3b8; font-size:11px;">@<?= htmlspecialchars($user['username']) ?></div>
                                       </td>
                                       <td style="text-transform:capitalize; color:#475569; font-size:13px;"><?= htmlspecialchars($user['role'] ?: 'user') ?></td>
                                       <td style="font-weight:700; color:#2563eb;"><?= $user['solved_problems'] ?></td>
                                       <td style="color:#15803d; font-weight:600;"><?= $user['accepted_count'] ?></td>
                                       <td style="color:#dc2626; font-weight:600;"><?= $user['wrong_count'] ?></td>
                                       <td style="color:#64748b;"><?= $user['total_submissions'] ?></td>
                                       <td style="color:#94a3b8; white-space:nowrap;"><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                                   </tr>
                               <?php endforeach; ?>
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
