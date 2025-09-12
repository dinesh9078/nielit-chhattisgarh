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
    .status-archived { background:#eef2ff; color:#3730a3; }
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
      <a class="nav-link" href="#" data-target="panel-courses"><i class="fa-solid fa-book-open me-2"></i> Courses</a>
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
        <a class="btn btn-outline-secondary btn-sm" href="index.php" target="_blank"><i class="fa-regular fa-window-restore me-1"></i> View Site</a>
        <button class="btn btn-primary btn-sm" id="refreshBtn"><i class="fa-solid fa-arrows-rotate me-1"></i> Refresh</button>
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
              <tr><td colspan="5" class="text-center">Loading notices...</td></tr>
            </tbody>
          </table>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-3">
          <div class="small text-muted"><span id="countLive">0</span> live • <span id="countDraft">0</span> draft • <span id="countArch">0</span> archived</div>
        </div>
      </div>

      <!-- Courses Panel -->
      <div id="panel-courses" class="card-soft p-3 p-md-4 mt-3 d-none">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
          <div>
            <h2 class="h5 fw-bold mb-0">Course Management</h2>
            <div class="text-muted small">Manage courses displayed on the website dynamically.</div>
          </div>
          <div class="d-flex gap-2">
            <input id="courseSearchInput" class="form-control form-control-sm search-input" placeholder="Search courses...">
            <button class="btn btn-success btn-modern btn-sm" id="addCourseBtn"><i class="fa-solid fa-circle-plus me-1"></i> Add Course</button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Course Title</th>
                <th class="d-none d-md-table-cell">Duration</th>
                <th>Status</th>
                <th class="d-none d-lg-table-cell">Fees</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody id="courseTbody">
              <tr><td colspan="5" class="text-center">Loading courses...</td></tr>
            </tbody>
          </table>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-3">
          <div class="small text-muted">
            <span id="courseCountActive">0</span> active • 
            <span id="courseCountInactive">0</span> inactive • 
            <span id="courseCountDraft">0</span> draft
          </div>
          <a href="course.php" target="_blank" class="btn btn-outline-primary btn-sm">
            <i class="fa-regular fa-window-restore me-1"></i> View Course Page
          </a>
        </div>
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
              <textarea id="description" class="form-control" rows="3" placeholder="Optional short description..."></textarea>
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
              <input id="attachment_url" type="url" class="form-control" placeholder="https://.../file.pdf">
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

<!-- Add/Edit Course Modal -->
<div class="modal fade" id="courseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="courseModalTitle">Add Course</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="courseForm">
        <div class="modal-body">
          <input type="hidden" id="courseId">
          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label">Course Title *</label>
              <input id="courseTitle" class="form-control" required placeholder="e.g., Master of Computer Applications">
            </div>
            <div class="col-md-4">
              <label class="form-label">Course Code</label>
              <input id="courseCode" class="form-control" placeholder="e.g., MCA">
            </div>
            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea id="courseDescription" class="form-control" rows="3" placeholder="Course description and details..."></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Duration *</label>
              <input id="courseDuration" class="form-control" required placeholder="e.g., 2 Years">
            </div>
            <div class="col-md-6">
              <label class="form-label">Total Hours</label>
              <input id="courseHours" class="form-control" placeholder="e.g., 1800 Hours">
            </div>
            <div class="col-md-6">
              <label class="form-label">Fees (₹)</label>
              <input id="courseFees" type="number" step="0.01" class="form-control" placeholder="0.00">
            </div>
            <div class="col-md-6">
              <label class="form-label">Last Date for Application</label>
              <input id="courseLastDate" type="date" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select id="courseStatus" class="form-select">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="draft">Draft</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Enrollment Status</label>
              <input id="courseEnrollmentStatus" class="form-control" placeholder="e.g., Enrollment Ongoing">
            </div>
            <div class="col-12">
              <label class="form-label">Eligibility Criteria</label>
              <textarea id="courseEligibility" class="form-control" rows="2" placeholder="Educational qualifications required..."></textarea>
            </div>
            <div class="col-12">
              <label class="form-label">Syllabus URL</label>
              <input id="courseSyllabusUrl" type="url" class="form-control" placeholder="https://...">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Save Course</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Confirm Delete Course Modal -->
<div class="modal fade" id="confirmCourseDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Course</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete <span class="fw-semibold" id="delCourseTitle"></span>? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmCourseDeleteBtn"><i class="fa-solid fa-trash me-1"></i> Delete</button>
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
// Global variables
let notices = [];
let editingId = null;
let deleteId = null;

// DOM elements
const tbody = document.getElementById('noticeTbody');
const searchInput = document.getElementById('searchInput');
const countLive = document.getElementById('countLive');
const countDraft = document.getElementById('countDraft');
const countArch = document.getElementById('countArch');
const toastEl = new bootstrap.Toast(document.getElementById('toast'));
const toastBody = document.getElementById('toastBody');

// API Functions
async function apiRequest(url, options = {}) {
  try {
    const response = await fetch(url, {
      headers: {
        'Content-Type': 'application/json',
        ...options.headers
      },
      ...options
    });
    
    const data = await response.json();
    
    if (!response.ok) {
      throw new Error(data.error || 'API request failed');
    }
    
    return data;
  } catch (error) {
    console.error('API Error:', error);
    notify('Error: ' + error.message);
    throw error;
  }
}

async function loadNotices() {
  try {
    const data = await apiRequest('api/admin_notices.php');
    notices = data.notices || [];
    render();
  } catch (error) {
    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Failed to load notices. Please refresh the page.</td></tr>';
  }
}

async function saveNotice(notice) {
  const url = 'api/admin_notices.php';
  const method = notice.id ? 'PUT' : 'POST';
  
  return await apiRequest(url, {
    method: method,
    body: JSON.stringify(notice)
  });
}

async function deleteNotice(id) {
  return await apiRequest('api/admin_notices.php', {
    method: 'DELETE',
    body: JSON.stringify({ id: id })
  });
}

// Helper functions
function statusBadge(s) {
  if (s === 'live') return '<span class="status-badge status-live">Live</span>';
  if (s === 'draft') return '<span class="status-badge status-draft">Draft</span>';
  return '<span class="status-badge status-archived">Archived</span>';
}

function filteredNotices() {
  const q = (searchInput.value || '').toLowerCase().trim();
  const arr = notices.filter(n =>
    n.title.toLowerCase().includes(q) ||
    (n.description || '').toLowerCase().includes(q)
  );
  return arr.sort((a,b) => (b.date || '').localeCompare(a.date || '') || a.title.localeCompare(b.title));
}

function render() {
  const arr = filteredNotices();
  
  if (arr.length === 0) {
    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No notices found</td></tr>';
  } else {
    tbody.innerHTML = arr.map(n => `
      <tr>
        <td>
          <div class="fw-semibold">${n.title}</div>
          <div class="small text-muted d-lg-none">${n.date || ''}</div>
          ${n.description ? `<div class="small text-muted">${n.description}</div>` : ''}
        </td>
        <td class="d-none d-md-table-cell">${n.date || ''}</td>
        <td>${statusBadge(n.status)}</td>
        <td class="d-none d-lg-table-cell">
          ${n.attachment_url ? `<a href="${n.attachment_url}" class="text-decoration-none" target="_blank"><i class="fa-solid fa-file-arrow-down me-1"></i> File</a>` : '<span class="text-muted small">None</span>'}
        </td>
        <td class="text-end">
          <div class="btn-group">
            <button class="btn btn-sm btn-outline-primary" onclick="onEdit(${n.id})"><i class="fa-regular fa-pen-to-square"></i></button>
            <button class="btn btn-sm btn-outline-danger" onclick="onAskDelete(${n.id})"><i class="fa-regular fa-trash-can"></i></button>
          </div>
        </td>
      </tr>
    `).join('');
  }
  
  renderCounts();
}

function renderCounts() {
  countLive.textContent = notices.filter(n => n.status === 'live').length;
  countDraft.textContent = notices.filter(n => n.status === 'draft').length;
  countArch.textContent = notices.filter(n => n.status === 'archived').length;
}

function notify(msg) {
  toastBody.textContent = msg;
  toastEl.show();
}

// CRUD Functions
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
  const n = notices.find(x => x.id == id);
  if (!n) return;
  editingId = id;
  document.getElementById('modalTitle').textContent = 'Edit Notice';
  document.getElementById('noticeId').value = n.id;
  document.getElementById('title').value = n.title;
  document.getElementById('date').value = n.date || '';
  document.getElementById('description').value = n.description || '';
  document.getElementById('status').value = n.status || 'draft';
  document.getElementById('attachment_url').value = n.attachment_url || '';
  new bootstrap.Modal('#noticeModal').show();
}

