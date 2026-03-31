<?php
require_once 'auth_check.php';
require_once '../config.php';

$problem_id = intval($_GET['id'] ?? 0);
$db = new Database();

if (!$problem_id) {
    header("Location: problems.php");
    exit;
}

$problem = $db->get_problem_by_id("problems", $problem_id);
if (!$problem) {
    header("Location: problems.php");
    exit;
}

// Fetch first 2 test examples for the problem
$examples_result = $db->query("SELECT input, output FROM tests WHERE problem_id = $problem_id LIMIT 2");
$examples = [];
while ($r = mysqli_fetch_assoc($examples_result)) $examples[] = $r;

// ALL attempts for this problem (admin sees everyone's submissions)
$all_attempts_result = $db->query("
    SELECT a.id, a.status, a.language, a.runTime, a.memory,
           a.tests_passed, a.created_at, a.code,
           u.fullname, u.username
    FROM attempts a
    JOIN users u ON u.id = a.user_id
    WHERE a.problem_id = $problem_id
    ORDER BY a.created_at DESC
    LIMIT 100
");
$all_attempts = [];
while ($r = mysqli_fetch_assoc($all_attempts_result)) $all_attempts[] = $r;

$total_att = count($all_attempts);
$accepted  = count(array_filter($all_attempts, fn($a) => $a['status'] === 'Accept'));
$acc_rate  = $total_att > 0 ? round($accepted / $total_att * 100) : 0;

// Difficulty styling
$diff = strtolower($problem['difficulty'] ?? 'easy');
$diffColors = [
    'beginner' => ['#dcfce7','#15803d'],
    'easy'     => ['#dbeafe','#1d4ed8'],
    'medium'   => ['#fef3c7','#b45309'],
    'hard'     => ['#fee2e2','#b91c1c'],
    'expert'   => ['#f3e8ff','#7e22ce'],
];
[$diff_bg, $diff_color] = $diffColors[$diff] ?? ['#f1f5f9','#475569'];
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once 'head.php' ?>

<!-- CodeMirror -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/dracula.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/python/python.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/clike/clike.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/php/php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/go/go.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/rust/rust.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/matchbrackets.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closebrackets.js"></script>

<style>
/* ── Solution page split layout ── */
.solution-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    height: calc(100vh - 60px); /* subtract topbar */
    overflow: hidden;
}
.problem-panel {
    overflow-y: auto;
    border-right: 1px solid #e2e8f0;
    background: #fff;
    display: flex;
    flex-direction: column;
}
.editor-panel {
    display: flex;
    flex-direction: column;
    background: #1e1e2e;
    overflow: hidden;
}

/* Problem panel sections */
.prob-header {
    padding: 20px 22px 16px;
    border-bottom: 1px solid #f1f5f9;
    position: sticky;
    top: 0;
    background: #fff;
    z-index: 10;
}
.prob-body { padding: 20px 22px; flex: 1; }

.section-title {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #94a3b8;
    margin: 18px 0 8px;
}
.section-title:first-child { margin-top: 0; }

.problem-text {
    font-size: 13.5px;
    line-height: 1.7;
    color: #374151;
    white-space: pre-wrap;
}

.io-block {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 14px;
    font-size: 13px;
    color: #374151;
    line-height: 1.6;
    white-space: pre-wrap;
    font-family: 'Fira Code', 'Consolas', monospace;
}

.example-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-top: 8px;
}
.example-box {
    background: #0f172a;
    border-radius: 8px;
    overflow: hidden;
}
.example-box-header {
    background: #1e293b;
    padding: 5px 10px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #64748b;
}
.example-box-content {
    padding: 10px;
    font-family: 'Fira Code', 'Consolas', monospace;
    font-size: 12.5px;
    color: #e2e8f0;
    white-space: pre;
    min-height: 40px;
}

