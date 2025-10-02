<?php
   include_once 'config.php';
   session_start();
   if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
      header("Location: auth/login.php");
      exit;
   }
   $db = new Database();
   $problems = $db->get_data_by_table_all("problems");
?>
<html lang="en">
   <?php include_once 'includes/head.php'?>
   <head>
       <!-- DataTables CSS -->
       <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css" rel="stylesheet">
       <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css" rel="stylesheet">
       <link rel="stylesheet" href="css/problems_style.css">
       
   </head>
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
                     </div>
                     <div class="row column1">
                        <div class="col-md-12">
                            <div class="white_shd full margin_bottom_30">
                                <div class="full graph_head">
                                    <div class="heading1 margin_0">
                                        <h2><strong>Masalalar ro'yxati</strong></h2>
                                    </div>
                                </div>
                                <div class="table_section padding_infor_info">
                                    <div class="table-responsive-sm">
                                        <table id="problemsTable" class="table table-striped table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Tartib raqam</th>
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
                                                <?php foreach ($problems as $problem): ?>
                                                <tr>
                                                    <td><strong><a href="solution.php?id=<?= (int)$problem['id'] ?>"><?= str_pad($problem['id'], 4, '0', STR_PAD_LEFT) ?></a></strong></td>
                                                    <td><strong><a href="solution.php?id=<?= (int)$problem['id'] ?>"><?= htmlspecialchars($problem['title']) ?></a></strong></td>
                                                    <td><span class="difficulty-badge difficulty-easy"><?=$problem['difficulty']?>%</span></td>
                                                    <td><span class="category-badge category-interactive"><?=$problem['category']?></span></td>
                                                    <td><span class="rating-stars">★★★<span style="color:#ddd;">★★</span></span></td>
                                                    <td><span class="status-participants">2</span></td>
                                                    <td><span class="status-correct">5</span></td>
                                                    <td><span class="status-wrong">1343</span></td>
                                                    <td><strong>0.4</strong></td>
                                                </tr>
                                                <?php endforeach; ?>
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
      
      <!-- jQuery -->
      <script src="js/jquery.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <script src="js/perfect-scrollbar.min.js"></script>
      
      <!-- DataTables JS -->
      <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
      <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
      <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
      
      <script>
         // Perfect Scrollbar
         var ps = new PerfectScrollbar('#sidebar');
         
         // DataTables initialization
         $(document).ready(function() {
            var table = $('#problemsTable').DataTable({
                "responsive": true,
                "processing": true,
                "pageLength": 10,
                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Hammasi"]],
                
                // Language settings
                "language": {
                    "lengthMenu": "Sahifada _MENU_ ta yozuv ko'rsatish",
                    "zeroRecords": "Hech qanday natija topilmadi",
                    "info": "_START_ dan _END_ gacha, jami _TOTAL_ ta yozuv",
                    "infoEmpty": "Yozuvlar mavjud emas",
                    "infoFiltered": "(_MAX_ ta yozuvdan filtrlandi)",
                    "search": "Qidirish:",
                    "paginate": {
                        "first": "Birinchi",
                        "last": "Oxirgi",
                        "next": "Keyingi",
                        "previous": "Oldingi"
                    },
                    "processing": "Yuklanmoqda..."
                },
                
                // Column definitions - yangilangan ustun indexlari
                "columnDefs": [
                    {
                        // Qiyinlik ustuni (index 2) - percentage sorting
                        "targets": [2], 
                        "type": "num",
                        "render": function(data, type, row) {
                            if (type === 'sort' || type === 'type') {
                                // Extract number from percentage for sorting
                                var match = data.match(/(\d+)%/);
                                return match ? parseInt(match[1]) : 0;
                            }
                            return data;
                        }
                    },
                    {
                        // Toifa ustuni (index 3) - string sorting
                        "targets": [3], 
                        "type": "string"
                    },
                    {
                        // Rating ustuni (index 4) - star rating sorting
                        "targets": [4], 
                        "type": "num",
                        "render": function(data, type, row) {
                            if (type === 'sort' || type === 'type') {
                                // Count filled stars for sorting
                                var filledStars = (data.match(/★/g) || []).length;
                                var emptyStars = (data.match(/style="color:#ddd;">★/g) || []).length;
                                return filledStars - emptyStars;
                            }
                            return data;
                        }
                    },
                    {
                        // Numeric ustunlar - participants, correct, wrong, rating
                        "targets": [5, 6, 7, 8], 
                        "type": "num"
                    }
                ],
                
                // Default ordering - ID bo'yicha kamayuvchi
                "order": [[0, "desc"]],
                
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                
                "autoWidth": false,
                "stateSave": true,
                "stateDuration": 300,
                
                // Add custom filters after initialization
                "initComplete": function(settings, json) {
                    // Category filter - Toifa ustuni (index 3)
                    var categoryFilter = $('<select class="filter-dropdown">' +
                        '<option value="">Barcha toifalar</option>' +
                        '<option value="Interactive">Interactive</option>' +
                        '<option value="Algorithm">Algorithm</option>' +
                        '<option value="Math">Math</option>' +
                        '<option value="Graph">Graph</option>' +
                        '</select>');
                    
                    $('.dataTables_filter label').append(categoryFilter);
                    
                    categoryFilter.on('change', function() {
                        var val = this.value;
                        table.column(3).search(val).draw(); // Toifa ustuni index 3
                    });
                    
                    // Difficulty filter - Qiyinlik ustuni (index 2)
                    var difficultyFilter = $('<select class="filter-dropdown">' +
                        '<option value="">Barcha qiyinlik</option>' +
                        '<option value="easy">Oson (0-30%)</option>' +
                        '<option value="medium">O\'rta (31-70%)</option>' +
                        '<option value="hard">Qiyin (71-100%)</option>' +
                        '</select>');
                    
                    $('.dataTables_filter label').append(difficultyFilter);
                    
                    difficultyFilter.on('change', function() {
                        var val = this.value;
                        var searchTerm = '';
                        
                        if (val === 'easy') {
                            searchTerm = '^([0-2][0-9]|30)%';
                        } else if (val === 'medium') {
                            searchTerm = '^([3-6][0-9]|70)%';
                        } else if (val === 'hard') {
                            searchTerm = '^([7-9][0-9]|100)%';
                        }
                        
                        table.column(2).search(searchTerm, true, false).draw(); 
                    });
                    
                    var ratingFilter = $('<select class="filter-dropdown">' +
                        '<option value="">Barcha reytinglar</option>' +
                        '<option value="5stars">5 yulduz</option>' +
                        '<option value="4stars">4 yulduz</option>' +
                        '<option value="3stars">3 yulduz</option>' +
                        '<option value="2stars">2 yulduz va past</option>' +
                        '</select>');
                    
                    $('.dataTables_filter label').append(ratingFilter);
                    
                    ratingFilter.on('change', function() {
                        var val = this.value;
                        var searchTerm = '';
                        
                        if (val === '5stars') {
                            searchTerm = '★★★★★';
                        } else if (val === '4stars') {
                            searchTerm = '★★★★.*★.*ddd|★★★★$';
                        } else if (val === '3stars') {
                            searchTerm = '★★★.*★.*ddd.*★.*ddd|★★★$';
                        } else if (val === '2stars') {
                            searchTerm = '★★.*★.*ddd.*★.*ddd.*★.*ddd|★★$|★[^★]*ddd';
                        }
                        
                        table.column(4).search(searchTerm, true, false).draw(); // Rating ustuni index 4
                    });
                }
            });
            // Search highlighting
            table.on('draw.dt', function() {
                var searchTerm = $('#problemsTable_filter input').val();
                if (searchTerm) {
                    $('#problemsTable tbody tr').each(function() {
                        var $row = $(this);
                        $row.find('td').each(function() {
                            var $cell = $(this);
                            var cellText = $cell.text();
                            if (cellText.toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1 && 
                                !$cell.find('.btn').length && 
                                !$cell.find('.difficulty-badge').length && 
                                !$cell.find('.category-badge').length &&
                                !$cell.find('.rating-stars').length) {
                                var highlightedText = cellText.replace(
                                    new RegExp('(' + searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi'),
                                    '<mark style="background-color: #fff3cd; padding: 1px 2px;">$1</mark>'
                                );
                                $cell.html(highlightedText);
                            }
                        });
                    });
                }
            });
            
            $.extend($.fn.dataTable.ext.type.order, {
                "id-pre": function (a) {
                    return parseInt(a.replace(/\D/g, ''), 10) || 0;
                }
            });
            table.column(0).type('id');
        });
      </script>
   </body>
</html>