function onAskDelete(id) {
  deleteId = id;
  const n = notices.find(x => x.id == id);
  document.getElementById('delTitle').textContent = n ? n.title : '';
  new bootstrap.Modal('#confirmModal').show();
}

async function doDelete() {
  if (!deleteId) return;
  
  try {
    await deleteNotice(deleteId);
    deleteId = null;
    loadNotices(); // Reload from database
    notify('Notice deleted successfully');
  } catch (error) {
    // Error already handled in apiRequest
  }
}

// Event Listeners
document.getElementById('year').textContent = new Date().getFullYear();

document.getElementById('addNoticeBtn').addEventListener('click', onAdd);
document.getElementById('refreshBtn').addEventListener('click', loadNotices);

document.getElementById('noticeForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const notice = {
    title: document.getElementById('title').value.trim(),
    date: document.getElementById('date').value,
    description: document.getElementById('description').value.trim(),
    status: document.getElementById('status').value,
    attachment_url: document.getElementById('attachment_url').value.trim()
  };
  
  if (!notice.title) {
    notify('Title is required');
    return;
  }
  
  if (editingId) {
    notice.id = editingId;
  }
  
  try {
    await saveNotice(notice);
    bootstrap.Modal.getInstance(document.getElementById('noticeModal')).hide();
    loadNotices(); // Reload from database
    notify(editingId ? 'Notice updated successfully' : 'Notice added successfully');
    editingId = null;
  } catch (error) {
    // Error already handled in apiRequest
  }
});

