<?php
   include_once '../config.php';
   session_start();
   $db = new Database();
   $problems = $db->get_data_by_table_all("problems");
?>
<style>
    .btn-warning {
    margin-right: 5px;
}

.modal-content {
    border-radius: 10px;
}

.modal-header.bg-danger {
    border-radius: 10px 10px 0 0;
}

.required {
    color: red;
}
</style>
<html lang="en">
   <?php include_once 'head.php'?>
   <head>
       <!-- DataTables CSS -->
       <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css" rel="stylesheet">
       <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css" rel="stylesheet">
       <link rel="stylesheet" href="../css/problems_style.css">
       
   </head>
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
                                                    <th>👥</th>
                                                    <th>✔️</th>
                                                    <th>❌</th>
                                                    <th>Test Cases</th>
                                                    <th>Update</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($problems as $problem): ?>
                                                <tr>
                                                    <td><strong><a href="solution.php?id=<?= (int)$problem['id'] ?>"><?= str_pad($problem['id'], 4, '0', STR_PAD_LEFT) ?></a></strong></td>
                                                    <td><strong><a href="solution.php?id=<?= (int)$problem['id'] ?>"><?= htmlspecialchars($problem['title']) ?></a></strong></td>
                                                    <td><span class="difficulty-badge difficulty-easy"><?=$problem['difficulty']?></span></td>
                                                    <td><span class="category-badge category-interactive"><?=$problem['category']?></span></td>
                                                    <td><span class="status-participants">2</span></td>
                                                    <td><span class="status-correct">5</span></td>
                                                    <td><span class="status-wrong">1343</span></td>
                                                    <td>
                                                        <a href="test_views.php?id=<?= (int)$problem['id'] ?>" class="btn btn-sm btn-info">
                                                            <i class="fa fa-eye"></i> Testni Ko'rish
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="update.php?id=<?= (int)$problem['id'] ?>" class="btn btn-sm btn-warning">
                                                            <i class="fa fa-edit"></i> Tahrirlash
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <button onclick="confirmDelete(<?= (int)$problem['id'] ?>)" class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash"></i> O'chirish
                                                        </button>
                                                    </td>
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
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fa fa-warning"></i> Masalani O'chirish
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
                    <p class="text-center">Bu masalani o'chirishni xohlaysizmi? Bu amalni qaytarib bo'lmaydi!</p>
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

    
      <script src="../js/jquery.min.js"></script>
      <script src="../js/bootstrap.min.js"></script>
      <script src="../js/perfect-scrollbar.min.js"></script>
      
      <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
      <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
      <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
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
                fetch('delete_problem.php?id=' + deleteId)
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
         
         $(document).ready(function() {
            var table = $('#problemsTable').DataTable({
                "responsive": true,
                "processing": true,
                "pageLength": 10,
                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Hammasi"]],
                
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
                
                "columnDefs": [
                    {
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
                        "targets": [3], 
                        "type": "string"
                    },
                    {
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
                        "targets": [5, 6, 7, 8], 
                        "type": "num"
                    }
                ],
                
                "order": [[0, "desc"]],
                
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                
                "autoWidth": false,
                "stateSave": true,
                "stateDuration": 300,
                
                "initComplete": function(settings, json) {
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