<?php
session_start();
if (empty($_SESSION['admin_id'])) {
  header('Location: admin_login.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard | NIELIT Chhattisgarh</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

  <style>
    :root{
      --sidebar-w: 260px;
      --radius: 16px;
    }
    body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, sans-serif; background:#f6f7fb; }
    .app { min-height: 100vh; display:flex; }
    .sidebar {
      width: var(--sidebar-w);
      background: #0d6efd;
      color:#fff;
      position: sticky; top:0; height: 100vh;
      box-shadow: 0 10px 30px rgba(13,110,253,.25) inset;
    }
    .sidebar a { color:#eaf2ff; text-decoration: none; }
    .sidebar .brand { font-weight: 800; letter-spacing:-.02em; }
    .sidebar .nav-link {
      color:#eaf2ff; border-radius: 10px; margin: 2px 8px; padding:.65rem .9rem;
    }
    .sidebar .nav-link.active, .sidebar .nav-link:hover {
      background: rgba(255,255,255,.12);
      color: #fff;
    }
    .content { flex:1; }
    .topbar {
      background:#fff; border-bottom:1px solid #eef0f5;
      position: sticky; top:0; z-index: 10;
    }
    .card-soft {
      border:1px solid #e9eef7; border-radius: var(--radius); background:#fff;
      box-shadow: 0 10px 24px rgba(16,24,40,.05);
    }
    .table thead th { background:#f3f6fb; }
    .status-badge { border-radius: 999px; padding:.35rem .6rem; font-weight:700; font-size:.75rem; }
    .status-live { background:#eafaf1; color:#0b7a45; }
    .status-draft { background:#fff7ed; color:#9a3412; }
    .status-arch { background:#eef2ff; color:#3730a3; }
    .btn-modern { border-radius: 10px; font-weight:700; }
    .search-input { border-radius: 10px; }
    .footer { color:#6b7280; }
    @media (max-width: 991.98px) {
      .sidebar { position: fixed; left:-100%; transition: left .25s ease; }
      .sidebar.open { left:0; }
      .content { margin-left: 0; }
    }
  </style>
</head>
<body>
<div class="app">

  <!-- Sidebar -->
  <aside class="sidebar d-flex flex-column p-3">
    <div class="d-flex align-items-center gap-2 mb-3">
      <i class="fa-solid fa-graduation-cap fs-4 text-white"></i>
      <span class="brand fs-5">NIELIT Admin</span>
    </div>
    <hr class="border-light border-opacity-25">
    <nav class="nav nav-pills flex-column mb-auto" id="sideNav">
      <a class="nav-link active" href="#" data-target="panel-notices"><i class="fa-regular fa-bell me-2"></i> Notice Board</a>
      <a class="nav-link" href="#" data-target="panel-courses"><i class="fa-solid fa-book-open me-2"></i> Courses <span class="text-white-50 small">(placeholder)</span></a>
      <a class="nav-link" href="#" data-target="panel-settings"><i class="fa-solid fa-gear me-2"></i> Settings</a>
    </nav>
    <hr class="border-light border-opacity-25">
    <div class="mt-auto small">
      <div class="text-white-50">Logged in as</div>
      <div class="fw-semibold">Administrator</div>
      <a class="d-inline-flex align-items-center gap-1 mt-2" href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
    </div>
  </aside>

  <!-- Main content -->
  <section class="content d-flex flex-column w-100">

    <!-- Topbar -->
    <div class="topbar px-3 px-md-4 py-3 d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-2">
        <button class="btn btn-outline-primary d-lg-none" id="menuBtn"><i class="fa-solid fa-bars"></i></button>
        <h1 class="h5 mb-0 fw-bold">Admin Dashboard</h1>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a class="btn btn-outline-secondary btn-sm" href="index.html" target="_blank"><i class="fa-regular fa-window-restore me-1"></i> View Site</a>
        <button class="btn btn-primary btn-sm" id="exportBtn"><i class="fa-solid fa-download me-1"></i> Export Data</button>
        <label class="btn btn-outline-secondary btn-sm mb-0">
          <i class="fa-solid fa-upload me-1"></i> Import Data
          <input type="file" id="importFile" accept="application/json" hidden>
        </label>
      </div>
    </div>

    <!-- Panels wrapper -->
    <div class="p-3 p-md-4">

      <!-- Notices Panel -->
      <div id="panel-notices" class="card-soft p-3 p-md-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
          <div>
            <h2 class="h5 fw-bold mb-0">Notice Board</h2>
            <div class="text-muted small">Create, update, and delete notices shown on the website.</div>
          </div>
          <div class="d-flex gap-2">
            <input id="searchInput" class="form-control form-control-sm search-input" placeholder="Search notice...">
            <button class="btn btn-success btn-modern btn-sm" id="addNoticeBtn"><i class="fa-solid fa-circle-plus me-1"></i> Add Notice</button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Title</th>
                <th class="d-none d-md-table-cell">Date</th>
                <th>Status</th>
                <th class="d-none d-lg-table-cell">Attachment</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody id="noticeTbody">
              <!-- rows injected by JS -->
            </tbody>
          </table>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-3">
          <div class="small text-muted"><span id="countLive">0</span> live • <span id="countDraft">0</span> draft • <span id="countArch">0</span> archived</div>
          <nav>
            <ul class="pagination pagination-sm mb-0" id="pager">
              <!-- pager injected -->
            </ul>
          </nav>
        </div>
      </div>

      <!-- Courses (placeholder) -->
      <div id="panel-courses" class="card-soft p-4 mt-3 d-none">
        <h2 class="h5 fw-bold">Courses</h2>
        <p class="text-muted mb-0">This is a placeholder for course management. You can extend the same CRUD pattern used in Notice Board.</p>
      </div>

      <!-- Settings -->
      <div id="panel-settings" class="card-soft p-4 mt-3 d-none">
        <h2 class="h5 fw-bold">Settings</h2>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Organization Name</label>
            <input id="orgName" class="form-control" placeholder="NIELIT Chhattisgarh">
          </div>
          <div class="col-md-6">
            <label class="form-label">Support Email</label>
            <input id="supportEmail" class="form-control" placeholder="support@example.com">
          </div>
          <div class="col-12">
            <button class="btn btn-primary mt-2" id="saveSettings"><i class="fa-solid fa-floppy-disk me-1"></i> Save Settings</button>
          </div>
        </div>
      </div>

    </div>

    <footer class="footer text-center py-4 small">
      &copy; <span id="year"></span> NIELIT Chhattisgarh • Admin
    </footer>
  </section>

</div>

<!-- Add/Edit Notice Modal -->
<div class="modal fade" id="noticeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Add Notice</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="noticeForm">
        <div class="modal-body">
          <input type="hidden" id="noticeId">
          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label">Title</label>
              <input id="title" class="form-control" maxlength="160" required placeholder="e.g., Examination Timetable – Feb 2025">
            </div>
            <div class="col-md-4">
              <label class="form-label">Date</label>
              <input type="date" id="date" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea id="desc" class="form-control" rows="3" placeholder="Optional short description..."></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select id="status" class="form-select">
                <option value="live">Live</option>
                <option value="draft">Draft</option>
                <option value="archived">Archived</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Attachment URL (PDF)</label>
              <input id="attachment" type="url" class="form-control" placeholder="https://.../file.pdf">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Confirm Delete Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Notice</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete <span class="fw-semibold" id="delTitle"></span>? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn"><i class="fa-solid fa-trash me-1"></i> Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- Toasts -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="toast" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="toastBody">Saved</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/** ===== Storage Utilities (localStorage) ===== */
const KEYS = {
  notices: 'admin_notices_v1',
  settings: 'admin_settings_v1'
};
const getData = (key, fallback=[]) => JSON.parse(localStorage.getItem(key) || JSON.stringify(fallback));
const setData = (key, value) => localStorage.setItem(key, JSON.stringify(value));

function uid() {
  return Math.random().toString(36).slice(2) + Date.now().toString(36);
}

/** ===== UI State ===== */
let notices = getData(KEYS.notices);
let editingId = null;
let deleteId = null;
let currentPage = 1;
const pageSize = 7;

/** ===== DOM ===== */
const tbody = document.getElementById('noticeTbody');
const searchInput = document.getElementById('searchInput');
const countLive = document.getElementById('countLive');
const countDraft = document.getElementById('countDraft');
const countArch = document.getElementById('countArch');
const pager = document.getElementById('pager');
const toastEl = new bootstrap.Toast(document.getElementById('toast'));
const toastBody = document.getElementById('toastBody');

/** ===== Helpers ===== */
function statusBadge(s) {
  if (s === 'live') return '<span class="status-badge status-live">Live</span>';
  if (s === 'draft') return '<span class="status-badge status-draft">Draft</span>';
  return '<span class="status-badge status-arch">Archived</span>';
}

function filteredNotices() {
  const q = (searchInput.value || '').toLowerCase().trim();
  const arr = notices.filter(n =>
    n.title.toLowerCase().includes(q) ||
    (n.desc || '').toLowerCase().includes(q)
  );
  // sort by date desc, then title
  return arr.sort((a,b) => (b.date || '').localeCompare(a.date || '') || a.title.localeCompare(b.title));
}

function renderCounts() {
  countLive.textContent = notices.filter(n => n.status === 'live').length;
  countDraft.textContent = notices.filter(n => n.status === 'draft').length;
  countArch.textContent = notices.filter(n => n.status === 'archived').length;
}

function renderPager(total) {
  pager.innerHTML = '';
  const pages = Math.max(1, Math.ceil(total / pageSize));
  currentPage = Math.min(currentPage, pages);
  for (let i = 1; i <= pages; i++) {
    const li = document.createElement('li');
    li.className = 'page-item' + (i === currentPage ? ' active' : '');
    const a = document.createElement('a');
    a.className = 'page-link';
    a.href = '#';
    a.textContent = i;
    a.addEventListener('click', e => { e.preventDefault(); currentPage = i; render(); });
    li.appendChild(a);
    pager.appendChild(li);
  }
}

function render() {
  const arr = filteredNotices();
  renderPager(arr.length);
  const start = (currentPage - 1) * pageSize;
  const pageItems = arr.slice(start, start + pageSize);
  tbody.innerHTML = pageItems.map(n => `
    <tr>
      <td>
        <div class="fw-semibold">${n.title}</div>
        <div class="small text-muted d-lg-none">${n.date || ''}</div>
        ${n.desc ? `<div class="small text-muted">${n.desc}</div>` : ''}
      </td>
      <td class="d-none d-md-table-cell">${n.date || ''}</td>
      <td>${statusBadge(n.status)}</td>
      <td class="d-none d-lg-table-cell">
        ${n.attachment ? `<a href="${n.attachment}" class="text-decoration-none" target="_blank"><i class="fa-solid fa-file-arrow-down me-1"></i> File</a>` : '<span class="text-muted small">None</span>'}
      </td>
      <td class="text-end">
        <div class="btn-group">
          <button class="btn btn-sm btn-outline-primary" onclick="onEdit('${n.id}')"><i class="fa-regular fa-pen-to-square"></i></button>
          <button class="btn btn-sm btn-outline-danger" onclick="onAskDelete('${n.id}')"><i class="fa-regular fa-trash-can"></i></button>
        </div>
      </td>
    </tr>
  `).join('');
  renderCounts();
}

/** ===== CRUD ===== */
function onAdd() {
  editingId = null;
  document.getElementById('modalTitle').textContent = 'Add Notice';
  document.getElementById('noticeForm').reset();
  document.getElementById('noticeId').value = '';
  const today = new Date().toISOString().slice(0,10);
  document.getElementById('date').value = today;
  new bootstrap.Modal('#noticeModal').show();
}

function onEdit(id) {
  const n = notices.find(x => x.id === id);
  if (!n) return;
  editingId = id;
  document.getElementById('modalTitle').textContent = 'Edit Notice';
  document.getElementById('noticeId').value = n.id;
  document.getElementById('title').value = n.title;
  document.getElementById('date').value = n.date || '';
  document.getElementById('desc').value = n.desc || '';
  document.getElementById('status').value = n.status || 'draft';
  document.getElementById('attachment').value = n.attachment || '';
  new bootstrap.Modal('#noticeModal').show();
}

function onAskDelete(id) {
  deleteId = id;
  const n = notices.find(x => x.id === id);
  document.getElementById('delTitle').textContent = n ? n.title : '';
  new bootstrap.Modal('#confirmModal').show();
}

function doDelete() {
  if (!deleteId) return;
  notices = notices.filter(n => n.id !== deleteId);
  setData(KEYS.notices, notices);
  deleteId = null;
  render();
  notify('Notice deleted');
}

/** ===== Import/Export ===== */
function exportData() {
  const payload = {
    notices, 
    settings: getData(KEYS.settings, {})
  };
  const blob = new Blob([JSON.stringify(payload, null, 2)], {type: 'application/json'});
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url; a.download = 'nielit-admin-export.json';
  document.body.appendChild(a); a.click();
  a.remove(); URL.revokeObjectURL(url);
}

function importData(file) {
  const reader = new FileReader();
  reader.onload = () => {
    try {
      const data = JSON.parse(reader.result);
      if (data.notices) notices = data.notices;
      if (data.settings) setData(KEYS.settings, data.settings);
      setData(KEYS.notices, notices);
      render();
      notify('Data imported');
    } catch (e) {
      notify('Import failed (invalid JSON)');
    }
  };
  reader.readAsText(file);
}

/** ===== Notifications ===== */
function notify(msg) {
  toastBody.textContent = msg;
  toastEl.show();
}

/** ===== Settings ===== */
function loadSettings() {
  const s = getData(KEYS.settings, {});
  document.getElementById('orgName').value = s.orgName || 'NIELIT Chhattisgarh';
  document.getElementById('supportEmail').value = s.supportEmail || 'support@example.com';
}
function saveSettings() {
  const s = {
    orgName: document.getElementById('orgName').value.trim(),
    supportEmail: document.getElementById('supportEmail').value.trim()
  };
  setData(KEYS.settings, s);
  notify('Settings saved');
}

/** ===== Event Listeners ===== */
document.getElementById('year').textContent = new Date().getFullYear();

document.getElementById('addNoticeBtn').addEventListener('click', onAdd);
document.getElementById('noticeForm').addEventListener('submit', e => {
  e.preventDefault();
  const n = {
    id: editingId || uid(),
    title: document.getElementById('title').value.trim(),
    date: document.getElementById('date').value,
    desc: document.getElementById('desc').value.trim(),
    status: document.getElementById('status').value,
    attachment: document.getElementById('attachment').value.trim()
  };
  if (!n.title) return;
  if (editingId) {
    const idx = notices.findIndex(x => x.id === editingId);
    if (idx !== -1) notices[idx] = n;
    notify('Notice updated');
  } else {
    notices.unshift(n);
    notify('Notice added');
  }
  setData(KEYS.notices, notices);
  editingId = null;
  bootstrap.Modal.getInstance(document.getElementById('noticeModal')).hide();
  render();
});

document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
  bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
  doDelete();
});

searchInput.addEventListener('input', () => { currentPage = 1; render(); });

document.getElementById('exportBtn').addEventListener('click', exportData);
document.getElementById('importFile').addEventListener('change', e => {
  if (e.target.files?.length) importData(e.target.files[0]);
});

// sidebar toggle (mobile)
document.getElementById('menuBtn').addEventListener('click', () => {
  document.querySelector('.sidebar').classList.toggle('open');
});

// side navigation switching
document.querySelectorAll('#sideNav .nav-link').forEach(link => {
  link.addEventListener('click', e => {
    e.preventDefault();
    document.querySelectorAll('#sideNav .nav-link').forEach(a => a.classList.remove('active'));
    link.classList.add('active');
    const target = link.getAttribute('data-target');
    document.querySelectorAll('[id^="panel-"]').forEach(p => p.classList.add('d-none'));
    document.getElementById(target).classList.remove('d-none');
  });
});

// settings buttons
document.getElementById('saveSettings').addEventListener('click', saveSettings);

// First-time demo data
if (!notices || notices.length === 0) {
  notices = [
    { id: uid(), title: 'Admission Open for AI & Data Science (Batch 2025)', date: '2025-09-08', desc: 'Apply now for the upcoming batch.', status: 'live', attachment: '' },
    { id: uid(), title: 'Examination Timetable – February 2025', date: '2025-09-05', desc: 'Download the PDF for the full schedule.', status: 'live', attachment: '' },
    { id: uid(), title: 'Placement Drive with Infosys – Registrations Open', date: '2025-09-01', desc: 'Limited seats available for qualified candidates.', status: 'draft', attachment: '' },
    { id: uid(), title: 'Cyber Security Workshop – Limited Seats', date: '2025-08-28', desc: 'Hands-on lab with experts.', status: 'archived', attachment: '' },
  ];
  setData(KEYS.notices, notices);
}
loadSettings();
render();
</script>

</body>
</html>