document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
  bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
  doDelete();
});

searchInput.addEventListener('input', () => {
  render();
});

// Sidebar toggle (mobile)
document.getElementById('menuBtn').addEventListener('click', () => {
  document.querySelector('.sidebar').classList.toggle('open');
});

// Side navigation switching
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

// Settings (placeholder)
document.getElementById('saveSettings').addEventListener('click', () => {
  notify('Settings saved (placeholder)');
});

// ===== COURSE MANAGEMENT ===== 
// Course variables
let courses = [];
let editingCourseId = null;
let deleteCourseId = null;

// Course DOM elements
const courseTbody = document.getElementById('courseTbody');
const courseSearchInput = document.getElementById('courseSearchInput');
const courseCountActive = document.getElementById('courseCountActive');
const courseCountInactive = document.getElementById('courseCountInactive');
const courseCountDraft = document.getElementById('courseCountDraft');

// Course API Functions
async function loadCourses() {
  try {
    const data = await apiRequest('api/admin_courses.php');
    courses = data.courses || [];
    renderCourses();
  } catch (error) {
    courseTbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Failed to load courses. Please refresh the page.</td></tr>';
  }
}

async function saveCourse(course) {
  const url = 'api/admin_courses.php';
  const method = course.id ? 'PUT' : 'POST';
  
  return await apiRequest(url, {
    method: method,
    body: JSON.stringify(course)
  });
}

async function deleteCourse(id) {
  return await apiRequest('api/admin_courses.php', {
    method: 'DELETE',
    body: JSON.stringify({ id: id })
  });
}

// Course Helper Functions
function courseStatusBadge(s) {
  if (s === 'active') return '<span class="status-badge status-live">Active</span>';
  if (s === 'inactive') return '<span class="status-badge status-archived">Inactive</span>';
  return '<span class="status-badge status-draft">Draft</span>';
}

function filteredCourses() {
  const q = (courseSearchInput.value || '').toLowerCase().trim();
  const arr = courses.filter(c =>
    c.title.toLowerCase().includes(q) ||
    (c.description || '').toLowerCase().includes(q) ||
    (c.course_code || '').toLowerCase().includes(q)
  );
  return arr.sort((a,b) => a.title.localeCompare(b.title));
}

function renderCourses() {
  const arr = filteredCourses();
  
  if (arr.length === 0) {
    courseTbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No courses found</td></tr>';
  } else {
    courseTbody.innerHTML = arr.map(c => `
      <tr>
        <td>
          <div class="fw-semibold">${c.title}</div>
          <div class="small text-muted d-lg-none">${c.duration || ''}</div>
          ${c.course_code ? `<div class="small text-muted">Code: ${c.course_code}</div>` : ''}
          ${c.description ? `<div class="small text-muted">${c.description.substring(0, 80)}${c.description.length > 80 ? '...' : ''}</div>` : ''}
        </td>
        <td class="d-none d-md-table-cell">${c.duration || ''}</td>
        <td>${courseStatusBadge(c.status)}</td>
        <td class="d-none d-lg-table-cell">
          ${c.fees > 0 ? `₹${parseFloat(c.fees).toLocaleString()}` : '<span class="text-muted small">Free</span>'}
        </td>
        <td class="text-end">
          <div class="btn-group">
            <button class="btn btn-sm btn-outline-primary" onclick="onEditCourse(${c.id})"><i class="fa-regular fa-pen-to-square"></i></button>
            <button class="btn btn-sm btn-outline-danger" onclick="onAskDeleteCourse(${c.id})"><i class="fa-regular fa-trash-can"></i></button>
          </div>
        </td>
      </tr>
    `).join('');
  }
  
  renderCourseCounts();
}

