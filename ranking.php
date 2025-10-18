<?php
   include_once 'config.php';
   
?>
<html lang="en">
   <?php include_once 'includes/head.php'?>
   <link rel="stylesheet" href="css/reyting_style.css">
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
                              <h2>Reytinglar </h2>
                           </div>
                        </div>
                     </div>
                     <div class="row column1">
                        <!-- Top 3 o'rinlar -->
                        <div class="col-md-12">
                           <div class="white_shd full margin_bottom_30">
                              <div class="full graph_head">
                                 <div class="heading1 margin_0">
                                    <h2><strong>Top 3 Liderlar</strong></h2>
                                 </div>
                              </div>
                              <div class="full padding_infor_info">
                                 <div class="row">
                                    <!-- 2-o'rin -->
                                    <div class="col-md-4">
                                       <div class="podium-card podium-silver">
                                          <div class="podium-number">2</div>
                                          <div class="podium-avatar">
                                             <img src="images/user-default.png" alt="User">
                                          </div>
                                          <h4 class="podium-name">Ali Valiyev</h4>
                                          <p class="podium-points">850 ball</p>
                                          <p class="podium-solved">42 masala yechilgan</p>
                                       </div>
                                    </div>
                                    
                                    <!-- 1-o'rin -->
                                    <div class="col-md-4">
                                       <div class="podium-card podium-gold">
                                          <div class="podium-crown">👑</div>
                                          <div class="podium-number">1</div>
                                          <div class="podium-avatar">
                                             <img src="images/user-default.png" alt="User">
                                          </div>
                                          <h4 class="podium-name">Sardor Karimov</h4>
                                          <p class="podium-points">1250 ball</p>
                                          <p class="podium-solved">68 masala yechilgan</p>
                                       </div>
                                    </div>
                                    
                                    <!-- 3-o'rin -->
                                    <div class="col-md-4">
                                       <div class="podium-card podium-bronze">
                                          <div class="podium-number">3</div>
                                          <div class="podium-avatar">
                                             <img src="images/user-default.png" alt="User">
                                          </div>
                                          <h4 class="podium-name">Dinara Usmonova</h4>
                                          <p class="podium-points">720 ball</p>
                                          <p class="podium-solved">35 masala yechilgan</p>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- Umumiy reyting jadvali -->
                        <div class="col-md-12">
                           <div class="white_shd full margin_bottom_30">
                              <div class="full graph_head">
                                 <div class="heading1 margin_0">
                                    <h2><strong>Umumiy Reyting</strong></h2>
                                 </div>
                              </div>
                              <div class="table_section padding_infor_info">
                                 <!-- Filter qismi -->
                                 <div class="filter-section">
                                    <div class="row">
                                       <div class="col-md-3">
                                          <label>Qidirish:</label>
                                          <input type="text" id="searchUser" class="filter-input" placeholder="Foydalanuvchi ismi...">
                                       </div>
                                       <div class="col-md-2">
                                          <label>Ball bo'yicha:</label>
                                          <select id="filterPoints" class="filter-select">
                                             <option value="all">Hammasi</option>
                                             <option value="1000+">1000+ ball</option>
                                             <option value="500-1000">500-1000 ball</option>
                                             <option value="0-500">0-500 ball</option>
                                          </select>
                                       </div>
                                       <div class="col-md-2">
                                          <label>Yechilgan:</label>
                                          <select id="filterSolved" class="filter-select">
                                             <option value="all">Hammasi</option>
                                             <option value="50+">50+ masala</option>
                                             <option value="20-50">20-50 masala</option>
                                             <option value="0-20">0-20 masala</option>
                                          </select>
                                       </div>
                                       <div class="col-md-2">
                                          <label>Saralash:</label>
                                          <select id="sortBy" class="filter-select">
                                             <option value="rank">O'rin</option>
                                             <option value="points">Ball</option>
                                             <option value="solved">Yechilgan</option>
                                             <option value="percent">Foiz</option>
                                          </select>
                                       </div>
                                       <div class="col-md-3">
                                          <label>&nbsp;</label>
                                          <button class="filter-button" onclick="resetFilters()">🔄 Tozalash</button>
                                       </div>
                                    </div>
                                 </div>
                                 
                                 <div class="table-responsive">
                                    <table class="table table-hover" id="ratingTable">
                                       <thead>
                                          <tr>
                                             <th>O'rin</th>
                                             <th>Foydalanuvchi</th>
                                             <th>Ball</th>
                                             <th>Yechilgan</th>
                                             <th>Urinishlar</th>
                                             <th>Foiz</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          <tr class="rank-row">
                                             <td><span class="rank-badge rank-1">1</span></td>
                                             <td>
                                                <div class="user-info">
                                                   <img src="images/user-default.png" alt="User" class="user-avatar-small">
                                                   <strong>Sardor Karimov</strong>
                                                </div>
                                             </td>
                                             <td><span class="points-badge">1250</span></td>
                                             <td><span class="solved-badge">68</span></td>
                                             <td>145</td>
                                             <td><span class="percent-badge percent-high">46.9%</span></td>
                                          </tr>
                                          <tr class="rank-row">
                                             <td><span class="rank-badge rank-2">2</span></td>
                                             <td>
                                                <div class="user-info">
                                                   <img src="images/user-default.png" alt="User" class="user-avatar-small">
                                                   <strong>Ali Valiyev</strong>
                                                </div>
                                             </td>
                                             <td><span class="points-badge">850</span></td>
                                             <td><span class="solved-badge">42</span></td>
                                             <td>98</td>
                                             <td><span class="percent-badge percent-high">42.9%</span></td>
                                          </tr>
                                          <tr class="rank-row">
                                             <td><span class="rank-badge rank-3">3</span></td>
                                             <td>
                                                <div class="user-info">
                                                   <img src="images/user-default.png" alt="User" class="user-avatar-small">
                                                   <strong>Dinara Usmonova</strong>
                                                </div>
                                             </td>
                                             <td><span class="points-badge">720</span></td>
                                             <td><span class="solved-badge">35</span></td>
                                             <td>87</td>
                                             <td><span class="percent-badge percent-medium">40.2%</span></td>
                                          </tr>
                                          <tr class="rank-row">
                                             <td><span class="rank-badge">4</span></td>
                                             <td>
                                                <div class="user-info">
                                                   <img src="images/user-default.png" alt="User" class="user-avatar-small">
                                                   <strong>Javohir Rahimov</strong>
                                                </div>
                                             </td>
                                             <td><span class="points-badge">650</span></td>
                                             <td><span class="solved-badge">28</span></td>
                                             <td>76</td>
                                             <td><span class="percent-badge percent-medium">36.8%</span></td>
                                          </tr>
                                          <tr class="rank-row">
                                             <td><span class="rank-badge">5</span></td>
                                             <td>
                                                <div class="user-info">
                                                   <img src="images/user-default.png" alt="User" class="user-avatar-small">
                                                   <strong>Malika Ahmedova</strong>
                                                </div>
                                             </td>
                                             <td><span class="points-badge">580</span></td>
                                             <td><span class="solved-badge">24</span></td>
                                             <td>68</td>
                                             <td><span class="percent-badge percent-medium">35.3%</span></td>
                                          </tr>
                                          <tr class="rank-row">
                                             <td><span class="rank-badge">6</span></td>
                                             <td>
                                                <div class="user-info">
                                                   <img src="images/user-default.png" alt="User" class="user-avatar-small">
                                                   <strong>Bobur Ergashev</strong>
                                                </div>
                                             </td>
                                             <td><span class="points-badge">520</span></td>
                                             <td><span class="solved-badge">22</span></td>
                                             <td>71</td>
                                             <td><span class="percent-badge percent-low">31.0%</span></td>
                                          </tr>
                                          <tr class="rank-row">
                                             <td><span class="rank-badge">7</span></td>
                                             <td>
                                                <div class="user-info">
                                                   <img src="images/user-default.png" alt="User" class="user-avatar-small">
                                                   <strong>Nigora Ismoilova</strong>
                                                </div>
                                             </td>
                                             <td><span class="points-badge">480</span></td>
                                             <td><span class="solved-badge">19</span></td>
                                             <td>65</td>
                                             <td><span class="percent-badge percent-low">29.2%</span></td>
                                          </tr>
                                          <tr class="rank-row">
                                             <td><span class="rank-badge">8</span></td>
                                             <td>
                                                <div class="user-info">
                                                   <img src="images/user-default.png" alt="User" class="user-avatar-small">
                                                   <strong>Anvar Toshmatov</strong>
                                                </div>
                                             </td>
                                             <td><span class="points-badge">450</span></td>
                                             <td><span class="solved-badge">18</span></td>
                                             <td>62</td>
                                             <td><span class="percent-badge percent-low">29.0%</span></td>
                                          </tr>
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
         function resetFilters() {
            document.getElementById('searchUser').value = '';
            document.getElementById('filterPoints').value = 'all';
            document.getElementById('filterSolved').value = 'all';
            document.getElementById('sortBy').value = 'rank';
         }

      </script>
      
   </body>
</html>