<?php
    session_start();
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        header("Location: ../auth/login.php");
        exit;
    }
    
    include_once "../config.php";
    $db = new Database();
    
    $problem_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($problem_id == 0) {
        header("Location: problems.php");
        exit;
    }
    
    // Get problem details
    $problem = $db->get_data_by_table('problems', ['id' => $problem_id]);
    if (empty($problem)) {
        header("Location: problems.php");
        exit;
    }
    // Get test cases
    $tests = $db->get_data_by_table_all('tests', "Where problem_id = $problem_id");
?>
<html lang="en">
   <?php include_once 'head.php'?>
   <link rel="stylesheet" href="../css/problems_style.css">
   <style>
   .test-preview {
       max-width: 300px;
       max-height: 100px;
       overflow: auto;
       background: #f8f9fa;
       padding: 8px;
       border-radius: 4px;
       font-family: 'Courier New', monospace;
       font-size: 12px;
       white-space: pre-wrap;
       word-wrap: break-word;
   }
   
   .test-actions {
       white-space: nowrap;
   }
   
   .btn-xs {
       padding: 2px 8px;
       font-size: 12px;
   }
   
   .test-number {
       font-weight: bold;
       color: #007bff;
   }
   
   .add-test-section {
       background: #f8f9fa;
       padding: 20px;
       border-radius: 8px;
       margin-top: 20px;
   }
   
   .modal-body textarea {
       font-family: 'Courier New', monospace;
       font-size: 13px;
   }
   </style>
   <body class="dashboard dashboard_1">
      <div class="full_container">
         <div class="inner_container">
            <?php include_once 'sidebar.php'?>
            <div id="content">
               <?php include_once 'topbar.php'?>
               <div class="midde_cont">
                  <div class="container-fluid">
                     <div class="row column_title">
                        <div class="col-md-12">
                           <div class="page_title">
                              <h2>
                                  <a href="problems.php" class="btn btn-secondary">
                                      <i class="fa fa-arrow-left"></i> Orqaga
                                  </a>
                                  Test Cases - <?= htmlspecialchars($problem['title']) ?>
                              </h2>
                           </div>
                        </div>
                     </div>
                     
                     <div class="row column1">
                        <div class="col-md-12">
                            <div class="white_shd full margin_bottom_30">
                                <div class="full graph_head">
                                    <div class="heading1 margin_0">
                                        <h2>📝 Test Case'lar Ro'yxati</h2>
                                    </div>
                                </div>
                                <div class="full price_table padding_infor_info">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="35%">Input (Kirish)</th>
                                                    <th width="35%">Output (Chiqish)</th>
                                                    <th width="25%" class="text-center">Amallar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($tests)): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">
                                                        <i class="fa fa-info-circle"></i> Hozircha test case'lar yo'q
                                                    </td>
                                                </tr>
                                                <?php else: ?>
                                                    <?php foreach ($tests as $index => $test): ?>
                                                    <tr id="test-row-<?= $test['id'] ?>">
                                                        <td class="test-number"><?= $index + 1 ?></td>
                                                        <td>
                                                            <div class="test-preview" title="<?= htmlspecialchars($test['input']) ?>">
                                                                <?= htmlspecialchars(substr($test['input'], 0, 200)) ?>
                                                                <?= strlen($test['input']) > 200 ? '...' : '' ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="test-preview" title="<?= htmlspecialchars($test['output']) ?>">
                                                                <?= htmlspecialchars(substr($test['output'], 0, 200)) ?>
                                                                <?= strlen($test['output']) > 200 ? '...' : '' ?>
                                                            </div>
                                                        </td>
                                                        <td class="text-center test-actions">
                                                            <button onclick="editTest(<?= $test['id'] ?>)" class="btn btn-sm btn-warning">
                                                                <i class="fa fa-edit"></i> Tahrirlash
                                                            </button>
                                                            <button onclick="deleteTest(<?= $test['id'] ?>)" class="btn btn-sm btn-danger">
                                                                <i class="fa fa-trash"></i> O'chirish
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!-- Add New Test Section -->
                                    <div class="add-test-section">
                                        <h4><i class="fa fa-plus-circle"></i> Yangi Test Case Qo'shish</h4>
                                        <form id="addTestForm">
                                            <input type="hidden" name="problem_id" value="<?= $problem_id ?>">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Input (Kirish) <span class="text-danger">*</span></label>
                                                        <textarea name="input" class="form-control" rows="6" required 
                                                            placeholder="Test uchun kirish ma'lumotini kiriting"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Output (Chiqish) <span class="text-danger">*</span></label>
                                                        <textarea name="output" class="form-control" rows="6" required 
                                                            placeholder="Kutilayotgan chiqish ma'lumotini kiriting"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fa fa-plus"></i> Test Case Qo'shish
                                                </button>
                                            </div>
                                        </form>
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

      <!-- View Test Modal -->
      <div class="modal fade" id="viewTestModal" tabindex="-1">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">📄 Test Case Ko'rish</h5>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-md-6">
                              <h6>Input (Kirish):</h6>
                              <pre id="viewInput" style="background:#f8f9fa; padding:15px; border-radius:4px; max-height:300px; overflow:auto;"></pre>
                          </div>
                          <div class="col-md-6">
                              <h6>Output (Chiqish):</h6>
                              <pre id="viewOutput" style="background:#f8f9fa; padding:15px; border-radius:4px; max-height:300px; overflow:auto;"></pre>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- Edit Test Modal -->
      <div class="modal fade" id="editTestModal" tabindex="-1">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">✏️ Test Case Tahrirlash</h5>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <form id="editTestForm">
                      <div class="modal-body">
                          <input type="hidden" name="test_id" id="editTestId">
                          <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Input (Kirish) <span class="text-danger">*</span></label>
                                      <textarea name="input" id="editInput" class="form-control" rows="8" required></textarea>
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Output (Chiqish) <span class="text-danger">*</span></label>
                                      <textarea name="output" id="editOutput" class="form-control" rows="8" required></textarea>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Yopish</button>
                          <button type="submit" class="btn btn-primary">
                              <i class="fa fa-save"></i> Saqlash
                          </button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
      <script src="../js/jquery.min.js"></script>
      <script src="../js/bootstrap.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
      <script>
      // Edit Test
      function editTest(testId) {
        $.ajax({
        url: 'tests/get_test.php',
        method: 'GET',
        data: { id: testId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
            $('#editTestId').val(response.data.id);
            $('#editInput').val(response.data.input);
            $('#editOutput').val(response.data.output);
            $('#editTestModal').modal('show');
            } else {
            if (window.Swal) Swal.fire('Xatolik!', response.message, 'error');
            else alert(response.message || 'Xatolik');
            }
        },
        error: function(xhr, status, err) {
            alert('AJAX xato: console.log bilan tekshiring.');
        }
        });
      }
      function deleteTest(testId) {
          Swal.fire({
              title: 'Ishonchingiz komilmi?',
              text: "Bu test case o'chiriladi!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: 'Ha, o\'chirish!',
              cancelButtonText: 'Bekor qilish'
          }).then((result) => {
              if (result.isConfirmed) {
                  $.ajax({
                      url: 'tests/delete_test.php',
                      method: 'POST',
                      data: { test_id: testId },
                      dataType: 'json',
                      success: function(response) {
                          if (response.success) {
                              $('#test-row-' + testId).fadeOut(300, function() {
                                  $(this).remove();
                              });
                              Swal.fire('O\'chirildi!', response.message, 'success');
                          } else {
                              Swal.fire('Xatolik!', response.message, 'error');
                          }
                      }
                  });
              }
          });
      }

      // Add Test
      $('#addTestForm').submit(function(e) {
          e.preventDefault();
          
          $.ajax({
              url: 'tests/add_test.php',
              method: 'POST',
              data: $(this).serialize(),
              dataType: 'json',
              success: function(response) {
                  if (response.success) {
                      Swal.fire('Muvaffaqiyatli!', response.message, 'success');
                      location.reload();
                  } else {
                      Swal.fire('Xatolik!', response.message, 'error');
                  }
              }
          });
      });

      // Update Test
      $('#editTestForm').submit(function(e) {
          e.preventDefault();
          $.ajax({
              url: 'tests/update_test.php',
              method: 'POST',
              data: $(this).serialize(),
              dataType: 'json',
              success: function(response) {
                  if (response.success) {
                      $('#editTestModal').modal('hide');
                      Swal.fire('Muvaffaqiyatli!', response.message, 'success');
                      location.reload();
                  } else {
                      Swal.fire('Xatolik!', response.message, 'error');
                  }
              }
          });
      });
      </script>
   </body>
</html>