function renderCourseCounts() {
  courseCountActive.textContent = courses.filter(c => c.status === 'active').length;
  courseCountInactive.textContent = courses.filter(c => c.status === 'inactive').length;
  courseCountDraft.textContent = courses.filter(c => c.status === 'draft').length;
}

// Course CRUD Functions
function onAddCourse() {
  editingCourseId = null;
  document.getElementById('courseModalTitle').textContent = 'Add Course';
  document.getElementById('courseForm').reset();
  document.getElementById('courseId').value = '';
  document.getElementById('courseStatus').value = 'active';
  document.getElementById('courseEnrollmentStatus').value = 'Enrollment Ongoing';
  new bootstrap.Modal('#courseModal').show();
}

function onEditCourse(id) {
  const c = courses.find(x => x.id == id);
  if (!c) return;
  editingCourseId = id;
  document.getElementById('courseModalTitle').textContent = 'Edit Course';
  document.getElementById('courseId').value = c.id;
  document.getElementById('courseTitle').value = c.title;
  document.getElementById('courseCode').value = c.course_code || '';
  document.getElementById('courseDescription').value = c.description || '';
  document.getElementById('courseDuration').value = c.duration || '';
  document.getElementById('courseHours').value = c.hours || '';
  document.getElementById('courseFees').value = c.fees || '';
  document.getElementById('courseLastDate').value = c.last_date || '';
  document.getElementById('courseStatus').value = c.status || 'active';
  document.getElementById('courseEnrollmentStatus').value = c.enrollment_status || '';
  document.getElementById('courseEligibility').value = c.eligibility || '';
  document.getElementById('courseSyllabusUrl').value = c.syllabus_url || '';
  new bootstrap.Modal('#courseModal').show();
}

function onAskDeleteCourse(id) {
  deleteCourseId = id;
  const c = courses.find(x => x.id == id);
  document.getElementById('delCourseTitle').textContent = c ? c.title : '';
  new bootstrap.Modal('#confirmCourseDeleteModal').show();
}

async function doDeleteCourse() {
  if (!deleteCourseId) return;
  
  try {
    await deleteCourse(deleteCourseId);
    deleteCourseId = null;
    loadCourses(); // Reload from database
    notify('Course deleted successfully');
  } catch (error) {
    // Error already handled in apiRequest
  }
}

// Course Event Listeners
document.getElementById('addCourseBtn').addEventListener('click', onAddCourse);

document.getElementById('courseForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const course = {
    title: document.getElementById('courseTitle').value.trim(),
    course_code: document.getElementById('courseCode').value.trim(),
    description: document.getElementById('courseDescription').value.trim(),
    duration: document.getElementById('courseDuration').value.trim(),
    hours: document.getElementById('courseHours').value.trim(),
    fees: parseFloat(document.getElementById('courseFees').value) || 0,
    last_date: document.getElementById('courseLastDate').value || null,
    status: document.getElementById('courseStatus').value,
    enrollment_status: document.getElementById('courseEnrollmentStatus').value.trim(),
    eligibility: document.getElementById('courseEligibility').value.trim(),
    syllabus_url: document.getElementById('courseSyllabusUrl').value.trim()
  };
  
  if (!course.title || !course.duration) {
    notify('Title and duration are required');
    return;
  }
  
  if (editingCourseId) {
    course.id = editingCourseId;
  }
  
  try {
    await saveCourse(course);
    bootstrap.Modal.getInstance(document.getElementById('courseModal')).hide();
    loadCourses(); // Reload from database
    notify(editingCourseId ? 'Course updated successfully' : 'Course added successfully');
    editingCourseId = null;
  } catch (error) {
    // Error already handled in apiRequest
  }
});

document.getElementById('confirmCourseDeleteBtn').addEventListener('click', () => {
  bootstrap.Modal.getInstance(document.getElementById('confirmCourseDeleteModal')).hide();
  doDeleteCourse();
});

courseSearchInput.addEventListener('input', () => {
  renderCourses();
});

// Update refresh button to also load courses
document.getElementById('refreshBtn').addEventListener('click', () => {
  loadNotices();
  loadCourses();
});

// Initialize
loadNotices();
loadCourses();
</script>

</body>
</html>
