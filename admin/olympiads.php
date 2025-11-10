<?php
   session_start();
   include_once '../config.php';
   $db = new Database();
   if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
      header("Location: ../auth/login.php");
      exit;
   }
   $musobaqalar = $db->get_data_by_table_all("contests");
?>
<html lang="en">
   <?php include_once 'head.php'?>
   <body class="dashboard dashboard_1">
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
                              <h2>Olimpiadalar</h2>
                           </div>
                        </div>
                     </div>
                     
                     <!-- Yangi musoboqa qo'shish tugmasi -->
                     <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-12">
                           <button class="btn btn-primary" data-toggle="modal" data-target="#addContestModal">
                              <i class="fa fa-plus"></i> Yangi Musoboqa Qo'shish
                           </button>
                        </div>
                     </div>

                     <!-- Musobaqalar cardlari -->
                     <div class="row column1">
                        <?php foreach ($musobaqalar as $contest): ?>
                           <?php
                                 // statusga qarab badge, rang va nomni tanlash
                                 switch ($contest['status']) {
                                    case 0:
                                       $status_text = "Kutilmoqda";
                                       $status_class = "badge-warning";
                                       $gradient = "#EF9B0F";
                                       $color1 = "#27e1e7ff";
                                       $color2 = "#16b0b6ff";
                                       break;
                                    case 1:
                                       $status_text = "Faol";
                                       $status_class = "badge-success";
                                       $gradient = "#3EB489";
                                       $color1 = "#67f591ff";
                                       $color2 = "#0ba513ff";
                                       break;
                                    case 2:
                                       $status_text = "Tugagan";
                                       $status_class = "badge-danger";
                                       $gradient = "#E44D2E";
                                       $color1 = "#e46e38ff";
                                       $color2 = "#f52408ff";
                                       break;
                                    default:
                                       $status_text = "Noma'lum";
                                       $status_class = "badge-secondary";
                                       $gradient = "#3EB489";
                                       $color1 = "#999";
                                       $color2 = "#666";
                                       break;
                                 }
                           ?>
                           <div class="col-md-4 col-sm-6">
                                 <div class="white_shd full margin_bottom_30" style="border-radius: 8px; overflow: hidden;">
                                    <div class="full graph_head" style="background: <?= $gradient ?>; padding: 20px;">
                                       <div class="heading1 margin_0">
                                             <h2 style="color: white; font-size: 18px; margin-bottom: 5px;">
                                                <?= htmlspecialchars($contest['title']) ?>
                                             </h2>
                                             <span class="badge <?= $status_class ?>" style="font-size: 12px;">
                                                <?= $status_text ?>
                                             </span>
                                       </div>
                                    </div>

                                    <div class="full graph_revenue" style="padding: 20px;">
                                       <p style="color: #666; margin-bottom: 15px; min-height: 60px;">
                                             <?= nl2br(htmlspecialchars($contest['description'])) ?>
                                       </p>

                                       <div style="border-top: 1px solid #eee; padding-top: 15px; margin-bottom: 15px;">
                                          <div style="margin-bottom: 8px;">
                                             <i class="fa fa-calendar" style="color: <?= $color1 ?>; margin-right: 5px;"></i>
                                             <strong>Boshlanish:</strong>
                                             <span style="color: #666;">
                                                <?= date("d.m.Y H:i", strtotime($contest['start_time'])) ?>
                                             </span>
                                          </div>

                                          <div style="margin-bottom: 8px;">
                                             <i class="fa fa-clock-o" style="color: <?= $color2 ?>; margin-right: 5px;"></i>
                                             <strong>Tugash:</strong>
                                             <span style="color: #666;">
                                                <?= date("d.m.Y H:i", strtotime($contest['end_time'])) ?>
                                             </span>
                                          </div>
                                          <?php
                                             $start = strtotime($contest['start_time']);
                                             $end = strtotime($contest['end_time']);
                                             $diff = $end - $start;

                                             $hours = floor($diff / 3600);
                                             $minutes = floor(($diff % 3600) / 60);

                                             if ($hours > 0) {
                                                $duration = "{$hours} soat";
                                                if ($minutes > 0) $duration .= " {$minutes} daqiqa";
                                             } else {
                                                $duration = "{$minutes} daqiqa";
                                             }
                                          ?>
                                          <div>
                                             <i class="fa fa-hourglass-half" style="color: #999; margin-right: 5px;"></i>
                                             <strong>Davomiyligi:</strong>
                                             <span style="color: #666;"><?= $duration ?></span>
                                          </div>
                                       </div>
                                       <div class="row" style="margin-top: 20px;">
                                             <div class="col-xs-6" style="padding-right: 5px;">
                                                <a href="contest_problems.php?contest_id=<?= $contest['id'] ?>"
                                                   class="btn btn-success btn-block btn-sm"
                                                   style="border-radius: 4px;">
                                                   <i class="fa fa-tasks"></i> Masalalar
                                                </a>
                                             </div>
                                             <div class="col-xs-3" style="padding-left: 5px; padding-right: 5px;">
                                                <button class="btn btn-info btn-block btn-sm"
                                                   style="border-radius: 4px;"
                                                   data-toggle="modal"
                                                   data-target="#editContestModal"
                                                   onclick="editContest(<?= $contest['id'] ?>, '<?= htmlspecialchars($contest['title'], ENT_QUOTES) ?>', '<?= htmlspecialchars($contest['description'], ENT_QUOTES) ?>', '<?= $contest['start_time'] ?>', '<?= $contest['end_time'] ?>', <?= $contest['status'] ?>)">
                                                <i class="fa fa-edit"></i>
                                             </button>
                                             </div>
                                             <div class="col-xs-3" style="padding-left: 5px;">
                                                <button class="btn btn-danger btn-block btn-sm"
                                                         onclick="confirmDelete(<?= (int)$contest['id'] ?>)"
                                                         style="border-radius: 4px;">
                                                   <i class="fa fa-trash"></i>
                                                </button>
                                             </div>
                                       </div>
                                    </div>
                                 </div>
                           </div>
                        <?php endforeach; ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- Yangi Musoboqa Qo'shish Modal -->
      <div class="modal fade" id="addContestModal" tabindex="-1" role="dialog">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Yangi Musoboqa Qo'shish</h4>
               </div>

               <!-- AJAX bilan yuborish uchun id beramiz -->
               <form id="addContestForm">
                  <div class="modal-body">
                     <div class="form-group">
                        <label>Nomi *</label>
                        <input type="text" class="form-control" name="title" required>
                     </div>
                     <div class="form-group">
                        <label>Ta'rif *</label>
                        <textarea class="form-control" name="description" rows="4" required></textarea>
                     </div>
                     <div class="form-group">
                        <label>Boshlanish vaqti *</label>
                        <input type="datetime-local" class="form-control" name="start_time" required>
                     </div>
                     <div class="form-group">
                        <label>Tugash vaqti *</label>
                        <input type="datetime-local" class="form-control" name="end_time" required>
                     </div>
                     <div class="form-group">
                        <label>Holati *</label>
                        <select class="form-control" name="status" required>
                           <option value="0">Kutilmoqda</option>
                           <option value="1">Faol</option>
                           <option value="2">Tugagan</option>
                        </select>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-default" data-dismiss="modal">Bekor qilish</button>
                     <button type="submit" class="btn btn-primary">Saqlash</button>
                  </div>
               </form>
            </div>
         </div>
      </div>

      <!-- Tahrirlash modali -->
      <div class="modal fade" id="editContestModal" tabindex="-1" role="dialog">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Musobaqani Tahrirlash</h4>
               </div>
               <form id="editContestForm" method="POST">
                  <div class="modal-body">
                     <input type="hidden" name="id" value="">
                     <div class="form-group">
                        <label>Nomi *</label>
                        <input type="text" class="form-control" name="title" required>
                     </div>
                     <div class="form-group">
                        <label>Ta'rif *</label>
                        <textarea class="form-control" name="description" rows="4" required></textarea>
                     </div>
                     <div class="form-group">
                        <label>Boshlanish vaqti *</label>
                        <input type="datetime-local" class="form-control" name="start_time" required>
                     </div>
                     <div class="form-group">
                        <label>Tugash vaqti *</label>
                        <input type="datetime-local" class="form-control" name="end_time" required>
                     </div>
                     <div class="form-group">
                        <label>Holati *</label>
                        <select class="form-control" name="status" required>
                           <option value="0">Kutilmoqda</option>
                           <option value="1">Faol</option>
                           <option value="2">Tugagan</option>
                        </select>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-default" data-dismiss="modal">Bekor qilish</button>
                     <button type="submit" class="btn btn-primary">Saqlash</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <!-- Delete Confirmation Modal -->
      <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
         <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header bg-danger text-white">
                     <h5 class="modal-title">
                           <i class="fa fa-warning"></i> Musobaqani o'chirish
                     </h5>
                     <button type="button" class="close text-white" data-dismiss="modal">
                           <span>&times;</span>
                     </button>
                  </div>
                  <div class="modal-body">
                     <p class="text-center">
                           <i class="fa fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                     </p>
                     <h5 class="text-center">Ishonchingiz komilmi?</h5>
                     <p class="text-center">Bu musobaqani o'chirishni xohlaysizmi? Bu amalni qaytarib bo'lmaydi!</p>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">
                           <i class="fa fa-times"></i> Bekor qilish
                     </button>
                     <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                           <i class="fa fa-trash"></i> Ha, O'chirish
                     </button>
                  </div>
               </div>
         </div>
      </div>
      <!-- jQuery -->
      <script src="../js/jquery.min.js"></script>
      <script src="../js/bootstrap.min.js"></script>
      <script src="../js/perfect-scrollbar.min.js"></script>
      <!-- jQuery -->
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

      <!-- Toastr -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
      <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      
      <script>
         let deleteId = null;

         function confirmDelete(id) {
               deleteId = id;
               $('#deleteModal').modal('show');
         }

         document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (deleteId) {
                  fetch('delete/delete_contest.php?id=' + deleteId)
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
         function editContest(id, title, description, start_time, end_time, status) {
            // Modal inputlarini to'ldirish
            document.querySelector('#editContestModal input[name="id"]').value = id;
            document.querySelector('#editContestModal input[name="title"]').value = title;
            document.querySelector('#editContestModal textarea[name="description"]').value = description;
            document.querySelector('#editContestModal input[name="start_time"]').value = start_time.replace(' ', 'T');
            document.querySelector('#editContestModal input[name="end_time"]').value = end_time.replace(' ', 'T');
            document.querySelector('#editContestModal select[name="status"]').value = status;
         }
         $(document).ready(function () {
            $('#editContestForm').on('submit', function (e) {
               e.preventDefault(); // sahifa reload bo‘lmasin
               
               $.ajax({
                     url: 'update/update_contest.php',
                     type: 'POST',
                     data: $(this).serialize(),
                     dataType: 'json',
                     success: function(response) {
                        if (response.success) {
                           toastr.success(response.message);
                           $('#editContestModal').modal('hide');
                           $('#editContestForm')[0].reset();
                           setTimeout(() => location.reload(), 1500); 
                        } else {
                           toastr.error(response.message || 'Xatolik yuz berdi!');
                        }
                     },
                     error: function(xhr, status, error) {
                        toastr.error('Server bilan aloqa xatosi: ' + error);
                     }
               });
            });
         });

         var ps = new PerfectScrollbar('#sidebar');
         $(document).ready(function () {
            $('#addContestForm').on('submit', function (e) {
               e.preventDefault(); // sahifa reload bo‘lmasin
               
               $.ajax({
                     url: 'insert/add_olimpiads.php',
                     type: 'POST',
                     data: $(this).serialize(),
                     dataType: 'json',
                     success: function(response) {
                        if (response.success) {
                           toastr.success(response.message);
                           $('#addContestModal').modal('hide');
                           $('#addContestForm')[0].reset();
                           setTimeout(() => location.reload(), 2000); 
                        } else {
                           toastr.error(response.message || 'Xatolik yuz berdi!');
                        }
                     },
                     error: function(xhr, status, error) {
                        toastr.error('Server bilan aloqa xatosi: ' + error);
                     }
               });
            });
         });
      </script>

   </body>
</html>