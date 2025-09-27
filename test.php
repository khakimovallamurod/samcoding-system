<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masalalar ro'yxati</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .dashboard_1 {
            background: #f8f9fa;
        }
        
        .white_shd {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .full.graph_head {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            margin-bottom: 0;
        }
        
        .full.graph_head h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .padding_infor_info {
            padding: 20px;
        }
        
        /* DataTables customizations */
        .dataTables_wrapper {
            padding-top: 20px;
        }
        
        .dataTables_length,
        .dataTables_filter {
            margin-bottom: 20px;
        }
        
        .dataTables_filter input {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 8px 15px;
            transition: all 0.3s;
            background: #f8f9fa;
        }
        
        .dataTables_filter input:focus {
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        /* Table styling */
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background: #495057;
            color: white;
            border: none;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            padding: 15px 8px;
            font-size: 0.9rem;
        }
        
        .table tbody td {
            text-align: center;
            vertical-align: middle;
            padding: 12px 8px;
            border-color: #e9ecef;
        }
        
        /* Custom hover color - Blue instead of green */
        .table tbody tr:hover {
            background-color: #e3f2fd !important;
            transform: translateY(-1px);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8f9fa;
        }
        
        /* Difficulty badges */
        .difficulty-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            min-width: 60px;
        }
        
        .difficulty-easy {
            background: #d4edda;
            color: #155724;
        }
        
        .difficulty-medium {
            background: #fff3cd;
            color: #856404;
        }
        
        .difficulty-hard {
            background: #f8d7da;
            color: #721c24;
        }
        
        /* Category badges */
        .category-badge {
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .category-interactive {
            background: #e3f2fd;
            color: #1565c0;
        }
        
        .category-algorithm {
            background: #f3e5f5;
            color: #7b1fa2;
        }
        
        .category-math {
            background: #fff8e1;
            color: #ef6c00;
        }
        
        .category-graph {
            background: #e8f5e8;
            color: #2e7d32;
        }
        
        /* Rating stars */
        .rating-stars {
            color: #ffc107;
            font-size: 0.9rem;
        }
        
        /* Status icons */
        .status-icon {
            font-size: 1.1rem;
        }
        
        .status-correct {
            color: #28a745;
        }
        
        .status-wrong {
            color: #dc3545;
        }
        
        .status-participants {
            color: #6c757d;
        }
        
        /* Button styling */
        .btn-view {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-view:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .table thead th,
            .table tbody td {
                padding: 8px 4px;
                font-size: 0.8rem;
            }
            
            .btn-view {
                padding: 4px 8px;
                font-size: 0.7rem;
            }
        }
        
        /* DataTables pagination styling */
        .dataTables_paginate .paginate_button {
            padding: 8px 12px !important;
            margin: 0 2px;
            border-radius: 6px !important;
            border: 1px solid #dee2e6 !important;
            background: white !important;
            color: #495057 !important;
        }
        
        .dataTables_paginate .paginate_button:hover {
            background: #e3f2fd !important;
            border-color: #667eea !important;
            color: #667eea !important;
        }
        
        .dataTables_paginate .paginate_button.current {
            background: linear-gradient(45deg, #667eea, #764ba2) !important;
            border-color: #667eea !important;
            color: white !important;
        }
        
        /* Loading state */
        .dataTables_processing {
            background: rgba(255,255,255,0.9) !important;
            color: #667eea !important;
            font-weight: 600 !important;
        }
        
        /* Info styling */
        .dataTables_info {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        /* Length selector */
        .dataTables_length select {
            border: 2px solid #e9ecef;
            border-radius: 6px;
            padding: 4px 8px;
            background: #f8f9fa;
        }
        
        /* Search highlight */
        .dataTables_filter {
            text-align: right;
        }
        
        .dataTables_filter label {
            font-weight: 600;
            color: #495057;
        }
    </style>
</head>
<body class="dashboard dashboard_1">
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <h2><i class="fas fa-code me-2"></i>Masalalar ro'yxati</h2>
                    </div>
                    <div class="padding_infor_info">
                        <div class="table-responsive">
                            <table id="problemsTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Yechim</th>
                                        <th>Nomi</th>
                                        <th>Qiyinlik</th>
                                        <th>Toifa</th>
                                        <th><i class="fas fa-star"></i></th>
                                        <th><i class="fas fa-users"></i></th>
                                        <th><i class="fas fa-check status-correct"></i></th>
                                        <th><i class="fas fa-times status-wrong"></i></th>
                                        <th>Reyting</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>0912</strong></td>
                                        <td><a href="solution.php?id=912" class="btn-view"><i class="fas fa-eye me-1"></i>Ko'rish</a></td>
                                        <td><strong>Bubble Shooter #1</strong></td>
                                        <td><span class="difficulty-badge difficulty-easy">20%</span></td>
                                        <td><span class="category-badge category-interactive">Interactive</span></td>
                                        <td><span class="rating-stars">★★★<span style="color:#ddd;">★★</span></span></td>
                                        <td><span class="status-participants">2</span></td>
                                        <td><span class="status-correct">5</span></td>
                                        <td><span class="status-wrong">1343</span></td>
                                        <td><strong>0.4</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>0913</strong></td>
                                        <td><a href="solution.php?id=913" class="btn-view"><i class="fas fa-eye me-1"></i>Ko'rish</a></td>
                                        <td><strong>Binary Search Tree</strong></td>
                                        <td><span class="difficulty-badge difficulty-hard">85%</span></td>
                                        <td><span class="category-badge category-algorithm">Algorithm</span></td>
                                        <td><span class="rating-stars">★★★★<span style="color:#ddd;">★</span></span></td>
                                        <td><span class="status-participants">45</span></td>
                                        <td><span class="status-correct">23</span></td>
                                        <td><span class="status-wrong">892</span></td>
                                        <td><strong>2.8</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>0914</strong></td>
                                        <td><a href="solution.php?id=914" class="btn-view"><i class="fas fa-eye me-1"></i>Ko'rish</a></td>
                                        <td><strong>Matrix Multiplication</strong></td>
                                        <td><span class="difficulty-badge difficulty-medium">55%</span></td>
                                        <td><span class="category-badge category-math">Math</span></td>
                                        <td><span class="rating-stars">★★★★★</span></td>
                                        <td><span class="status-participants">78</span></td>
                                        <td><span class="status-correct">67</span></td>
                                        <td><span class="status-wrong">234</span></td>
                                        <td><strong>4.2</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>0915</strong></td>
                                        <td><a href="solution.php?id=915" class="btn-view"><i class="fas fa-eye me-1"></i>Ko'rish</a></td>
                                        <td><strong>Graph Traversal DFS</strong></td>
                                        <td><span class="difficulty-badge difficulty-hard">78%</span></td>
                                        <td><span class="category-badge category-graph">Graph</span></td>
                                        <td><span class="rating-stars">★★★★<span style="color:#ddd;">★</span></span></td>
                                        <td><span class="status-participants">156</span></td>
                                        <td><span class="status-correct">89</span></td>
                                        <td><span class="status-wrong">567</span></td>
                                        <td><strong>3.7</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>0916</strong></td>
                                        <td><a href="solution.php?id=916" class="btn-view"><i class="fas fa-eye me-1"></i>Ko'rish</a></td>
                                        <td><strong>Simple Calculator</strong></td>
                                        <td><span class="difficulty-badge difficulty-easy">15%</span></td>
                                        <td><span class="category-badge category-interactive">Interactive</span></td>
                                        <td><span class="rating-stars">★★<span style="color:#ddd;">★★★</span></span></td>
                                        <td><span class="status-participants">234</span></td>
                                        <td><span class="status-correct">198</span></td>
                                        <td><span class="status-wrong">45</span></td>
                                        <td><strong>1.8</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>0917</strong></td>
                                        <td><a href="solution.php?id=917" class="btn-view"><i class="fas fa-eye me-1"></i>Ko'rish</a></td>
                                        <td><strong>Dynamic Programming Fibonacci</strong></td>
                                        <td><span class="difficulty-badge difficulty-medium">45%</span></td>
                                        <td><span class="category-badge category-algorithm">Algorithm</span></td>
                                        <td><span class="rating-stars">★★★<span style="color:#ddd;">★★</span></span></td>
                                        <td><span class="status-participants">123</span></td>
                                        <td><span class="status-correct">87</span></td>
                                        <td><span class="status-wrong">456</span></td>
                                        <td><strong>3.1</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>0918</strong></td>
                                        <td><a href="solution.php?id=918" class="btn-view"><i class="fas fa-eye me-1"></i>Ko'rish</a></td>
                                        <td><strong>Prime Number Generator</strong></td>
                                        <td><span class="difficulty-badge difficulty-medium">60%</span></td>
                                        <td><span class="category-badge category-math">Math</span></td>
                                        <td><span class="rating-stars">★★★★<span style="color:#ddd;">★</span></span></td>
                                        <td><span class="status-participants">89</span></td>
                                        <td><span class="status-correct">45</span></td>
                                        <td><span class="status-wrong">234</span></td>
                                        <td><strong>2.9</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>0919</strong></td>
                                        <td><a href="solution.php?id=919" class="btn-view"><i class="fas fa-eye me-1"></i>Ko'rish</a></td>
                                        <td><strong>Minimum Spanning Tree</strong></td>
                                        <td><span class="difficulty-badge difficulty-hard">92%</span></td>
                                        <td><span class="category-badge category-graph">Graph</span></td>
                                        <td><span class="rating-stars">★★★★★</span></td>
                                        <td><span class="status-participants">34</span></td>
                                        <td><span class="status-correct">12</span></td>
                                        <td><span class="status-wrong">678</span></td>
                                        <td><strong>4.8</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#problemsTable').DataTable({
                // Basic configuration
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
                
                // Column definitions
                "columnDefs": [
                    {
                        "targets": [1], // Yechim column
                        "orderable": false,
                        "searchable": false
                    },
                    {
                        "targets": [3], // Qiyinlik column
                        "type": "num",
                        "render": function(data, type, row) {
                            if (type === 'sort' || type === 'type') {
                                return parseInt(data.replace('%', ''));
                            }
                            return data;
                        }
                    },
                    {
                        "targets": [5, 6, 7, 8, 9], // Numeric columns
                        "type": "num"
                    }
                ],
                
                // Default ordering
                "order": [[0, "desc"]],
                
                // Styling
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                       '<"row"<"col-sm-12"tr>>' +
                       '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                
                // Additional features
                "autoWidth": false,
                "stateSave": true,
                "stateDuration": 300, // 5 minutes
                
                // Custom search function for category filtering
                "initComplete": function(settings, json) {
                    // Add category filter dropdown
                    var categoryFilter = $('<select class="form-select form-select-sm ms-2" style="width:auto; display:inline-block;">' +
                        '<option value="">Barcha toifalar</option>' +
                        '<option value="Interactive">Interactive</option>' +
                        '<option value="Algorithm">Algorithm</option>' +
                        '<option value="Math">Math</option>' +
                        '<option value="Graph">Graph</option>' +
                        '</select>');
                    
                    $('.dataTables_filter label').append(categoryFilter);
                    
                    // Category filter functionality
                    categoryFilter.on('change', function() {
                        var val = this.value;
                        $('#problemsTable').DataTable().column(4).search(val).draw();
                    });
                    
                    // Add difficulty filter
                    var difficultyFilter = $('<select class="form-select form-select-sm ms-2" style="width:auto; display:inline-block;">' +
                        '<option value="">Barcha qiyinlik</option>' +
                        '<option value="easy">Oson (0-30%)</option>' +
                        '<option value="medium">O\'rta (31-70%)</option>' +
                        '<option value="hard">Qiyin (71-100%)</option>' +
                        '</select>');
                    
                    $('.dataTables_filter label').append(difficultyFilter);
                    
                    // Difficulty filter functionality
                    difficultyFilter.on('change', function() {
                        var val = this.value;
                        var searchTerm = '';
                        
                        if (val === 'easy') {
                            searchTerm = '^([0-2][0-9]|30)%$';
                        } else if (val === 'medium') {
                            searchTerm = '^([3-6][0-9]|70)%$';
                        } else if (val === 'hard') {
                            searchTerm = '^([7-9][0-9]|100)%$';
                        }
                        
                        $('#problemsTable').DataTable().column(3).search(searchTerm, true, false).draw();
                    });
                }
            });
            
            // Custom search highlighting
            $('#problemsTable').on('draw.dt', function() {
                var searchTerm = $('#problemsTable_filter input').val();
                if (searchTerm) {
                    $('#problemsTable tbody tr').each(function() {
                        var $row = $(this);
                        $row.find('td').each(function() {
                            var $cell = $(this);
                            var cellText = $cell.text();
                            if (cellText.toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1 && !$cell.find('.btn-view').length) {
                                var highlightedText = cellText.replace(
                                    new RegExp('(' + searchTerm + ')', 'gi'),
                                    '<mark>$1</mark>'
                                );
                                $cell.html(highlightedText);
                            }
                        });
                    });
                }
            });
        });
    </script>
</body>
</html>