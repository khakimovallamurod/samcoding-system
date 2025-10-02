<?php
session_start();
include_once '../config.php';

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$db = new Database();
$problem_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($problem_id === 0) {
    header("Location: problems.php");
    exit;
}

$problem = $db->get_data_by_table('problems', ['id' => $problem_id]);

if (!$problem) {
    $_SESSION['error'] = "Masala topilmadi!";
    header("Location: problems.php");
    exit;
}
?>
<html lang="en">
<?php include_once 'head.php'?>
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
                                    <h2>Masalani Tahrirlash</h2>
                                </div>
                            </div>
                        </div>

                        <div id="alertContainer"></div>

                        <div class="row column1">
                            <div class="col-md-12">
                                <div class="white_shd full margin_bottom_30">
                                    <div class="full graph_head">
                                        <div class="heading1 margin_0">
                                            <h2>Masala #<?= str_pad($problem_id, 4, '0', STR_PAD_LEFT) ?></h2>
                                        </div>
                                    </div>
                                    <div class="full price_table padding_infor_info">
                                        <form id="updateProblemForm" enctype="multipart/form-data">
                                            <input type="hidden" name="id" value="<?= $problem_id ?>">
                                            
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="title">Masala Nomi <span class="required">*</span></label>
                                                        <input type="text" class="form-control" id="title" name="title" 
                                                            value="<?= htmlspecialchars($problem['title']) ?>" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="descript">Masala Tavsifi <span class="required">*</span></label>
                                                        <textarea class="form-control" id="descript" name="descript" 
                                                                rows="8" required><?= htmlspecialchars($problem['descript']) ?></textarea>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="input_format">Kirish Formati</label>
                                                                <textarea class="form-control" id="input_format" name="input_format" 
                                                                        rows="4"><?= htmlspecialchars($problem['input_format']) ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="output_format">Chiqish Formati</label>
                                                                <textarea class="form-control" id="output_format" name="output_format" 
                                                                        rows="4"><?= htmlspecialchars($problem['output_format']) ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="constraints">Cheklovlar</label>
                                                        <textarea class="form-control" id="constraints" name="constraints" 
                                                                rows="3"><?= htmlspecialchars($problem['constraints']) ?></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="izoh">Izoh</label>
                                                        <input type="text" class="form-control" id="izoh" name="izoh" 
                                                            value="<?= htmlspecialchars($problem['izoh']) ?>">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="testcases_zip">Test Case'larni Yangilash (ixtiyoriy)</label>
                                                        <input type="file" class="form-control-file" id="testcases_zip" 
                                                            name="testcases_zip" accept=".zip" onchange="handleFileSelect(event)">
                                                        <small class="form-text text-muted">
                                                            Yangi test case yuklash shart emas. Faqat o'zgartirish kerak bo'lsa yuklang.
                                                            <br><strong>Diqqat:</strong> Yangi test yuklansa, eski testlar o'chiriladi!
                                                        </small>
                                                    </div>

                                                    <div id="file-preview" class="file-preview" style="display: none;">
                                                        <div class="alert alert-info">
                                                            <strong>Tanlangan fayl:</strong>
                                                            <span id="preview-name"></span>
                                                            (<span id="preview-size"></span>)
                                                            <button type="button" class="btn btn-sm btn-danger float-right" onclick="removeFile()">
                                                                <i class="fa fa-times"></i> Bekor qilish
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="difficulty">Qiyinlik Darajasi <span class="required">*</span></label>
                                                        <input type="number" class="form-control" id="difficulty" name="difficulty" 
                                                            value="<?= $problem['difficulty'] ?>" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="category">Kategoriya <span class="required">*</span></label>
                                                        <input type="text" class="form-control" id="category" name="category"
                                                            value="<?= htmlspecialchars($problem['category']) ?>" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="time_limit">Vaqt Limiti (ms) <span class="required">*</span></label>
                                                        <input type="number" class="form-control" id="time_limit" name="time_limit" 
                                                            value="<?= $problem['time_limit'] ?>" min="100" max="10000" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="memory_limit">Xotira Limiti (KB) <span class="required">*</span></label>
                                                        <input type="number" class="form-control" id="memory_limit" name="memory_limit" 
                                                            value="<?= $problem['memory_limit'] ?>" min="4096" max="262144" required>
                                                    </div>

                                                    <div class="info-box alert alert-warning">
                                                        <h5>⚠️ Diqqat</h5>
                                                        <ul class="mb-0">
                                                            <li>Barcha o'zgarishlar darhol saqlanadi</li>
                                                            <li>Test yuklansa eski testlar o'chiriladi</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group text-right">
                                                <a href="problems.php" class="btn btn-secondary btn-lg">
                                                    <i class="fa fa-times"></i> Bekor qilish
                                                </a>
                                                <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                                    <i class="fa fa-save"></i> O'zgarishlarni Saqlash
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
    
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/perfect-scrollbar.min.js"></script>
    <script>
        var ps = new PerfectScrollbar('#sidebar');

        // File select handler
        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                document.getElementById('preview-name').textContent = file.name;
                document.getElementById('preview-size').textContent = formatBytes(file.size);
                document.getElementById('file-preview').style.display = 'block';
            }
        }

        function removeFile() {
            document.getElementById('testcases_zip').value = '';
            document.getElementById('file-preview').style.display = 'none';
        }

        function formatBytes(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        document.getElementById('updateProblemForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const formData = new FormData(this);
            
            // Disable button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saqlanmoqda...';

            fetch('update_problem.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    setTimeout(() => {
                        window.location.href = 'problems.php';
                    }, 1500);
                } else {
                    showAlert('danger', data.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fa fa-save"></i> O\'zgarishlarni Saqlash';
                }
            })
            .catch(error => {
                showAlert('danger', 'Xatolik yuz berdi: ' + error.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa fa-save"></i> O\'zgarishlarni Saqlash';
            });
        });

        function showAlert(type, message) {
            const alertHTML = `
                <div class="alert alert-${type} alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    ${message}
                </div>
            `;
            document.getElementById('alertContainer').innerHTML = alertHTML;
            
            setTimeout(() => {
                $('.alert').fadeOut();
            }, 5000);
        }
    </script>
    <style>
        .required {
            color: red;
        }
        .file-preview {
            margin-top: 15px;
        }
        .info-box {
            padding: 15px;
            border-radius: 5px;
        }
        .info-box h5 {
            margin-bottom: 10px;
        }
        .info-box ul {
            padding-left: 20px;
        }
    </style>
</body>
</html>