<?php
    include_once 'config.php';
    session_start();
    if (!isset($_SESSION['id']) || empty($_SESSION['id']) ) {
        header("Location: auth/login.php");
        exit;
    }
   $user_id = $_SESSION['id'];
   $problem_id = intval($_GET['id']);
   $db = new Database();
   $solutions = $db->get_problem_by_id("problems",$problem_id);
   $attempts = $db->get_attempts_by_user($user_id);
?>


<html lang="en">
   <?php include_once 'includes/head.php'?>
   <link rel="stylesheet" href="css/solution_style.css">
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
                           </div>
                        </div>
                     </div>
                     
                     <div class="row column1">
                        <div class="col-md-12">
                            <div class="white_shd full margin_bottom_30">
                                <!-- Problem Header -->
                                <div class="problem-header">
                                    <div class="problem-title">Masala #<?= str_pad($solutions['id'], 4, '0', STR_PAD_LEFT) ?></div>
                                    <div class="problem-meta">
                                        <div class="meta-item"><strong>Xotira:</strong> <?=intval($solutions['memory_limit']/1024) ?> MB</div>
                                        <div class="meta-item"><strong>Vaqt:</strong> <?=$solutions['time_limit'] ?> ms</div>
                                        <div class="meta-item">
                                            <strong>Qiyinchiligi:</strong> 
                                            <span class="difficulty-badge difficulty-easy"><?=$solutions['difficulty'] ?>%</span>
                                        </div>
                                    </div>
                                    <div class="rating-section">
                                        <span class="category-badge">#<?=$solutions['category'] ?></span>
                                        <span class="rating-stars">★★★<span style="color:#95a5a6;">★★</span></span>
                                        <span style="color: #ecf0f1;">3.5 (Baholar 33)</span>
                                        <span style="color: #95a5a6; margin-left: 20px;">👥 14</span>
                                    </div>
                                    <div class="author-info">
                                        Muallif: <?=$solutions['author_name'] ?>
                                    </div>
                                </div>
                                
                                <!-- Navigation Tabs -->
                                <ul class="nav nav-tabs" id="problemTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="problem-tab" data-bs-toggle="tab" data-bs-target="#problem" type="button" role="tab">
                                            Masala
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="attempts-tab" data-bs-toggle="tab" data-bs-target="#attempts" type="button" role="tab">
                                            Oxirgi urinishlar
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="discussion-tab" data-bs-toggle="tab" data-bs-target="#discussion" type="button" role="tab">
                                            Muhokama
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="rating-tab" data-bs-toggle="tab" data-bs-target="#rating" type="button" role="tab">
                                            Reyting
                                        </button>
                                    </li>
                                </ul>
                                
                                <!-- Tab Content -->
                                <div class="tab-content" id="problemTabContent">
                                    <!-- Problem Tab -->
                                    <div class="tab-pane fade show active" id="problem" role="tabpanel">
                                        <div class="problem-description">
                                            <h3 style="color: #2c3e50; margin-bottom: 20px;"><?=$solutions['title'] ?></h3>
                                            
                                            <p><?=$solutions['descript'] ?></p>
                                            <div class="io-section">
                                                <h4 class="io-title">Kiruvchi ma'lumotlar:</h4>
                                                <p>INPUT.TXT kirish ma'lumot: <?=$solutions['input_format'] ?></p>
                                            </div>
                                            <div class="io-section">
                                                <h4 class="io-title">Chiquvchi ma'lumotlar:</h4>
                                                <p>OUTPUT.TXT chiqish ma'lumot: <?=$solutions['output_format'] ?></p>
                                            </div>
                                            
                                            <div class="examples-section">
                                                <h4 style="color: #2c3e50;">Misollar</h4>
                                                <div class="example">
                                                    <div class="input-section">
                                                        <div class="example-label">input.txt</div>
                                                        <div class="example-content">Yo'q</div>
                                                    </div>
                                                    <div class="output-section">
                                                        <div class="example-label">output.txt</div>
                                                        <div class="example-content">Yo'q</div>
                                                    </div>
                                                </div>

                                            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0;">
                                                <h4 style="color: #2c3e50;">Izoh:</h4>
                                                <div class="example-content" style="margin-top: 10px;"><?=$solutions['izoh'] ?></div>
                                            </div>
                                            <form id="attemptForm" onsubmit="submitAttempt(event)">
                                                <input type="hidden" name="user_id" value="<?= $solutions['user_id']; ?>">
                                                <input type="hidden" name="problem_id" value="<?= $problem_id; ?>">

                                                <h4 style="color: #2c3e50; margin: 30px 0 20px 0;">Tilni tanlash</h4>
                                                <div class="code-editor">
                                                    <div class="language-selector">
                                                        <label for="languageSelect">📝 Dasturlash tili:</label>
                                                        <select id="languageSelect" name="language" required>
                                                            <option value="python">Python 3.10.0</option>
                                                            <option value="python2">Python 2.7.18</option>
                                                            <option value="java">Java 15.0.2</option>
                                                            <option value="cpp">C++ (GCC 10.2.0)</option>
                                                            <option value="c">C (GCC 10.2.0)</option>
                                                            <option value="csharp">C# 6.12.0</option>
                                                            <option value="javascript">JavaScript (Node.js 18.15.0)</option>
                                                            <option value="typescript">TypeScript 5.0.3</option>
                                                            <option value="php">PHP 8.2.3</option>
                                                            <option value="go">Go 1.16.2</option>
                                                            <option value="kotlin">Kotlin 1.8.20</option>
                                                            <option value="rust">Rust 1.68.2</option>
                                                            <option value="ruby">Ruby 3.0.1</option>
                                                            <option value="swift">Swift 5.3.3</option>
                                                            <option value="r">R (4.1.1)</option>
                                                            <option value="scala">Scala 3.2.2</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <textarea id="codeEditor" name="code"></textarea>
                                                    
                                                    <div class="editor-footer">
                                                        <div class="code-length">
                                                            Code: <span id="codeLength">0</span> / 65536 characters
                                                        </div>
                                                        <div class="editor-actions">
                                                            <button type="button" class="editor-btn secondary" onclick="resetCode()">
                                                                <i class="fa fa-refresh"></i> Reset
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="submit-section">
                                                    <button class="btn-submit" type="submit" id="submitBtn">Yechimni yuborish</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <!-- Attempts Tab -->
                                    <div>
                                        <h4 style="color: #2c3e50; margin-bottom: 20px;">Oxirgi urinishlar</h4>
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="attemptsTable">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Masala</th>
                                                        <th>Holati</th>
                                                        <th>Til</th>
                                                        <th>Vaqt</th>
                                                        <th>Xotira</th>
                                                        <th>Yuborilgan vaqt</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(empty($attempts)): ?>
                                                        <tr>
                                                            <td colspan="7" class="empty-state">
                                                                <div class="empty-state-icon">📋</div>
                                                                <h5>Hali hech qanday urinish yo'q</h5>
                                                                <p>Masalani yechib, birinchi urinishingizni yuboring!</p>
                                                            </td>
                                                        </tr>
                                                    <?php else: ?>
                                                        <?php foreach($attempts as $attempt): ?>
                                                        <tr>
                                                            <td>
                                                                <span class="attempt-id"><?= str_pad($attempt['attempt_id'], 6, '0', STR_PAD_LEFT) ?></span>
                                                            </td>
                                                            <td>
                                                                <strong><?= htmlspecialchars($attempt['problem_title']); ?></strong>
                                                            </td>
                                                            <td>
                                                                <?php 
                                                                $status = $attempt['status'];
                                                                $statusClass = 'status-badge ';
                                                                
                                                                if($status === 'Accept') {
                                                                    $statusClass .= 'status-accepted';
                                                                } elseif(strpos($status, 'Wrong Answer') !== false) {
                                                                    $statusClass .= 'status-wrong';
                                                                } elseif(strpos($status, 'Runtime Error') !== false) {
                                                                    $statusClass .= 'status-wrong';
                                                                } else {
                                                                    $statusClass .= 'status-error';
                                                                }
                                                                ?>
                                                                <span class="<?= $statusClass ?>">
                                                                    <?php if ($status === 'Accept'): ?>
                                                                        <?= htmlspecialchars($status); ?>
                                                                    <?php else: ?>
                                                                        <?= htmlspecialchars($status . " (test " . ($attempt['tests_passed']) . ")"); ?>
                                                                    <?php endif; ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="lang-badge"><?= htmlspecialchars($attempt['language']); ?></span>
                                                            </td>
                                                            <td>
                                                                <span class="metric-value"><?= intval($attempt['runTime']); ?> ms</span>
                                                            </td>
                                                            <td>
                                                                <span class="metric-value"><?= intval($attempt['memory']/1024); ?> KB</span>
                                                            </td>
                                                            <td>
                                                                <span class="date-text"><?= date('d.m.Y H:i', strtotime($attempt['created_at'])); ?></span>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
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
      </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

      <script>
        const codeEditor = document.getElementById("codeEditor");
        const codeLength = document.getElementById("codeLength");
        const submitBtn = document.getElementById("submitBtn");

        if (codeEditor) {
            window.addEventListener("load", function () {
                const saved = localStorage.getItem("savedCode");
                if (saved) {
                    codeEditor.value = saved;
                    if (codeLength) codeLength.textContent = saved.length;
                }
            });

            codeEditor.addEventListener("input", function () {
                localStorage.setItem("savedCode", this.value);
                if (codeLength) codeLength.textContent = this.value.length;
            });
        }

        function submitAttempt(event) {
            event.preventDefault();

            const user_id = document.querySelector("[name='user_id']").value;
            const problem_id = document.querySelector("[name='problem_id']").value;
            const language = document.querySelector("[name='language']").value;
            const code = document.querySelector("[name='code']").value;

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Yuborilmoqda...';
            }

            const loadingOverlay = document.createElement('div');
            loadingOverlay.className = 'loading-overlay';
            loadingOverlay.id = 'loadingOverlay';
            loadingOverlay.innerHTML = `
                <div class="loading-spinner">
                    <div class="spinner"></div>
                    <h3>Kod tekshirilmoqda...</h3>
                    <p>Iltimos, kuting</p>
                </div>
            `;
            document.body.appendChild(loadingOverlay);

            const tbody = document.querySelector("#attemptsTable tbody");
            if (tbody) {
                const loadingRow = document.createElement('tr');
                loadingRow.id = 'loadingRow';
                loadingRow.innerHTML = `
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        <span class="status-loading">⏳ Natija kutilmoqda...</span>
                    </td>
                `;
                tbody.insertBefore(loadingRow, tbody.firstChild);
            }

            const formData = new FormData();
            formData.append("user_id", user_id);
            formData.append("problem_id", problem_id);
            formData.append("language", language);
            formData.append("code", code);

            fetch("codecheck.php", {
                method: "POST",
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Serverdan noto'g'ri javob qaytdi");
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('loadingOverlay')?.remove();
                document.getElementById('loadingRow')?.remove();
                
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Yechimni yuborish';
                }

                if (data.success) {
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    } else {
                        console.error("❌ " + data.message);
                    }
                }
            })
            .catch(error => {
                console.error("Xatolik:", error);
                document.getElementById('loadingOverlay')?.remove();
                document.getElementById('loadingRow')?.remove();

                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Yechimni yuborish';
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: "So'rov xatoligi: " + error.message,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    alert("❌ Xatolik: " + error.message);
                }
            });
        }
    </script>

   </body>
</html>