/* Tabs in problem panel */
.prob-tabs {
    display: flex;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
    flex-shrink: 0;
}
.prob-tab {
    padding: 10px 18px;
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    border: none;
    background: none;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.15s;
    position: relative;
    top: 1px;
}
.prob-tab.active {
    color: #2563eb;
    border-bottom-color: #2563eb;
    background: #fff;
}
.prob-tab:hover:not(.active) { color: #374151; background: #f1f5f9; }

.tab-pane { display: none; }
.tab-pane.active { display: block; }

/* Editor panel */
.editor-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 14px;
    background: #161622;
    border-bottom: 1px solid #2d2d44;
    flex-shrink: 0;
    gap: 10px;
    flex-wrap: wrap;
}
.lang-select {
    background: #252535;
    border: 1px solid #3d3d55;
    border-radius: 8px;
    color: #e2e8f0;
    padding: 6px 10px;
    font-size: 12.5px;
    outline: none;
    cursor: pointer;
    min-width: 160px;
}
.lang-select option { background: #252535; }

.editor-actions { display: flex; align-items: center; gap: 8px; }

.btn-editor {
    display: inline-flex; align-items: center; gap: 5px;
    border: none; border-radius: 8px;
    padding: 7px 14px; font-size: 12.5px; font-weight: 600;
    cursor: pointer; transition: all 0.15s;
}
.btn-run {
    background: #1e3a5f;
    color: #60a5fa;
    border: 1px solid #2563eb44;
}
.btn-run:hover { background: #1d4ed8; color: #fff; }
.btn-submit-code {
    background: linear-gradient(135deg,#2563eb,#1d4ed8);
    color: #fff;
    box-shadow: 0 2px 8px rgba(37,99,235,0.35);
}
.btn-submit-code:hover { opacity: 0.9; transform: translateY(-1px); }
.btn-submit-code:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
.btn-reset {
    background: #252535; color: #64748b;
    border: 1px solid #3d3d55;
}
.btn-reset:hover { background: #2d2d44; color: #94a3b8; }

/* CodeMirror wrapper */
.cm-wrapper { flex: 1; overflow: hidden; }
.CodeMirror {
    height: 100%;
    font-size: 13.5px;
    font-family: 'Fira Code', 'Consolas', monospace;
    line-height: 1.6;
}

/* Code length indicator */
.code-info {
    display: flex; align-items: center; justify-content: space-between;
    padding: 5px 14px;
    background: #161622;
    border-top: 1px solid #2d2d44;
    font-size: 11px;
    color: #475569;
    flex-shrink: 0;
}

/* Result panel */
.result-panel {
    background: #161622;
    border-top: 1px solid #2d2d44;
    flex-shrink: 0;
    max-height: 200px;
    overflow-y: auto;
    transition: max-height 0.3s ease;
}
.result-panel.hidden { max-height: 0; overflow: hidden; }
.result-inner { padding: 12px 14px; }

/* Attempts table tab */
.attempt-row {
    display: grid;
    grid-template-columns: 60px 1fr 130px 90px 70px 60px 100px;
    align-items: center;
    padding: 9px 16px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 12.5px;
    transition: background 0.12s;
}
.attempt-row:hover { background: #f8fafc; }
.attempt-row-head {
    background: #f8fafc;
    font-size: 11px; font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #64748b;
    padding: 8px 16px;
    border-bottom: 1px solid #e2e8f0;
}

/* Spinner overlay */
.submit-overlay {
    display: none;
    position: fixed; inset: 0;
    background: rgba(15,23,42,0.7);
    z-index: 99999;
    align-items: center; justify-content: center;
    flex-direction: column; gap: 14px;
    backdrop-filter: blur(3px);
}
.submit-overlay.show { display: flex; }
.spin-ring {
    width: 48px; height: 48px;
    border: 3px solid rgba(99,102,241,0.2);
    border-top-color: #6366f1;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

@media (max-width: 900px) {
    .solution-layout { grid-template-columns: 1fr; height: auto; }
    .problem-panel { max-height: 50vh; }
    .editor-panel { height: 60vh; }
}
</style>

<body style="overflow:hidden;">
<div class="admin-root">
    <?php include_once 'sidebar.php' ?>

    <div id="content" style="overflow:hidden;">
        <?php include_once 'topbar.php' ?>

        <!-- Split layout -->
        <div class="solution-layout">

            <!-- ═══════════════════ LEFT: Problem Panel ═══════════════════ -->
            <div class="problem-panel">

                <!-- Problem Header (sticky) -->
                <div class="prob-header">
                    <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:10px;">
                        <a href="problems.php" style="color:#94a3b8; text-decoration:none; font-size:13px; display:flex; align-items:center; gap:4px;"
                           onmouseover="this.style.color='#374151';" onmouseout="this.style.color='#94a3b8';">
                            <i class="fas fa-arrow-left" style="font-size:11px;"></i> Back
                        </a>
                        <span style="color:#e2e8f0;">|</span>
                        <span style="color:#94a3b8; font-size:12px; font-family:monospace; font-weight:600;">
                            #<?= str_pad($problem['id'], 4, '0', STR_PAD_LEFT) ?>
                        </span>
                    </div>
                    <h2 style="font-size:17px; font-weight:800; color:#0f172a; margin:0 0 10px; line-height:1.3;">
                        <?= htmlspecialchars($problem['title']) ?>
                    </h2>
                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                        <span style="background:<?= $diff_bg ?>; color:<?= $diff_color ?>; border-radius:99px; padding:3px 10px; font-size:11px; font-weight:700; text-transform:capitalize;">
                            <?= htmlspecialchars($problem['difficulty']) ?>
                        </span>
                        <?php if ($problem['category']): ?>
                        <span style="background:#f1f5f9; color:#475569; border-radius:99px; padding:3px 10px; font-size:11px; font-weight:600;">
                            #<?= htmlspecialchars($problem['category']) ?>
                        </span>
                        <?php endif; ?>
                        <?php if ($problem['time_limit']): ?>
                        <span style="color:#64748b; font-size:11.5px;">
                            <i class="fas fa-clock" style="margin-right:3px;"></i><?= $problem['time_limit'] ?>ms
                        </span>
                        <?php endif; ?>
                        <?php if ($problem['memory_limit']): ?>
                        <span style="color:#64748b; font-size:11.5px;">
                            <i class="fas fa-memory" style="margin-right:3px;"></i><?= intval($problem['memory_limit']/1024) ?>MB
                        </span>
                        <?php endif; ?>
                        <span style="color:#64748b; font-size:11.5px; margin-left:auto;">
                            <i class="fas fa-user" style="margin-right:3px;"></i><?= htmlspecialchars($problem['author_name'] ?? '—') ?>
                        </span>
                    </div>
                    <!-- Mini stats -->
                    <div style="display:flex; gap:12px; margin-top:10px;">
                        <div style="background:#f0fdf4; border-radius:8px; padding:5px 10px; font-size:11px; color:#15803d; font-weight:600;">
                            <i class="fas fa-circle-check" style="margin-right:3px;"></i><?= $accepted ?> AC
                        </div>
                        <div style="background:#f1f5f9; border-radius:8px; padding:5px 10px; font-size:11px; color:#64748b; font-weight:600;">
                            <i class="fas fa-paper-plane" style="margin-right:3px;"></i><?= $total_att ?> total
                        </div>
                        <div style="background:#<?= $acc_rate >= 50 ? 'f0fdf4; color:#15803d' : 'fff5f5; color:#dc2626' ?>; border-radius:8px; padding:5px 10px; font-size:11px; font-weight:600;">
                            <?= $acc_rate ?>% AC rate
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="prob-tabs">
                    <button class="prob-tab active" onclick="switchTab('problem', this)">Problem</button>
                    <button class="prob-tab" onclick="switchTab('attempts', this)">
                        All Attempts
                        <?php if ($total_att > 0): ?>
                        <span style="background:#f1f5f9; color:#64748b; border-radius:99px; padding:1px 6px; font-size:10px; font-weight:700; margin-left:4px;"><?= $total_att ?></span>
                        <?php endif; ?>
                    </button>
                </div>

                <!-- Problem description tab -->
                <div id="tab-problem" class="tab-pane active prob-body">

                    <?php if ($problem['descript']): ?>
                    <div class="section-title">Description</div>
                    <div class="problem-text"><?= nl2br(htmlspecialchars($problem['descript'])) ?></div>
                    <?php endif; ?>

                    <?php if ($problem['input_format']): ?>
                    <div class="section-title">Input Format</div>
                    <div class="io-block"><?= htmlspecialchars($problem['input_format']) ?></div>
                    <?php endif; ?>

                    <?php if ($problem['output_format']): ?>
                    <div class="section-title">Output Format</div>
                    <div class="io-block"><?= htmlspecialchars($problem['output_format']) ?></div>
                    <?php endif; ?>

                    <?php if ($problem['constraints']): ?>
                    <div class="section-title">Constraints</div>
                    <div class="io-block"><?= htmlspecialchars($problem['constraints']) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($examples)): ?>
                    <div class="section-title">Examples</div>
                    <?php foreach ($examples as $i => $ex): ?>
                    <div style="margin-bottom:10px;">
                        <div style="font-size:12px; font-weight:600; color:#64748b; margin-bottom:6px;">Example <?= $i + 1 ?></div>
                        <div class="example-grid">
                            <div class="example-box">
                                <div class="example-box-header">Input</div>
                                <div class="example-box-content"><?= htmlspecialchars($ex['input']) ?: '(empty)' ?></div>
                            </div>
                            <div class="example-box">
                                <div class="example-box-header">Output</div>
                                <div class="example-box-content"><?= htmlspecialchars($ex['output']) ?: '(empty)' ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if ($problem['izoh']): ?>
                    <div class="section-title">Note</div>
                    <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:10px; padding:12px 14px; font-size:13px; line-height:1.6; color:#92400e;">
                        <i class="fas fa-lightbulb" style="color:#f59e0b; margin-right:6px;"></i>
                        <?= nl2br(htmlspecialchars($problem['izoh'])) ?>
                    </div>
                    <?php endif; ?>

                    <div style="height: 24px;"></div>
                </div>

                <!-- All attempts tab -->
                <div id="tab-attempts" class="tab-pane">
                    <?php if (empty($all_attempts)): ?>
                    <div style="text-align:center; padding:48px 20px; color:#94a3b8;">
                        <i class="fas fa-inbox" style="font-size:32px; display:block; margin-bottom:10px; color:#cbd5e1;"></i>
                        No submissions yet for this problem
                    </div>
                    <?php else: ?>
                    <!-- Head row -->
                    <div class="attempt-row attempt-row-head">
                        <span>#</span>
                        <span>User</span>
                        <span>Status</span>
                        <span>Language</span>
                        <span>Runtime</span>
                        <span>Memory</span>
                        <span>Submitted</span>
                    </div>
                    <?php foreach ($all_attempts as $a):
                        $st = $a['status'];
                        if ($st === 'Accept') { $bc='badge-accepted'; $icon='fa-circle-check'; $short='Accepted'; }
                        elseif (str_contains($st,'Wrong')) { $bc='badge-wrong'; $icon='fa-circle-xmark'; $short='Wrong Ans'; }
                        elseif (str_contains($st,'Runtime')) { $bc='badge-runtime'; $icon='fa-triangle-exclamation'; $short='Runtime Err'; }
                        else { $bc='badge-pending'; $icon='fa-circle'; $short=substr($st,0,12); }
                    ?>
                    <div class="attempt-row">
                        <span style="color:#94a3b8; font-size:11px; font-family:monospace;">#<?= $a['id'] ?></span>
                        <span>
                            <div style="font-size:12.5px; font-weight:600; color:#1e293b; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:120px;"><?= htmlspecialchars($a['fullname']) ?></div>
                            <div style="font-size:11px; color:#94a3b8;">@<?= htmlspecialchars($a['username']) ?></div>
                        </span>
                        <span><span class="<?= $bc ?>"><i class="fas <?= $icon ?>" style="margin-right:3px; font-size:10px;"></i><?= $short ?></span></span>
                        <span><span style="background:#f1f5f9; color:#475569; border-radius:6px; padding:2px 7px; font-size:11px; font-weight:600;"><?= htmlspecialchars($a['language']) ?></span></span>
                        <span style="color:#64748b; font-size:12px;"><?= $a['runTime'] ? intval($a['runTime']).'ms' : '—' ?></span>
                        <span style="color:#64748b; font-size:12px;"><?= $a['memory'] ? intval($a['memory']/1024).'KB' : '—' ?></span>
                        <span style="color:#94a3b8; font-size:11px;"><?= date('d M, H:i', strtotime($a['created_at'])) ?></span>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div><!-- /problem-panel -->

            <!-- ═══════════════════ RIGHT: Editor Panel ═══════════════════ -->
            <div class="editor-panel">

                <!-- Editor Toolbar -->
                <div class="editor-header">
                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                        <!-- Language selector -->
                        <select id="languageSelect" class="lang-select" onchange="changeLanguage()">
                            <option value="python">🐍 Python 3.10</option>
                            <option value="python2">🐍 Python 2.7</option>
                            <option value="java">☕ Java 15</option>
                            <option value="cpp">⚙️ C++ (GCC)</option>
                            <option value="c">⚙️ C (GCC)</option>
                            <option value="csharp">🔷 C#</option>
                            <option value="javascript">🟨 JavaScript</option>
                            <option value="typescript">🔵 TypeScript</option>
                            <option value="php">🐘 PHP 8.2</option>
                            <option value="go">🐹 Go 1.16</option>
                            <option value="kotlin">🟣 Kotlin</option>
                            <option value="rust">🦀 Rust</option>
                            <option value="ruby">💎 Ruby</option>
                            <option value="swift">🍎 Swift</option>
                        </select>
                        <span id="codeCountBadge" style="color:#475569; font-size:11.5px;">0 chars</span>
                    </div>
                    <div class="editor-actions">
                        <button type="button" class="btn-editor btn-reset" onclick="resetEditor()">
                            <i class="fas fa-rotate-left"></i> Reset
                        </button>
                        <button type="button" class="btn-editor btn-submit-code" id="submitBtn" onclick="submitCode()">
                            <i class="fas fa-paper-plane"></i> Submit
                        </button>
                    </div>
                </div>

                <!-- CodeMirror -->
                <div class="cm-wrapper">
                    <textarea id="codeEditor"></textarea>
                </div>

                <!-- Status bar -->
                <div class="code-info">
                    <span id="editorLang">Python 3.10</span>
                    <span>Ln <span id="cursorLine">1</span>, Col <span id="cursorCol">1</span></span>
                </div>

                <!-- Result panel -->
                <div class="result-panel hidden" id="resultPanel">
                    <div class="result-inner" id="resultInner"></div>
                </div>

            </div><!-- /editor-panel -->

        </div><!-- /solution-layout -->
    </div><!-- /content -->
</div><!-- /admin-root -->

<!-- Submit overlay -->
<div class="submit-overlay" id="submitOverlay">
    <div class="spin-ring"></div>
    <div style="color:#e2e8f0; font-size:14px; font-weight:600;">Evaluating code...</div>
    <div style="color:#64748b; font-size:12px;">Running against all test cases</div>
</div>

<script src="../js/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ── CodeMirror init ──────────────────────────────────────────────────
const editor = CodeMirror.fromTextArea(document.getElementById('codeEditor'), {
    theme: 'dracula',
    lineNumbers: true,
    matchBrackets: true,
    autoCloseBrackets: true,
    indentUnit: 4,
    tabSize: 4,
    indentWithTabs: false,
    lineWrapping: false,
    mode: 'text/x-python',
    extraKeys: {
        'Tab': function(cm) { cm.replaceSelection('    '); }
    }
});

// Fill full height
editor.setSize('100%', '100%');

// Restore saved code per language
const STORAGE_KEY = 'admin_code_<?= $problem_id ?>_';

function saveCode() {
    const lang = document.getElementById('languageSelect').value;
    localStorage.setItem(STORAGE_KEY + lang, editor.getValue());
}

function loadCode() {
    const lang = document.getElementById('languageSelect').value;
    const saved = localStorage.getItem(STORAGE_KEY + lang);
    if (saved !== null) editor.setValue(saved);
    else editor.setValue(getBoilerplate(lang));
}

// Update code count
editor.on('change', function() {
    const len = editor.getValue().length;
    document.getElementById('codeCountBadge').textContent = len + ' chars';
    saveCode();
});

// Cursor position
editor.on('cursorActivity', function() {
    const pos = editor.getCursor();
    document.getElementById('cursorLine').textContent = pos.line + 1;
    document.getElementById('cursorCol').textContent  = pos.ch + 1;
});

// ── Language → CodeMirror mode map ──────────────────────────────────
const MODES = {
    python:     'text/x-python',
    python2:    'text/x-python',
    java:       'text/x-java',
    cpp:        'text/x-c++src',
    c:          'text/x-csrc',
    csharp:     'text/x-csharp',
    javascript: 'text/javascript',
    typescript: 'text/typescript',
    php:        'application/x-httpd-php',
    go:         'text/x-go',
    kotlin:     'text/x-kotlin',
    rust:       'text/x-rustsrc',
    ruby:       'text/x-ruby',
    swift:      'text/x-swift',
};

const LANG_LABELS = {
    python:'Python 3.10', python2:'Python 2.7', java:'Java 15',
    cpp:'C++ (GCC)', c:'C (GCC)', csharp:'C#',
    javascript:'JavaScript', typescript:'TypeScript',
    php:'PHP 8.2', go:'Go 1.16', kotlin:'Kotlin',
    rust:'Rust', ruby:'Ruby', swift:'Swift',
};

function getBoilerplate(lang) {
    const bp = {
        python:     `# Write your solution here

`,
        python2:    `# Write your solution here

`,
        java:       `import java.util.*;
import java.io.*;

public class Main {
    public static void main(String[] args) {
        Scanner sc = new Scanner(System.in);
        
    }
}
`,
        cpp:        `#include <bits/stdc++.h>
using namespace std;

int main() {
    ios_base::sync_with_stdio(false);
    cin.tie(NULL);
    
    return 0;
}
`,
        c:          `#include <stdio.h>

int main() {
    
    return 0;
}
`,
        csharp:     `using System;

class Program {
    static void Main() {
        
    }
}
`,
        javascript: `const readline = require("readline");
const rl = readline.createInterface({ input: process.stdin });
const lines = [];
rl.on("line", l => lines.push(l.trim()));
rl.on("close", () => {
    // your solution here
});
`,
        go:         `package main

import "fmt"

func main() {
    
}
`,
        php:        '<' + '?php\n\n',
        rust:       `use std::io::{self, Read};

fn main() {
    let mut input = String::new();
    io::stdin().read_to_string(&mut input).unwrap();
    let mut iter = input.split_whitespace();
    
}
`,

    };
    return bp[lang] || `// Write your solution here

`;

}

function changeLanguage() {
    const lang = document.getElementById('languageSelect').value;
    editor.setOption('mode', MODES[lang] || 'text/plain');
    document.getElementById('editorLang').textContent = LANG_LABELS[lang] || lang;
    loadCode();
}

function resetEditor() {
    const lang = document.getElementById('languageSelect').value;
    if (confirm('Reset code to boilerplate?')) {
        editor.setValue(getBoilerplate(lang));
        localStorage.removeItem(STORAGE_KEY + lang);
    }
}

// Init
loadCode();

// ── Submit ───────────────────────────────────────────────────────────
function submitCode() {
    const code = editor.getValue().trim();
    if (!code) {
        Swal.fire({ toast:true, position:'top-end', icon:'warning', title:'Please write some code first!', showConfirmButton:false, timer:2000 });
        return;
    }

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
    document.getElementById('submitOverlay').classList.add('show');
    hideResult();

    const fd = new FormData();
    fd.append('user_id',    '<?= $_SESSION['id'] ?>');
    fd.append('problem_id', '<?= $problem_id ?>');
    fd.append('language',   document.getElementById('languageSelect').value);
    fd.append('code',       code);

    fetch('codecheck.php', { method:'POST', body: fd })
        .then(r => { if (!r.ok) throw new Error('Server error'); return r.json(); })
        .then(data => {
            document.getElementById('submitOverlay').classList.remove('show');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit';

            if (data.success) {
                showResult(true, data);
            } else {
                showResult(false, data);
            }
        })
        .catch(err => {
            document.getElementById('submitOverlay').classList.remove('show');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit';
            Swal.fire({ toast:true, position:'top-end', icon:'error', title: err.message, showConfirmButton:false, timer:3000 });
        });
}

function showResult(success, data) {
    const panel = document.getElementById('resultPanel');
    const inner = document.getElementById('resultInner');
    panel.classList.remove('hidden');

    let html;
    if (success || (data && data.status === 'Accept')) {
        html = `<div style="display:flex; align-items:center; gap:10px; padding:6px 0;">
            <div style="width:36px; height:36px; background:#dcfce7; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="fas fa-circle-check" style="color:#15803d; font-size:16px;"></i>
            </div>
            <div>
                <div style="font-size:14px; font-weight:700; color:#15803d;">Accepted ✓</div>
                <div style="font-size:11.5px; color:#64748b; margin-top:2px;">All test cases passed</div>
            </div>
            <button onclick="location.reload()" style="margin-left:auto; background:#dcfce7; border:1px solid #bbf7d0; color:#15803d; border-radius:7px; padding:5px 10px; font-size:11.5px; font-weight:600; cursor:pointer;">
                Refresh
            </button>
        </div>`;
    } else {
        const msg = data?.message || data?.status || 'Submission failed';
        html = `<div style="display:flex; align-items:center; gap:10px; padding:6px 0;">
            <div style="width:36px; height:36px; background:#fee2e2; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="fas fa-circle-xmark" style="color:#dc2626; font-size:16px;"></i>
            </div>
            <div>
                <div style="font-size:14px; font-weight:700; color:#dc2626;">${msg}</div>
                <div style="font-size:11.5px; color:#64748b; margin-top:2px;">Check your solution and try again</div>
            </div>
            <button onclick="hideResult()" style="margin-left:auto; background:#fff5f5; border:1px solid #fecaca; color:#dc2626; border-radius:7px; padding:5px 10px; font-size:11.5px; font-weight:600; cursor:pointer;">
                Dismiss
            </button>
        </div>`;
    }
    inner.innerHTML = html;
}

function hideResult() {
    document.getElementById('resultPanel').classList.add('hidden');
}

// ── Tabs ─────────────────────────────────────────────────────────────
function switchTab(tabName, btn) {
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.prob-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tabName).classList.add('active');
    btn.classList.add('active');
}

// ── Keyboard shortcut: Ctrl+Enter to submit ──────────────────────────
editor.addKeyMap({
    'Ctrl-Enter': function() { submitCode(); },
    'Cmd-Enter':  function() { submitCode(); }
});
</script>
</body>
</html>
