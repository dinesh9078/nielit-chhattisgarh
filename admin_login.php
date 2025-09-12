<?php
session_start();
if (isset($_SESSION["admin_id"])) {
  header("Location: admin_dashboard.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Login | NIELIT Chhattisgarh</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    :root { --radius: 22px; }
    html, body { height:100%; }
    body {
      font-family: 'Inter', sans-serif;
      background: radial-gradient(1200px 800px at 10% -10%, #dbeafe 0%, rgba(219,234,254,0) 60%),
                  radial-gradient(1000px 800px at 110% 110%, #e9d5ff 0%, rgba(233,213,255,0) 60%),
                  #f7f7fb;
    }
    .auth-wrapper { min-height: 100%; display: grid; place-items: center; padding: 40px; }
    .card-glass {
      backdrop-filter: blur(8px);
      background: rgba(255,255,255,0.85);
      border: 1px solid rgba(17,24,39,0.06);
      border-radius: var(--radius);
      box-shadow: 0 20px 60px rgba(16,24,40,.12);
    }
    .side-illustration { background: linear-gradient(135deg, #0d6efd 0%, #5b21b6 100%); color:#fff; }
    .form-control { border-radius: 12px; }
    .btn-modern { border-radius: 12px; padding: .9rem 1rem; font-weight: 700; }
  </style>
</head>
<body>

<div class="auth-wrapper">
  <div class="card-glass container p-0">
    <div class="row g-0">

      <!-- Left side -->
      <div class="col-lg-6 side-illustration d-none d-lg-flex align-items-center">
        <div class="p-5">
          <h2 class="fw-bold">Admin Console</h2>
          <p class="text-white-50">Secure access for administrators only.</p>
          <ul class="mb-0 text-white-50">
            <li>Role-based access</li>
            <li>Audit & activity logs</li>
            <li>Manage courses & applications</li>
          </ul>
        </div>
      </div>

      <!-- Right side: login form -->
      <div class="col-lg-6 bg-white">
        <div class="p-5">
          <a href="index.html" class="text-decoration-none mb-3 d-inline-block">
            <i class="fa-solid fa-arrow-left-long me-2"></i> Back to site
          </a>
          <h1 class="h3 fw-bold mb-3">Sign in (Admin)</h1>

          <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
          <?php endif; ?>

          <form action="login.php" method="POST">
            <div class="mb-3">
              <label for="adminEmail" class="form-label">Email</label>
              <input type="email" name="email" id="adminEmail" class="form-control" placeholder="admin@example.com" required>
            </div>
            <div class="mb-3">
              <label for="adminPassword" class="form-label">Password</label>
              <div class="input-group">
                <input type="password" name="password" id="adminPassword" class="form-control" placeholder="Your password" required minlength="8">
                <button class="btn btn-outline-secondary" type="button" id="togglePass">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Remember me</label>
              </div>
              <a href="#">Forgot password?</a>
            </div>
            <button class="btn btn-primary w-100 btn-modern" type="submit">
              <i class="fa-solid fa-right-to-bracket me-2"></i> Sign in
            </button>
            <div class="mt-4 text-muted small">
              Not an admin? <a href="student_login.html">Go to Student Login</a>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const toggleBtn = document.getElementById('togglePass');
  const passInput = document.getElementById('adminPassword');
  toggleBtn.addEventListener('click', () => {
    const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passInput.setAttribute('type', type);
    toggleBtn.innerHTML = type === 'password' ? '<i class="fa-regular fa-eye"></i>' : '<i class="fa-regular fa-eye-slash"></i>';
  });
</script>
</body>
</html>
