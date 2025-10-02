<?php
   include_once '../config.php';
   session_start();
   $user_id = $_SESSION['id'];
   $obj = new Database();
   $user = $obj->get_data_by_table('users', ['id' => $user_id]);

?> 
<html lang="en">
   <?php include_once 'head.php'?>
   <body class="inner_page profile_page">
      <div class="full_container">
         <div class="inner_container">
            <!-- Sidebar  -->
            <?php include_once 'sidebar.php'?>
            <!-- end sidebar -->
            <!-- right content -->
            <div id="content">
               <!-- topbar -->
               <?php include_once 'topbar.php'?>
               <!-- end topbar -->
               <!-- dashboard inner -->
               <div class="midde_cont">
                  <div class="container-fluid">
                     <div class="row column_title">
                        <div class="col-md-12">
                           <div class="page_title">
                              <h2>Profile</h2>
                           </div>
                        </div>
                     </div>

                     <!-- row -->
                     <div class="row column1">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                           <div class="white_shd full margin_bottom_30">
                              <div class="full graph_head">
                                 <div class="heading1 margin_0">
                                    <h2> <?= ucfirst($user['username']) ?></h2>
                                 </div>
                              </div>
                              <div class="full price_table padding_infor_info">
                                 <div class="row">
                                    <div class="col-lg-12">
                                       <div class="full dis_flex center_text">
                                          <!-- Profile Image -->
                                          <div class="profile_img">
                                             <img width="150" class="rounded-circle shadow" src="../images/logo/mylogo.png" alt="Profile Image" />
                                          </div>

                                          <!-- Profile Content -->
                                          <div class="profile_contant ml-4">
                                             <div class="contact_inner">
                                                <h3><?= htmlspecialchars($user['fullname']) ?></h3>
                                                <ul class="list-unstyled">
                                                   <!-- Email -->
                                                   <li><i class="fa fa-envelope text-primary"></i> <?= htmlspecialchars($user['email']) ?></li>
                                                   
                                                   <!-- Telefon -->
                                                   <li><i class="fa fa-phone text-success"></i> <?= htmlspecialchars($user['phone']) ?></li>
                                                   
                                                   <!-- OTM (Universitet) -->
                                                   <li><i class="fa fa-university text-info"></i> <?= htmlspecialchars($user['otm']) ?></li>
                                                   
                                                   <!-- Kurs -->
                                                   <li><i class="fa fa-graduation-cap text-secondary"></i> <?= htmlspecialchars($user['course']) ?></li>
                                                   
                                                   <!-- Ro'yxatdan o‘tgan sana -->
                                                   <li><i class="fa fa-calendar text-warning"></i> Joined: <?= htmlspecialchars($user['created_at']) ?></li>
                                                </ul>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-2"></div>
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