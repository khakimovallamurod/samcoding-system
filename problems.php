<?php
   include_once 'config.php';
   session_start();

?>
<html lang="en">
   <?php include_once 'includes/head.php'?>
   <body class="dashboard dashboard_1">
      <div class="full_container">
         <div class="inner_container">
            <!-- Sidebar  -->
            <?php include_once 'includes/sidebar.php'?>
            <!-- end sidebar -->
            <!-- right content -->
            <div id="content">
               <!-- topbar -->
               <?php include_once 'includes/topbar.php'?>
               <!-- end topbar -->
               <!-- dashboard inner -->
               <div class="midde_cont">
                  <div class="container-fluid">
                     <div class="row column_title">
                        <div class="col-md-12">
                           <div class="page_title">
                              <h2>Masalalar ro‘yxati</h2>
                           </div>
                        </div>
                     </div>
                     <div class="row column1">
                        <div class="col-md-12">
                            <div class="white_shd full margin_bottom_30">
                                <div class="full graph_head">
                                    <div class="heading1 margin_0">
                                    <h2>Masalalar ro‘yxati</h2>
                                    </div>
                                </div>
                                <div class="table_section padding_infor_info">
                                    <div class="table-responsive-sm">
                                    <table class="table table-striped table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Tartib raqam</th>
                                                <th>Yechim</th>
                                                <th>Nomi</th>
                                                <th>Qiyinchiligi</th>
                                                <th>Toifasi</th>
                                                <th>⭐</th>
                                                <th>👥</th>
                                                <th>✔️</th>
                                                <th>❌</th>
                                                <th>Reyting</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>0912</td>
                                                <td><a href="solution.php?id=912" class="btn btn-sm btn-primary">Ko‘rish</a></td>
                                                <td>Bubble shooter.#1</td>
                                                <td>20%</td>
                                                <td>Interactive</td>
                                                <td>3.5</td>
                                                <td>2</td>
                                                <td>5</td>
                                                <td>1343</td>
                                                <td>0.4</td>
                                            </tr>
                                            <tr>
                                                <td>0912</td>
                                                <td><a href="solution.php?id=912" class="btn btn-sm btn-primary">Ko‘rish</a></td>
                                                <td>Bubble shooter.#1</td>
                                                <td>20%</td>
                                                <td>Interactive</td>
                                                <td>3.5</td>
                                                <td>2</td>
                                                <td>5</td>
                                                <td>1343</td>
                                                <td>0.4</td>
                                            </tr>
                                            <!-- Boshqa masalalarni ham shu yerga qo‘shasiz -->
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
      <!-- jQuery -->
      <script src="js/jquery.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      
      <script src="js/perfect-scrollbar.min.js"></script>
      <script>
         var ps = new PerfectScrollbar('#sidebar');
      </script>
      
   </body>
</html>