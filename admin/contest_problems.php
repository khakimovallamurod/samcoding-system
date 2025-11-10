<?php
   include_once '../config.php';
   session_start();
   $db = new Database();
   $contest_id = $_GET['contest_id'];
   $contest_problems = $db->get_data_by_table_all("contest_problems", " where contest_id=$contest_id");
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
                              <h2>Masalalar</h2>
                           </div>
                        </div>
                     </div>
                     <!-- Orqaga qaytish va masala qo'shish -->
                     <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-6">
                           <a href="olympiads.php" class="btn btn-default">
                              <i class="fa fa-arrow-left"></i> Orqaga
                           </a>
                        </div>
                        <div class="col-md-6 text-right">
                           <button class="btn btn-primary" onclick="window.location='add_contest_problem.php?contest_id=<?=$contest_id?>'">
                              <i class="fa fa-plus"></i> Masala Qo'shish
                           </button>
                        </div>
                     </div>
                     <!-- Musoboqa masalalari jadvali -->
                     <div class="row column1">
                        <div class="col-md-12">
                            <div class="white_shd full margin_bottom_30">
                                <div class="table_section padding_infor_info">
                                    <div class="table-responsive-sm">
                                        <table id="problemsTable" class="table table-striped table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nomi</th>
                                                    <th>Qiyinchiligi</th>
                                                    <th>Toifasi</th>
                                                    <th>Test Cases</th>
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
                                                    <td><strong><a href="cn_solution.php?id=<?= (int)$problem['id'] ?>"><?= $letter ?></a></strong></td>
                                                    <td><strong><a href="cn_solution.php?id=<?= (int)$problem['id'] ?>"><?= htmlspecialchars($problem['title']) ?></a></strong></td>
                                                    <td><span class="difficulty-badge difficulty-easy"><?=$problem['difficulty']?></span></td>
                                                    <td><span class="category-badge category-interactive"><?=$problem['category']?></span></td>
                                                    <td>
                                                        <a href="cn_test_views.php?id=<?= (int)$problem['id'] ?>" class="btn btn-sm btn-info">
                                                            <i class="fa fa-eye"></i> Testni Ko'rish
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="cn_update.php?id=<?= (int)$problem['id'] ?>" class="btn btn-sm btn-warning">
                                                            <i class="fa fa-edit"></i> Tahrirlash
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <button onclick="confirmDelete(<?= (int)$problem['id'] ?>)" class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash"></i> O'chirish
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
                  </div>
               </div>
            </div>
         </div>
      </div>
        <!-- 🧩 O‘chirishni tasdiqlash modali -->
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