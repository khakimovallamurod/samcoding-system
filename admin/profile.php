<?php
   require_once 'auth_check.php';
   require_once '../config.php';
   $user_id = $_SESSION['id'];
   $obj = new Database();
   $user = $obj->get_data_by_table('users', ['id' => $user_id]);
?>
<html lang="en">
   <?php include_once 'head.php'?>
   <body>
      <div class="admin-root">
         <?php include_once 'sidebar.php'?>
         <div id="content">
            <?php include_once 'topbar.php'?>
            <div id="main-content">
               <div style="max-width:980px; margin:0 auto;">
                  <div style="margin-bottom:24px;">
                      <h1 style="font-size:22px; font-weight:700; color:#0f172a; margin:0 0 4px;">Profile</h1>
                      <p style="color:#64748b; font-size:13px; margin:0;">Admin account details and profile overview.</p>
                  </div>
                  <div style="display:grid; gap:18px;">
                     <div class="content-card" style="padding:24px;">
                         <div style="display:flex; flex-wrap:wrap; gap:24px; align-items:center;">
                             <div style="flex:0 0 auto; width:150px; height:150px; border-radius:24px; background:#fff; display:flex; align-items:center; justify-content:center; box-shadow:0 16px 40px rgba(15,23,42,0.06);">
                                 <img width="110" class="rounded-circle" src="../images/logo/mylogo.png" alt="Profile Image" />
                             </div>
                             <div style="flex:1; min-width:220px;">
                                 <h2 style="font-size:28px; font-weight:800; color:#0f172a; margin:0 0 8px;"><?= htmlspecialchars($user['fullname'] ?: $user['username']) ?></h2>
                                 <p style="color:#64748b; font-size:14px; margin:0 0 8px;">Welcome back, admin.</p>
                                 <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px; margin-top:16px;">
                                     <div style="background:#f8fafc; border-radius:14px; padding:14px;">
                                         <div style="font-size:12px; color:#64748b;">Username</div>
                                         <div style="font-size:14px; font-weight:700; color:#0f172a;">@<?= htmlspecialchars($user['username']) ?></div>
                                     </div>
                                     <div style="background:#f8fafc; border-radius:14px; padding:14px;">
                                         <div style="font-size:12px; color:#64748b;">Email</div>
                                         <div style="font-size:14px; font-weight:700; color:#0f172a;"><?= htmlspecialchars($user['email']) ?></div>
                                     </div>
                                     <div style="background:#f8fafc; border-radius:14px; padding:14px;">
                                         <div style="font-size:12px; color:#64748b;">Role</div>
                                         <div style="font-size:14px; font-weight:700; color:#0f172a; text-transform:capitalize;"><?= htmlspecialchars($user['role'] ?? 'admin') ?></div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="content-card" style="padding:24px;">
                         <h3 style="font-size:18px; font-weight:700; color:#0f172a; margin-bottom:14px;">Contact & Education</h3>
                         <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                             <div style="background:#fff; border-radius:16px; border:1px solid #e2e8f0; padding:18px;">
                                 <div style="color:#64748b; font-size:12px; margin-bottom:6px;">Phone</div>
                                 <div style="font-size:14px; color:#1e293b; font-weight:600;"><?= htmlspecialchars($user['phone'] ?: '—') ?></div>
                             </div>
                             <div style="background:#fff; border-radius:16px; border:1px solid #e2e8f0; padding:18px;">
                                 <div style="color:#64748b; font-size:12px; margin-bottom:6px;">University</div>
                                 <div style="font-size:14px; color:#1e293b; font-weight:600;"><?= htmlspecialchars($user['otm'] ?: '—') ?></div>
                             </div>
                             <div style="background:#fff; border-radius:16px; border:1px solid #e2e8f0; padding:18px;">
                                 <div style="color:#64748b; font-size:12px; margin-bottom:6px;">Course</div>
                                 <div style="font-size:14px; color:#1e293b; font-weight:600;"><?= htmlspecialchars($user['course'] ?: '—') ?></div>
                             </div>
                             <div style="background:#fff; border-radius:16px; border:1px solid #e2e8f0; padding:18px;">
                                 <div style="color:#64748b; font-size:12px; margin-bottom:6px;">Joined</div>
                                 <div style="font-size:14px; color:#1e293b; font-weight:600;"><?= htmlspecialchars($user['created_at'] ?: '—') ?></div>
                             </div>
                         </div>
                     </div>
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
