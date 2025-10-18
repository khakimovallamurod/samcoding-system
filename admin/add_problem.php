<?php
    session_start();
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        header("Location: ../auth/login.php");
        exit;
    }
?>
<html lang="en">
   <?php include_once 'head.php'?>
   <link rel="stylesheet" href="../css/addproblem_style.css">
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
                           </div>
                        </div>
                     </div>
                     <div class="row column1">
                        <div class="col-md-12">
                            <div class="white_shd full margin_bottom_30">
                                <div class="full graph_head">
                                    <div class="heading1 margin_0">
                                        <h2>📝 Yangi Masala Qo'shish</h2>
                                    </div>
                                </div>
                                <div class="full price_table padding_infor_info">
                                    <form id="addProblemForm" method="POST" enctype="multipart/form-data">
                                        <!-- Step Indicator -->
                                        <div class="step-indicator">
                                            <div class="step active" id="step1-indicator">
                                                <span class="step-number">1</span>
                                                <span class="step-title">Masala Ma'lumotlari</span>
                                            </div>
                                            <div class="step-line"></div>
                                            <div class="step" id="step2-indicator">
                                                <span class="step-number">2</span>
                                                <span class="step-title">Test Case'lar</span>
                                            </div>
                                        </div>
                                        <!-- Step 1: Problem Details -->
                                        <div class="form-step active" id="step1">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="title">Masala Nomi <span class="required">*</span></label>
                                                        <input type="text" class="form-control" id="title" name="title" 
                                                            placeholder="Masalaning nomini kiriting" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="descript">Masala Tavsifi <span class="required">*</span></label>
                                                        <textarea class="form-control" id="descript" name="descript" 
                                                                rows="8" placeholder="Masala matnini kiriting" required></textarea>
                                                        <small class="form-text text-muted">Masalaning to'liq tavsifini yozing</small>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="input_format">Kirish Formati</label>
                                                                <textarea class="form-control" id="input_format" name="input_format" 
                                                                        rows="4" placeholder="Kirish formatini tavsiflang"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="output_format">Chiqish Formati</label>
                                                                <textarea class="form-control" id="output_format" name="output_format" 
                                                                        rows="4" placeholder="Chiqish formatini tavsiflang"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="constraints">Cheklovlar</label>
                                                        <textarea class="form-control" id="constraints" name="constraints" 
                                                                rows="3" placeholder="Masalaning cheklovlarini kiriting"></textarea>
                                                        <small class="form-text text-muted">Misol: 1 ≤ N ≤ 10^5</small>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="izoh">Izoh</label>
                                                        <input type="text" class="form-control" id="izoh" name="izoh" 
                                                            placeholder="Qo'shimcha izohlar (masalan: ACM-ICPC 2023)">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="difficulty">Qiyinlik Darajasi <span class="required">*</span></label>
                                                        <select class="form-control" id="difficulty" name="difficulty" required>
                                                            <option value="" disabled selected>Qiyinlik darajasini tanlang</option>
                                                            <option value="beginner">Beginner</option>
                                                            <option value="easy">Easy</option>
                                                            <option value="medium">Medium</option>
                                                            <option value="hard">Hard</option>
                                                            <option value="expert">Expert</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="category">Kategoriya <span class="required">*</span></label>
                                                        <select class="form-control" id="category" name="category" required>
                                                            <option value="" disabled selected>Kategoriyani tanlang</option>
                                                            <option value="array">Array</option>
                                                            <option value="string">String</option>
                                                            <option value="math">Math</option>
                                                            <option value="dp">Dynamic Programming</option>
                                                            <option value="graph">Graph</option>
                                                            <option value="tree">Tree</option>
                                                            <option value="list">List</option>
                                                            <option value="stack">Stack</option>
                                                            <option value="queue">Queue</option>
                                                            <option value="sorting">Sorting</option>
                                                            <option value="ga">Graph Algorithms</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="time_limit">Vaqt Limiti (ms) <span class="required">*</span></label>
                                                        <input type="number" class="form-control" id="time_limit" name="time_limit" 
                                                            value="1000" min="100" max="10000" required>
                                                        <small class="form-text text-muted">Standart: 1000ms</small>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="memory_limit">Xotira Limiti (KB) <span class="required">*</span></label>
                                                        <input type="number" class="form-control" id="memory_limit" name="memory_limit" 
                                                            value="16384" min="4096" max="262144" required>
                                                        <small class="form-text text-muted">Standart: 16MB</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group text-right">
                                                <button type="button" class="btn btn-primary btn-lg" onclick="nextStep()">
                                                    Keyingisi <i class="fa fa-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Step 2: Test Cases -->
                                        <div class="form-step" id="step2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- Tab Navigation -->
                                                    <ul class="nav nav-tabs mb-4" id="uploadTabs" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="zip-tab" data-toggle="tab" href="#zipUpload" role="tab">
                                                                <i class="fa fa-file-archive-o"></i> ZIP File Yuklash
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="manual-tab" data-toggle="tab" href="#manualInput" role="tab">
                                                                <i class="fa fa-keyboard-o"></i> Qo'lda Kiritish
                                                            </a>
                                                        </li>
                                                    </ul>

                                                    <!-- Tab Content -->
                                                    <div class="tab-content" id="uploadTabContent">
                                                        <!-- ZIP Upload Tab -->
                                                        <div class="tab-pane fade show active" id="zipUpload" role="tabpanel">
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="upload-section">
                                                                        <h4>📦 Test Case'larni Yuklash</h4>
                                                                        <p class="text-muted">Test fayllarini ZIP arxivi sifatida yuklang</p>
                                                                        
                                                                        <div class="file-upload-wrapper">
                                                                            <input type="file" id="testcases_zip" name="testcases_zip" 
                                                                                accept=".zip" onchange="handleFileSelect(event)">
                                                                            <label for="testcases_zip" class="file-upload-label">
                                                                                <i class="fa fa-cloud-upload"></i>
                                                                                <span id="file-name">ZIP faylni tanlang yoki bu yerga tashlang</span>
                                                                            </label>
                                                                        </div>

                                                                        <div class="alert alert-info mt-3">
                                                                            <h5><i class="fa fa-info-circle"></i> ZIP Fayl Strukturasi:</h5>
                                                                            <pre>testcases.zip
                                        ├── 1.in
                                        ├── 1.out
                                        ├── 2.in
                                        ├── 2.out
                                        ├── 3.in
                                        ├── 3.out
                                        └── ...</pre>
                                                                            <p class="mb-0">Har bir test uchun <code>.in</code> (input) va <code>.out</code> (output) fayllari bo'lishi kerak.</p>
                                                                        </div>
                                                                    </div>

                                                                    <div id="file-preview" class="file-preview" style="display: none;">
                                                                        <h5>Yuklangan Fayl:</h5>
                                                                        <div class="file-info">
                                                                            <i class="fa fa-file-archive-o"></i>
                                                                            <div class="file-details">
                                                                                <span id="preview-name"></span>
                                                                                <span id="preview-size"></span>
                                                                            </div>
                                                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeFile()">
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Manual Input Tab -->
                                                        <div class="tab-pane fade" id="manualInput" role="tabpanel">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="manual-input-section">
                                                                        <h4>✍️ Test Case'larni Qo'lda Kiritish</h4>
                                                                        <p class="text-muted">Har bir test case uchun kirish va chiqish qiymatlarini kiriting</p>
                                                                        
                                                                        <div id="testCasesContainer">
                                                                            <!-- First test case (default) -->
                                                                            <div class="test-case-item" data-index="1">
                                                                                <div class="test-case-header">
                                                                                    <h5>Test Case #1</h5>
                                                                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeTestCase(1)" style="display: none;">
                                                                                        <i class="fa fa-trash"></i> O'chirish
                                                                                    </button>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label>Kirish Qiymati (Input)</label>
                                                                                            <textarea class="form-control manual-input" name="manual_input[]" 
                                                                                                rows="5" placeholder="Test uchun kirish qiymatini kiriting"></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label>Chiqish Qiymati (Output)</label>
                                                                                            <textarea class="form-control manual-output" name="manual_output[]" 
                                                                                                rows="5" placeholder="Kutilayotgan chiqish qiymatini kiriting"></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="text-center mt-3">
                                                                            <button type="button" class="btn btn-primary" onclick="addTestCase()">
                                                                                <i class="fa fa-plus"></i> Yangi Test Case Qo'shish
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group text-right mt-4">
                                                <button type="button" class="btn btn-secondary btn-lg" onclick="prevStep()">
                                                    <i class="fa fa-arrow-left"></i> Orqaga
                                                </button>
                                                <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                                    <i class="fa fa-check"></i> Masalani Saqlash
                                                </button>
                                            </div>
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
      <!-- jQuery -->
      <script src="../js/jquery.min.js"></script>
      <script src="../js/bootstrap.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

      <script src="../js/perfect-scrollbar.min.js"></script>
      <script>
         var ps = new PerfectScrollbar('#sidebar');
      </script>
      <script>
        let currentStep = 1;

        function nextStep() {
            const form = document.getElementById('addProblemForm');
            const step1Inputs = document.querySelectorAll('#step1 [required]');
            let isValid = true;
            
            step1Inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                alert('❌ Iltimos, barcha majburiy maydonlarni to\'ldiring!');
                return;
            }
            
            currentStep = 2;
            updateSteps();
        }

        function prevStep() {
            currentStep = 1;
            updateSteps();
        }

        function updateSteps() {
            document.querySelectorAll('.step').forEach((step, index) => {
                if (index + 1 === currentStep) {
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                }
            });
            
            document.querySelectorAll('.form-step').forEach((step, index) => {
                if (index + 1 === currentStep) {
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                }
            });
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                document.getElementById('file-name').textContent = file.name;
                document.getElementById('preview-name').textContent = file.name;
                document.getElementById('preview-size').textContent = formatFileSize(file.size);
                document.getElementById('file-preview').style.display = 'block';
            }
        }

        function removeFile() {
            document.getElementById('testcases_zip').value = '';
            document.getElementById('file-name').textContent = 'ZIP faylni tanlang yoki bu yerga tashlang';
            document.getElementById('file-preview').style.display = 'none';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
        let testCaseCounter = 1;

        function addTestCase() {
            testCaseCounter++;
            
            const container = document.getElementById('testCasesContainer');
            const newTestCase = document.createElement('div');
            newTestCase.className = 'test-case-item';
            newTestCase.setAttribute('data-index', testCaseCounter);
            
            newTestCase.innerHTML = `
                <div class="test-case-header">
                    <h5>Test Case #${testCaseCounter}</h5>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeTestCase(${testCaseCounter})">
                        <i class="fa fa-trash"></i> O'chirish
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kirish Qiymati (Input)</label>
                            <textarea class="form-control manual-input" name="manual_input[]" 
                                rows="5" placeholder="Test uchun kirish qiymatini kiriting"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Chiqish Qiymati (Output)</label>
                            <textarea class="form-control manual-output" name="manual_output[]" 
                                rows="5" placeholder="Kutilayotgan chiqish qiymatini kiriting"></textarea>
                        </div>
                    </div>
                </div>
            `;
            
            container.appendChild(newTestCase);
            
            // Scroll to new test case
            newTestCase.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Show delete button for first test case if more than one exists
            updateDeleteButtons();
        }

        function removeTestCase(index) {
            const testCase = document.querySelector(`.test-case-item[data-index="${index}"]`);
            if (testCase) {
                testCase.style.opacity = '0';
                testCase.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    testCase.remove();
                    updateDeleteButtons();
                }, 300);
            }
        }

        function updateDeleteButtons() {
            const testCases = document.querySelectorAll('.test-case-item');
            testCases.forEach((item, index) => {
                const deleteBtn = item.querySelector('.btn-danger');
                if (testCases.length > 1) {
                    deleteBtn.style.display = 'inline-block';
                } else {
                    deleteBtn.style.display = 'none';
                }
            });
        }
                document.getElementById('addProblemForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Yuklanmoqda...';
            
            const formData = new FormData(this);
            formData.append('author_id', <?= $_SESSION['id'] ?>);
            
            // Determine which method is being used
            const activeTab = document.querySelector('.tab-pane.active').id;
            formData.append('upload_method', activeTab === 'zipUpload' ? 'zip' : 'manual');
            
            fetch('process_add_problem.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: '✅ Masala muvaffaqiyatli qo\'shildi!',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                    window.location.href = 'problems.php';
                } else {
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: '❌ Xatolik: ' + data.message,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fa fa-check"></i> Masalani Saqlash';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    title: '❌ Xatolik yuz berdi!',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa fa-check"></i> Masalani Saqlash';
            });
        });

        const fileInput = document.getElementById('testcases_zip');
        const fileLabel = document.querySelector('.file-upload-label');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileLabel.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileLabel.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileLabel.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            fileLabel.style.borderColor = '#0056b3';
            fileLabel.style.background = '#cce5ff';
        }

        function unhighlight() {
            fileLabel.style.borderColor = '#007bff';
            fileLabel.style.background = '#f8f9ff';
        }

        fileLabel.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            handleFileSelect({ target: { files: files } });
        }
    
    </script>
   </body>
</html>