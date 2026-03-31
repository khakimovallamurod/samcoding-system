<?php
require_once 'auth_check.php';
require_once '../config.php';

$user_id = $_SESSION['id'];
$cn_problem_id = intval($_GET['id'] ?? 0);
$db = new Database();

if (!$cn_problem_id) {
    header('Location: olympiads.php');
    exit;
}

$cn_problem = $db->get_problem_by_id('contest_problems', $cn_problem_id);
if (!$cn_problem) {
    header('Location: olympiads.php');
    exit;
}

$contest_id = $cn_problem['contest_id'];
$cn_attempts = $db->get_contest_attempts_by_user($user_id, $cn_problem_id, $contest_id);
$examples_result = $db->query("SELECT input, output FROM contest_tests WHERE cn_problem_id = $cn_problem_id LIMIT 2");
$examples = [];
while ($row = mysqli_fetch_assoc($examples_result)) {
    $examples[] = $row;
}

$total_att = count($cn_attempts);
$accepted = count(array_filter($cn_attempts, fn($a) => $a['status'] === 'Accept'));
$acc_rate = $total_att ? round($accepted / $total_att * 100) : 0;

$diff = strtolower($cn_problem['difficulty'] ?? 'easy');
$diffColors = [
    'beginner' => ['#dcfce7', '#15803d'],
    'easy' => ['#dbeafe', '#1d4ed8'],
    'medium' => ['#fef3c7', '#b45309'],
    'hard' => ['#fee2e2', '#b91c1c'],
    'expert' => ['#f3e8ff', '#7e22ce'],
];
[$diff_bg, $diff_color] = $diffColors[$diff] ?? ['#f1f5f9', '#475569'];
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once 'head.php' ?>

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
html, body { margin: 0; padding: 0; background: #f8fafc; font-family: 'Inter', sans-serif; }
body { overflow: hidden; }
.solution-layout { display: grid; grid-template-columns: 1.05fr 0.95fr; height: calc(100vh - 72px); min-height: 640px; overflow: hidden; }
.problem-panel { background:#fff; border-right:1px solid #e2e8f0; display:flex; flex-direction:column; overflow:hidden; }
.editor-panel { display:flex; flex-direction:column; background:#111827; overflow:hidden; }
.prob-header { padding:24px; border-bottom:1px solid #f1f5f9; position:sticky; top:0; background:#fff; z-index:10; }
.prob-header h1 { margin:0; font-size:22px; line-height:1.2; color:#0f172a; }
.prob-header .meta-row { display:flex; flex-wrap:wrap; gap:8px; align-items:center; margin-top:12px; }
.tag-pill { display:inline-flex; align-items:center; gap:6px; border-radius:999px; padding:6px 12px; font-size:12px; font-weight:700; }
.tag-plain { background:#f1f5f9; color:#475569; }
.diff-pill { color:<?= $diff_color ?>; background:<?= $diff_bg ?>; }
.prob-tabs { display:flex; gap:0; border-bottom:1px solid #e2e8f0; background:#f8fafc; }
.prob-tab { flex:1; padding:12px 18px; background:none; border:none; cursor:pointer; color:#64748b; font-weight:700; font-size:13px; transition: all 0.15s; }
.prob-tab.active { color:#1d4ed8; background:#fff; border-bottom:3px solid #2563eb; }
.prob-tab:hover { background:#f1f5f9; }
.tab-pane { flex:1; overflow:auto; padding:22px; display:none; }
.tab-pane.active { display:block; }
.section-title { margin:20px 0 10px; color:#475569; font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; }
.problem-text { color:#334155; line-height:1.8; font-size:14px; white-space:pre-wrap; }
.io-block { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:14px; font-family:'Fira Code', monospace; font-size:13px; color:#334155; white-space:pre-wrap; }
.example-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-top:12px; }
.example-box { background:#0f172a; border-radius:12px; overflow:hidden; }
.example-box-header { padding:10px 12px; background:#111827; color:#94a3b8; font-size:11px; text-transform:uppercase; letter-spacing:0.06em; }
.example-box-content { padding:14px; font-family:'Fira Code', monospace; font-size:13px; color:#f8fafc; white-space:pre-wrap; min-height:80px; }
.empty-state { padding:32px 0; text-align:center; color:#64748b; }
.empty-state h3 { margin:0 0 8px; font-size:16px; color:#0f172a; }
.attempt-list { display:grid; gap:10px; }
.attempt-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:16px; display:grid; grid-template-columns:1fr auto; gap:14px; align-items:center; }
.attempt-meta { display:flex; flex-wrap:wrap; gap:10px; font-size:13px; color:#475569; }
.status-pill { border-radius:999px; padding:4px 12px; font-size:12px; font-weight:700; }
.status-accept { background:#dcfce7; color:#15803d; }
.status-wrong { background:#fee2e2; color:#b91c1c; }
.status-runtime { background:#fef3c7; color:#b45309; }
.status-other { background:#e2e8f0; color:#475569; }
.lang-pill { background:#e0e7ff; color:#3730a3; }
.editor-header { display:flex; justify-content:space-between; align-items:center; gap:12px; padding:18px 22px; background:#111827; border-bottom:1px solid #1f2937; }
.editor-header .label { color:#94a3b8; font-size:13px; }
.lang-select { min-width:170px; padding:8px 12px; border-radius:10px; background:#0f172a; border:1px solid #374151; color:#f8fafc; }
.editor-actions { display:flex; gap:10px; flex-wrap:wrap; }
.btn-editor { border:none; border-radius:10px; padding:10px 16px; font-size:13px; font-weight:700; cursor:pointer; transition:all 0.15s; }
.btn-reset { background:#1f2937; color:#cbd5e1; }
.btn-reset:hover { background:#374151; }
.btn-submit-code { background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; }
.btn-submit-code:hover { opacity:0.95; }
.cm-wrapper { flex:1; overflow:hidden; }
.CodeMirror { height: 100%; font-size:14px; line-height:1.5; }
.code-info { padding:12px 22px; background:#111827; border-top:1px solid #1f2937; color:#94a3b8; font-size:12px; display:flex; justify-content:space-between; align-items:center; }
.result-panel { padding:16px 22px; background:#111827; color:#e2e8f0; font-size:13px; border-top:1px solid #1f2937; min-height:80px; }
.result-panel.hidden { display:none; }
.solution-footer { padding:18px 22px; background:#111827; border-top:1px solid #1f2937; display:flex; justify-content:flex-end; }
@media (max-width: 1080px) { .solution-layout { grid-template-columns: 1fr; height:auto; } .problem-panel { max-height: calc(100vh - 72px); } .editor-panel { min-height: 500px; } }
</style>

<body>
<div class="admin-root">
    <?php include_once 'sidebar.php' ?>
    <div id="content" style="overflow:hidden;">
        <?php include_once 'topbar.php' ?>
        <div class="solution-layout">
            <div class="problem-panel">
                <div class="prob-header">
                    <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:12px;">
                        <a href="contest_problems.php?contest_id=<?= $contest_id ?>" style="color:#2563eb; text-decoration:none; font-size:13px; display:inline-flex; align-items:center; gap:6px;">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <span style="color:#94a3b8; font-size:12px;">Contest problem</span>
                    </div>
                    <h1><?= htmlspecialchars($cn_problem['title']) ?></h1>
                    <div class="meta-row">
                        <span class="tag-pill diff-pill"><?= htmlspecialchars($cn_problem['difficulty'] ?? 'Easy') ?></span>
                        <?php if (!empty($cn_problem['category'])): ?>
                        <span class="tag-pill tag-plain">#<?= htmlspecialchars($cn_problem['category']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($cn_problem['time_limit'])): ?>
                        <span class="tag-pill tag-plain"><i class="fas fa-clock"></i> <?= intval($cn_problem['time_limit']) ?> ms</span>
                        <?php endif; ?>
                        <?php if (!empty($cn_problem['memory_limit'])): ?>
                        <span class="tag-pill tag-plain"><i class="fas fa-memory"></i> <?= intval($cn_problem['memory_limit'] / 1024) ?> MB</span>
                        <?php endif; ?>
                        <span class="tag-pill tag-plain" style="margin-left:auto;">Author: <?= htmlspecialchars($cn_problem['author_name'] ?? 'System') ?></span>
                    </div>
                    <div class="meta-row" style="margin-top:10px; gap:8px;">
                        <span class="tag-pill tag-plain">Total submissions: <?= $total_att ?></span>
                        <span class="tag-pill tag-plain">Accepted: <?= $accepted ?></span>
                        <span class="tag-pill tag-plain">AC Rate: <?= $acc_rate ?>%</span>
                    </div>
                </div>

                <div class="prob-tabs">
                    <button class="prob-tab active" onclick="switchTab('problem', this)">Problem</button>
                    <button class="prob-tab" onclick="switchTab('attempts', this)">Last attempts</button>
                </div>

                <div id="tab-problem" class="tab-pane active">
                    <?php if (!empty($cn_problem['descript'])): ?>
                    <div class="section-title">Description</div>
                    <div class="problem-text"><?= nl2br(htmlspecialchars($cn_problem['descript'])) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($cn_problem['input_format'])): ?>
                    <div class="section-title">Input format</div>
                    <div class="io-block"><?= htmlspecialchars($cn_problem['input_format']) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($cn_problem['output_format'])): ?>
                    <div class="section-title">Output format</div>
                    <div class="io-block"><?= htmlspecialchars($cn_problem['output_format']) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($cn_problem['constraints'])): ?>
                    <div class="section-title">Constraints</div>
                    <div class="io-block"><?= nl2br(htmlspecialchars($cn_problem['constraints'])) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($examples)): ?>
                    <div class="section-title">Sample tests</div>
                    <div class="example-grid">
                        <?php foreach ($examples as $index => $example): ?>
                        <div class="example-box">
                            <div class="example-box-header">Input <?= $index + 1 ?></div>
                            <div class="example-box-content"><?= htmlspecialchars($example['input']) ?></div>
                        </div>
                        <div class="example-box">
                            <div class="example-box-header">Output <?= $index + 1 ?></div>
                            <div class="example-box-content"><?= htmlspecialchars($example['output']) ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($cn_problem['izoh'])): ?>
                    <div class="section-title">Note</div>
                    <div class="problem-text"><?= nl2br(htmlspecialchars($cn_problem['izoh'])) ?></div>
                    <?php endif; ?>
                </div>

                <div id="tab-attempts" class="tab-pane">
                    <?php if (empty($cn_attempts)): ?>
                        <div class="empty-state">
                            <h3>No attempts yet</h3>
                            <p>Submit your first solution and review results here.</p>
                        </div>
                    <?php else: ?>
                        <div class="attempt-list">
                            <?php foreach ($cn_attempts as $attempt): ?>
                                <?php
                                $status = $attempt['status'];
                                if ($status === 'Accept') { $badge = 'status-accept'; }
                                elseif (strpos($status, 'Wrong Answer') !== false) { $badge = 'status-wrong'; }
                                elseif (strpos($status, 'Runtime Error') !== false) { $badge = 'status-runtime'; }
                                else { $badge = 'status-other'; }
                                ?>
                                <div class="attempt-card">
                                    <div>
                                        <div style="font-size:14px; font-weight:700; color:#0f172a;">Attempt #<?= str_pad($attempt['attempt_id'], 5, '0', STR_PAD_LEFT) ?></div>
                                        <div class="attempt-meta">
                                            <span class="lang-pill"><?= htmlspecialchars($attempt['language']) ?></span>
                                            <span><?= intval($attempt['runTime']) ?> ms</span>
                                            <span><?= intval($attempt['memory'] / 1024) ?> KB</span>
                                            <span>Passed: <?= intval($attempt['tests_passed']) ?>/<?= intval($attempt['total_tests']) ?></span>
                                        </div>
                                        <div style="margin-top:8px; color:#475569; font-size:13px;">Submitted: <?= date('d.m.Y H:i', strtotime($attempt['created_at'])) ?></div>
                                    </div>
                                    <div style="display:flex; flex-direction:column; align-items:flex-end; gap:10px;">
                                        <span class="status-pill <?= $badge ?>"><?= htmlspecialchars($status) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="editor-panel">
                <div class="editor-header">
                    <div>
                        <div class="label">Solution editor</div>
                        <div style="color:#f8fafc; font-size:16px; font-weight:700; margin-top:4px;">Submit code for this contest problem</div>
                    </div>
                    <div class="editor-actions">
                        <select id="languageSelect" class="lang-select">
                            <option value="python">Python 3</option>
                            <option value="cpp">C++</option>
                            <option value="java">Java</option>
                            <option value="php">PHP</option>
                            <option value="javascript">JavaScript</option>
                            <option value="go">Go</option>
                            <option value="rust">Rust</option>
                        </select>
                        <button type="button" class="btn-editor btn-reset" id="resetBtn"><i class="fas fa-rotate-left"></i> Reset</button>
                    </div>
                </div>
                <div class="cm-wrapper">
                    <textarea id="codeEditor" name="code" placeholder="Write your solution here..."></textarea>
                </div>
                <div class="code-info">
                    <span>Characters: <strong id="codeLength">0</strong></span>
                    <span>Language: <strong id="currentLanguage">Python 3</strong></span>
                </div>
                <div id="resultPanel" class="result-panel hidden"></div>
                <div class="solution-footer">
                    <button id="submitBtn" class="btn-editor btn-submit-code"><i class="fas fa-paper-plane"></i> Submit solution</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="submitOverlay" class="submit-overlay">
    <div class="spin-ring"></div>
    <div style="color:#fff; font-size:15px;">Submitting solution, please wait...</div>
</div>

<script>
let editor = null;
function switchTab(tab, btn) {
    document.querySelectorAll('.prob-tab').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-' + tab).classList.add('active');
}

function sanitizeOutput(text) {
    return text.replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

function setResult(message, type) {
    const panel = document.getElementById('resultPanel');
    panel.classList.remove('hidden');
    panel.innerHTML = `<strong style="color:${type === 'success' ? '#a7f3d0' : '#fecaca'};">${sanitizeOutput(message)}</strong>`;
}

window.addEventListener('DOMContentLoaded', () => {
    const textarea = document.getElementById('codeEditor');
    editor = CodeMirror.fromTextArea(textarea, {
        mode: 'python',
        theme: 'dracula',
        lineNumbers: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        indentUnit: 4,
        tabSize: 4,
        lineWrapping: true
    });
    editor.setSize('100%', '100%');

    const languageSelect = document.getElementById('languageSelect');
    const currentLanguage = document.getElementById('currentLanguage');
    const codeLength = document.getElementById('codeLength');
    const resultPanel = document.getElementById('resultPanel');

    const languageModes = {
        python: 'python',
        cpp: 'text/x-c++src',
        java: 'text/x-java',
        php: 'application/x-httpd-php',
        javascript: 'javascript',
        go: 'go',
        rust: 'rust'
    };

    function refreshEditor() {
        const lang = languageSelect.value;
        editor.setOption('mode', languageModes[lang] || 'python');
        currentLanguage.textContent = languageSelect.options[languageSelect.selectedIndex].text;
    }

    languageSelect.addEventListener('change', refreshEditor);
    refreshEditor();

    editor.on('change', () => {
        codeLength.textContent = editor.getValue().length;
        window.localStorage.setItem('cn_solution_code_<?= $cn_problem_id ?>', editor.getValue());
    });

    const savedCode = window.localStorage.getItem('cn_solution_code_<?= $cn_problem_id ?>');
    if (savedCode) {
        editor.setValue(savedCode);
        codeLength.textContent = savedCode.length;
    }

    document.getElementById('resetBtn').addEventListener('click', () => {
        editor.setValue('');
        codeLength.textContent = '0';
        window.localStorage.removeItem('cn_solution_code_<?= $cn_problem_id ?>');
        resultPanel.classList.add('hidden');
    });

    document.getElementById('submitBtn').addEventListener('click', async () => {
        const code = editor.getValue().trim();
        if (!code) {
            setResult('Please enter your solution before submitting.', 'error');
            return;
        }

        const overlay = document.getElementById('submitOverlay');
        overlay.classList.add('show');

        const payload = new FormData();
        payload.append('user_id', '<?= $user_id ?>');
        payload.append('problem_id', '<?= $cn_problem_id ?>');
        payload.append('contest_id', '<?= $contest_id ?>');
        payload.append('language', languageSelect.value);
        payload.append('code', code);

        try {
            const response = await fetch('cn_codecheck.php', { method: 'POST', body: payload });
            const data = await response.json();
            overlay.classList.remove('show');
            if (data.success) {
                setResult(data.message || 'Solution submitted successfully.', 'success');
                setTimeout(() => location.reload(), 1600);
            } else {
                setResult(data.message || 'Submission failed.', 'error');
            }
        } catch (error) {
            overlay.classList.remove('show');
            setResult('Network error. Please try again.', 'error');
        }
    });
});
</script>
</body>
</html>
