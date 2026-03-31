<?php
require_once 'auth_check.php';
require_once '../config.php';
$db = new Database();
$musobaqalar = $db->get_data_by_table_all("contests", "ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once 'head.php' ?>
<body>
<div class="admin-root">

    <?php include_once 'sidebar.php' ?>

    <div id="content">
        <?php include_once 'topbar.php' ?>
        <div id="main-content">

            <!-- Page header -->
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
                <div>
                    <h1 style="font-size:22px; font-weight:700; color:#0f172a; margin:0 0 4px;">Olympiads</h1>
                    <p style="color:#64748b; font-size:13px; margin:0;">Manage programming contests and olympiads</p>
                </div>
                <button id="openAddModal"
                    style="display:inline-flex; align-items:center; gap:6px; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; border:none; border-radius:10px; padding:9px 18px; font-size:13px; font-weight:600; cursor:pointer; box-shadow:0 2px 10px rgba(37,99,235,0.3); transition: all 0.2s;"
                    onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 16px rgba(37,99,235,0.4)';"
                    onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 10px rgba(37,99,235,0.3)';">
                    <i class="fas fa-circle-plus"></i> New Contest
                </button>
            </div>

            <!-- Stats row -->
            <?php
                $total_c    = count($musobaqalar);
                $waiting_c  = count(array_filter($musobaqalar, fn($c) => $c['status'] == 0));
                $active_c   = count(array_filter($musobaqalar, fn($c) => $c['status'] == 1));
                $ended_c    = count(array_filter($musobaqalar, fn($c) => $c['status'] == 2));
            ?>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap:12px; margin-bottom:24px;">
                <div class="stat-card" style="display:flex; align-items:center; gap:10px;">
                    <div style="width:40px; height:40px; background:#eff6ff; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fas fa-trophy" style="color:#2563eb;"></i>
                    </div>
                    <div>
                        <div style="font-size:20px; font-weight:800; color:#0f172a;"><?= $total_c ?></div>
                        <div style="font-size:11px; color:#64748b;">Total</div>
                    </div>
                </div>
                <div class="stat-card" style="display:flex; align-items:center; gap:10px;">
                    <div style="width:40px; height:40px; background:#dcfce7; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fas fa-circle-play" style="color:#15803d;"></i>
                    </div>
                    <div>
                        <div style="font-size:20px; font-weight:800; color:#0f172a;"><?= $active_c ?></div>
                        <div style="font-size:11px; color:#15803d; font-weight:500;">Active</div>
                    </div>
                </div>
                <div class="stat-card" style="display:flex; align-items:center; gap:10px;">
                    <div style="width:40px; height:40px; background:#fef3c7; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fas fa-clock" style="color:#d97706;"></i>
                    </div>
                    <div>
                        <div style="font-size:20px; font-weight:800; color:#0f172a;"><?= $waiting_c ?></div>
                        <div style="font-size:11px; color:#d97706; font-weight:500;">Upcoming</div>
                    </div>
                </div>
                <div class="stat-card" style="display:flex; align-items:center; gap:10px;">
                    <div style="width:40px; height:40px; background:#f1f5f9; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="fas fa-flag-checkered" style="color:#64748b;"></i>
                    </div>
                    <div>
                        <div style="font-size:20px; font-weight:800; color:#0f172a;"><?= $ended_c ?></div>
                        <div style="font-size:11px; color:#64748b;">Ended</div>
                    </div>
                </div>
            </div>

            <!-- Contest Cards Grid -->
            <?php if (empty($musobaqalar)): ?>
            <div class="content-card" style="padding:48px; text-align:center;">
                <i class="fas fa-trophy" style="font-size:40px; color:#cbd5e1; margin-bottom:12px; display:block;"></i>
                <h3 style="color:#475569; font-size:16px; margin-bottom:6px;">No contests yet</h3>
                <p style="color:#94a3b8; font-size:13px;">Click "New Contest" to create your first olympiad.</p>
            </div>
            <?php else: ?>
            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap:18px;">
                <?php foreach ($musobaqalar as $contest):
                    $s = intval($contest['status']);
                    $start = strtotime($contest['start_time']);
                    $end   = strtotime($contest['end_time']);
                    $diff  = $end - $start;
                    $hours = floor($diff / 3600);
                    $mins  = floor(($diff % 3600) / 60);
                    $duration = $hours > 0 ? "{$hours}h" . ($mins > 0 ? " {$mins}m" : '') : "{$mins}m";

                    if ($s === 0) {
                        $badge_bg = '#fef3c7'; $badge_color = '#b45309';
                        $badge_txt = 'Upcoming'; $badge_icon = 'fa-clock';
                        $accent = '#f59e0b'; $card_top = 'linear-gradient(135deg,#fffbeb,#fef3c7)';
                    } elseif ($s === 1) {
                        $badge_bg = '#dcfce7'; $badge_color = '#15803d';
                        $badge_txt = 'Live'; $badge_icon = 'fa-circle-play';
                        $accent = '#22c55e'; $card_top = 'linear-gradient(135deg,#f0fdf4,#dcfce7)';
                    } else {
                        $badge_bg = '#f1f5f9'; $badge_color = '#475569';
                        $badge_txt = 'Ended'; $badge_icon = 'fa-flag-checkered';
                        $accent = '#94a3b8'; $card_top = 'linear-gradient(135deg,#f8fafc,#f1f5f9)';
                    }
                ?>
                <div class="content-card" style="overflow:visible; position:relative; transition: box-shadow 0.2s, transform 0.2s;"
                     onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 32px rgba(0,0,0,0.1)';"
                     onmouseout="this.style.transform=''; this.style.boxShadow='';">

                    <!-- Colored top bar -->
                    <div style="height:4px; background:<?= $accent ?>; border-radius:16px 16px 0 0; margin:-1px -1px 0;"></div>

                    <div style="padding:20px;">
                        <!-- Header row -->
                        <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:10px; margin-bottom:12px;">
                            <div style="flex:1; min-width:0;">
                                <h3 style="font-size:15px; font-weight:700; color:#0f172a; margin:0 0 6px; line-height:1.3; overflow:hidden; text-overflow:ellipsis; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">
                                    <?= htmlspecialchars($contest['title']) ?>
                                </h3>
                                <span style="display:inline-flex; align-items:center; gap:4px; background:<?= $badge_bg ?>; color:<?= $badge_color ?>; border-radius:99px; padding:3px 10px; font-size:11px; font-weight:700;">
                                    <i class="fas <?= $badge_icon ?>" style="font-size:9px;"></i>
                                    <?= $badge_txt ?>
                                </span>
                            </div>
                        </div>

                        <!-- Description -->
                        <?php if (!empty($contest['description'])): ?>
                        <p style="color:#64748b; font-size:12.5px; line-height:1.5; margin-bottom:14px; overflow:hidden; text-overflow:ellipsis; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">
                            <?= htmlspecialchars($contest['description']) ?>
                        </p>
                        <?php endif; ?>

                        <!-- Time info -->
                        <div style="background:#f8fafc; border-radius:10px; padding:12px; margin-bottom:14px; display:flex; flex-direction:column; gap:6px;">
                            <div style="display:flex; align-items:center; justify-content:space-between; font-size:12px;">
                                <span style="color:#64748b; display:flex; align-items:center; gap:5px;">
                                    <i class="fas fa-play" style="color:#22c55e; width:12px;"></i> Start
                                </span>
                                <strong style="color:#1e293b;"><?= date('d M Y, H:i', $start) ?></strong>
                            </div>
                            <div style="height:1px; background:#e2e8f0;"></div>
                            <div style="display:flex; align-items:center; justify-content:space-between; font-size:12px;">
                                <span style="color:#64748b; display:flex; align-items:center; gap:5px;">
                                    <i class="fas fa-stop" style="color:#ef4444; width:12px;"></i> End
                                </span>
                                <strong style="color:#1e293b;"><?= date('d M Y, H:i', $end) ?></strong>
                            </div>
                            <div style="height:1px; background:#e2e8f0;"></div>
                            <div style="display:flex; align-items:center; justify-content:space-between; font-size:12px;">
                                <span style="color:#64748b; display:flex; align-items:center; gap:5px;">
                                    <i class="fas fa-hourglass-half" style="color:#f59e0b; width:12px;"></i> Duration
                                </span>
                                <strong style="color:#1e293b;"><?= $duration ?></strong>
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div style="display:flex; gap:6px; flex-wrap:wrap;">
                            <a href="contest_problems.php?contest_id=<?= $contest['id'] ?>"
                               style="flex:1; min-width:80px; display:inline-flex; align-items:center; justify-content:center; gap:5px; background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; border-radius:8px; padding:7px 10px; font-size:12px; font-weight:600; text-decoration:none; transition: all 0.15s;"
                               onmouseover="this.style.background='#dcfce7';" onmouseout="this.style.background='#f0fdf4';">
                                <i class="fas fa-list-check" style="font-size:11px;"></i> Problems
                            </a>
                            <button onclick="openEditModal(<?= htmlspecialchars(json_encode($contest)) ?>)"
                                style="display:inline-flex; align-items:center; justify-content:center; gap:5px; background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; border-radius:8px; padding:7px 12px; font-size:12px; font-weight:600; cursor:pointer; transition: all 0.15s;"
                                onmouseover="this.style.background='#dbeafe';" onmouseout="this.style.background='#eff6ff';">
                                <i class="fas fa-pen-to-square" style="font-size:11px;"></i> Edit
                            </button>
                            <button onclick="openDeleteModal(<?= (int)$contest['id'] ?>)"
                                style="display:inline-flex; align-items:center; justify-content:center; gap:5px; background:#fff5f5; color:#dc2626; border:1px solid #fecaca; border-radius:8px; padding:7px 12px; font-size:12px; font-weight:600; cursor:pointer; transition: all 0.15s;"
                                onmouseover="this.style.background='#fee2e2';" onmouseout="this.style.background='#fff5f5';">
                                <i class="fas fa-trash" style="font-size:11px;"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </div><!-- /main-content -->
    </div><!-- /content -->
</div><!-- /admin-root -->

<!-- ── Add Contest Modal ───────────────────────────────────────── -->
<div id="addModal" class="modal-bg" style="display:none;">
    <div class="modal-box" style="max-width:500px;">
        <div class="modal-hdr">
            <div style="display:flex; align-items:center; gap:8px;">
                <div style="width:32px; height:32px; background:#eff6ff; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-circle-plus" style="color:#2563eb;"></i>
                </div>
                <span style="font-size:15px; font-weight:700; color:#0f172a;">New Contest</span>
            </div>
            <button onclick="closeModal('addModal')" class="modal-close">&times;</button>
        </div>
        <form id="addContestForm" style="padding:20px;">
            <div class="form-row">
                <label class="form-lbl">Title *</label>
                <input type="text" name="title" class="form-inp" required placeholder="Contest title">
            </div>
            <div class="form-row">
                <label class="form-lbl">Description</label>
                <textarea name="description" class="form-inp" rows="3" placeholder="Short description..."></textarea>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div class="form-row">
                    <label class="form-lbl">Start Time *</label>
                    <input type="datetime-local" name="start_time" class="form-inp" required>
                </div>
                <div class="form-row">
                    <label class="form-lbl">End Time *</label>
                    <input type="datetime-local" name="end_time" class="form-inp" required>
                </div>
            </div>
            <div class="form-row">
                <label class="form-lbl">Status *</label>
                <select name="status" class="form-inp">
                    <option value="0">⏳ Upcoming</option>
                    <option value="1">🟢 Active</option>
                    <option value="2">🏁 Ended</option>
                </select>
            </div>
            <div style="display:flex; gap:10px; margin-top:4px;">
                <button type="button" onclick="closeModal('addModal')" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-primary-modal">
                    <i class="fas fa-circle-plus"></i> Create Contest
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ── Edit Contest Modal ──────────────────────────────────────── -->
<div id="editModal" class="modal-bg" style="display:none;">
    <div class="modal-box" style="max-width:500px;">
        <div class="modal-hdr">
            <div style="display:flex; align-items:center; gap:8px;">
                <div style="width:32px; height:32px; background:#fffbeb; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-pen-to-square" style="color:#d97706;"></i>
                </div>
                <span style="font-size:15px; font-weight:700; color:#0f172a;">Edit Contest</span>
            </div>
            <button onclick="closeModal('editModal')" class="modal-close">&times;</button>
        </div>
        <form id="editContestForm" style="padding:20px;">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-row">
                <label class="form-lbl">Title *</label>
                <input type="text" name="title" id="edit_title" class="form-inp" required>
            </div>
            <div class="form-row">
                <label class="form-lbl">Description</label>
                <textarea name="description" id="edit_description" class="form-inp" rows="3"></textarea>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div class="form-row">
                    <label class="form-lbl">Start Time *</label>
                    <input type="datetime-local" name="start_time" id="edit_start" class="form-inp" required>
                </div>
                <div class="form-row">
                    <label class="form-lbl">End Time *</label>
                    <input type="datetime-local" name="end_time" id="edit_end" class="form-inp" required>
                </div>
            </div>
            <div class="form-row">
                <label class="form-lbl">Status *</label>
                <select name="status" id="edit_status" class="form-inp">
                    <option value="0">⏳ Upcoming</option>
                    <option value="1">🟢 Active</option>
                    <option value="2">🏁 Ended</option>
                </select>
            </div>
            <div style="display:flex; gap:10px; margin-top:4px;">
                <button type="button" onclick="closeModal('editModal')" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-warn-modal">
                    <i class="fas fa-floppy-disk"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ── Delete Modal ────────────────────────────────────────────── -->
<div id="deleteModal" class="modal-bg" style="display:none;">
    <div class="modal-box" style="max-width:400px;">
        <div style="padding:28px; text-align:center;">
            <div style="width:52px; height:52px; background:#fee2e2; border-radius:14px; display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
                <i class="fas fa-trash" style="color:#dc2626; font-size:20px;"></i>
            </div>
            <h3 style="font-size:16px; font-weight:700; color:#0f172a; margin-bottom:6px;">Delete Contest?</h3>
            <p style="color:#64748b; font-size:13px; margin-bottom:22px;">This action cannot be undone.</p>
            <div style="display:flex; gap:10px;">
                <button onclick="closeModal('deleteModal')" class="btn-cancel" style="flex:1;">Cancel</button>
                <button id="confirmDeleteBtn" class="btn-danger-modal" style="flex:1;">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.modal-bg {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 9999;
    display: flex; align-items: center; justify-content: center;
    padding: 16px;
    backdrop-filter: blur(2px);
}
.modal-box {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.18);
    width: 100%;
    overflow: hidden;
    animation: modalIn 0.2s ease;
}
@keyframes modalIn {
    from { opacity:0; transform: scale(0.95) translateY(10px); }
    to   { opacity:1; transform: scale(1) translateY(0); }
}
.modal-hdr {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
}
.modal-close {
    background: none; border: none; cursor: pointer;
    color: #94a3b8; font-size: 22px; line-height: 1;
    padding: 4px 6px; border-radius: 6px; transition: all 0.15s;
}
.modal-close:hover { background: #f1f5f9; color: #475569; }
.form-row { margin-bottom: 14px; }
.form-lbl { display: block; font-size: 12.5px; font-weight: 600; color: #374151; margin-bottom: 5px; }
.form-inp {
    width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px;
    padding: 9px 12px; font-size: 13px; color: #1e293b; outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    background: #f8fafc;
}
.form-inp:focus { border-color: #6366f1; background: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
textarea.form-inp { resize: vertical; }
.btn-cancel {
    flex: 1; padding: 10px; border: 1.5px solid #e2e8f0; background: #f8fafc;
    border-radius: 10px; font-size: 13px; font-weight: 600; color: #475569;
    cursor: pointer; transition: background 0.15s;
}
.btn-cancel:hover { background: #f1f5f9; }
.btn-primary-modal {
    flex: 1; padding: 10px; border: none;
    background: linear-gradient(135deg,#2563eb,#1d4ed8);
    border-radius: 10px; font-size: 13px; font-weight: 600; color: #fff;
    cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;
    transition: opacity 0.15s;
}
.btn-primary-modal:hover { opacity: 0.9; }
.btn-warn-modal {
    flex: 1; padding: 10px; border: none;
    background: linear-gradient(135deg,#f59e0b,#d97706);
    border-radius: 10px; font-size: 13px; font-weight: 600; color: #fff;
    cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;
    transition: opacity 0.15s;
}
.btn-warn-modal:hover { opacity: 0.9; }
.btn-danger-modal {
    flex: 1; padding: 10px; border: none;
    background: linear-gradient(135deg,#ef4444,#dc2626);
    border-radius: 10px; font-size: 13px; font-weight: 600; color: #fff;
    cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;
    transition: opacity 0.15s;
}
.btn-danger-modal:hover { opacity: 0.9; }
</style>

<script src="../js/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
let deleteContestId = null;

function openModal(id) {
    document.getElementById(id).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
    document.body.style.overflow = '';
}
// Close on backdrop click
document.querySelectorAll('.modal-bg').forEach(m => {
    m.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
    });
});

document.getElementById('openAddModal').addEventListener('click', () => openModal('addModal'));

function openEditModal(contest) {
    document.getElementById('edit_id').value          = contest.id;
    document.getElementById('edit_title').value       = contest.title;
    document.getElementById('edit_description').value = contest.description;
    document.getElementById('edit_start').value       = contest.start_time.replace(' ', 'T');
    document.getElementById('edit_end').value         = contest.end_time.replace(' ', 'T');
    document.getElementById('edit_status').value      = contest.status;
    openModal('editModal');
}

function openDeleteModal(id) {
    deleteContestId = id;
    openModal('deleteModal');
}

// ── Add contest ──
$('#addContestForm').on('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('[type=submit]');
    btn.disabled = true;
    btn.textContent = 'Creating...';

    $.ajax({
        url: 'insert/add_olimpiads.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(r) {
            closeModal('addModal');
            $('#addContestForm')[0].reset();
            if (r.success) {
                toastr.success(r.message || 'Contest created!');
                setTimeout(() => location.reload(), 1200);
            } else {
                toastr.error(r.message || 'Failed to create contest.');
            }
        },
        error: function() { toastr.error('Server error.'); },
        complete: function() { btn.disabled = false; btn.innerHTML = '<i class="fas fa-circle-plus"></i> Create Contest'; }
    });
});

// ── Edit contest ──
$('#editContestForm').on('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('[type=submit]');
    btn.disabled = true;

    $.ajax({
        url: 'update/update_contest.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(r) {
            closeModal('editModal');
            if (r.success) {
                toastr.success(r.message || 'Contest updated!');
                setTimeout(() => location.reload(), 1200);
            } else {
                toastr.error(r.message || 'Update failed.');
            }
        },
        error: function() { toastr.error('Server error.'); },
        complete: function() { btn.disabled = false; }
    });
});

// ── Delete contest ──
document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!deleteContestId) return;
    this.textContent = 'Deleting...';
    this.disabled = true;

    fetch('delete/delete_contest.php?id=' + deleteContestId)
        .then(r => r.json())
        .then(data => {
            closeModal('deleteModal');
            if (data.success) {
                toastr.success(data.message || 'Contest deleted!');
                setTimeout(() => location.reload(), 1200);
            } else {
                toastr.error(data.message || 'Delete failed.');
            }
        })
        .catch(() => { closeModal('deleteModal'); toastr.error('Server error.'); });
});
</script>
</body>
</html>
