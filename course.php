<?php
require_once 'db.php';

// Get active courses from database
try {
    $sql = "SELECT * FROM courses WHERE status = 'active' ORDER BY created_at DESC";
    $result = $conn->query($sql);
    $courses = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Calculate days remaining
            $days_remaining = 0;
            $days_text = '';
            if ($row['last_date']) {
                $today = new DateTime();
                $last_date = new DateTime($row['last_date']);
                $interval = $today->diff($last_date);
                $days_remaining = $interval->invert ? -$interval->days : $interval->days;
                
                if ($days_remaining > 0) {
                    $days_text = "($days_remaining days remaining)";
                } elseif ($days_remaining == 0) {
                    $days_text = "(Last day today!)";
                } else {
                    $days_text = "(Registration closed)";
                }
            }
            
            $row['days_remaining'] = $days_remaining;
            $row['days_text'] = $days_text;
            $row['formatted_last_date'] = $row['last_date'] ? date('jS F, Y', strtotime($row['last_date'])) : '';
            $courses[] = $row;
        }
    }
} catch (Exception $e) {
    error_log("Error fetching courses: " . $e->getMessage());
    $courses = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Courses | NIELIT Chhattisgarh</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    :root {
      --card-radius: 18px;
    }
    body { 
      font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, 'Helvetica Neue', Arial, sans-serif; 
      background: #f5f7fb; 
    }
    .page-title { font-weight: 800; letter-spacing: -0.02em; }
    .page-subtitle { color: #6b7280; }
    .course-card {
      border: 1px solid #e5e7eb;
      border-radius: var(--card-radius);
      box-shadow: 0 8px 24px rgba(16, 24, 40, 0.06);
      background: #fff;
      height: 100%;
      transition: transform .2s ease, box-shadow .2s ease;
    }
    .course-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 28px rgba(16, 24, 40, 0.10);
    }
    .badge-soft {
      background: #eafaf1;
      color: #0b7a45;
      border-radius: 9999px;
      padding: 6px 12px;
      font-weight: 600;
      font-size: .9rem;
      display: inline-block;
    }
    .badge-closed {
      background: #fef2f2;
      color: #dc2626;
    }
    .course-title {
      font-weight: 800;
      font-size: 1.25rem;
      line-height: 1.2;
    }
    .meta {
      color: #374151;
      margin: 0;
    }
    .meta small { color: #6b7280; }
    .apply-btn {
      border-radius: 12px;
      padding: .9rem 1rem;
      font-weight: 700;
      letter-spacing: .2px;
    }
    .chip-deadline {
      color: #111827;
    }
    .topbar {
      background: #0d6efd;
      color: #fff;
    }
    .topbar a { color: #fff; text-decoration: none; }
    .container-narrow { max-width: 1200px; }
    .course-description {
      color: #6b7280;
      font-size: 0.9rem;
      line-height: 1.5;
    }
    .admin-notice {
      background: #f0f9ff;
      border: 1px solid #0ea5e9;
      color: #0c4a6e;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 2rem;
    }
  </style>
</head>
<body>

  <!-- Simple top bar -->
  <header class="topbar py-3">
    <div class="container container-narrow d-flex align-items-center justify-content-between">
      <a href="index.php" class="fw-bold text-decoration-none">
        <i class="fa-solid fa-graduation-cap me-2"></i> NIELIT Chhattisgarh
      </a>
      <nav class="d-none d-md-flex gap-3">
        <a href="index.php#programs">Programs</a>
        <a href="course.php" class="fw-semibold">Courses</a>
        <a href="student_registration.php">Register</a>
      </nav>
    </div>
  </header>

  <main class="container container-narrow py-5">
    <div class="mb-4">
      <h1 class="page-title">Courses</h1>
      <p class="page-subtitle">Make sure you've submitted all your educational documents to be eligible.</p>
    </div>

    <?php if (empty($courses)): ?>
    <div class="admin-notice">
      <i class="fa-solid fa-info-circle me-2"></i>
      <strong>No courses available at the moment.</strong> Please check back later or contact the admin.
    </div>
    <?php endif; ?>

    <div class="row g-4">
      <?php foreach ($courses as $course): ?>
      <div class="col-12 col-md-6 col-lg-4">
        <div class="course-card p-4 d-flex flex-column">
          <h3 class="course-title"><?php echo htmlspecialchars($course['title']); ?></h3>
          
          <?php if ($course['description']): ?>
          <p class="course-description mt-2"><?php echo htmlspecialchars($course['description']); ?></p>
          <?php endif; ?>
          
          <div class="mt-2 mb-3">
            <span class="badge-soft <?php echo ($course['days_remaining'] < 0) ? 'badge-closed' : ''; ?>">
              <?php echo htmlspecialchars($course['enrollment_status']); ?>
            </span>
          </div>
          
          <p class="meta"><strong>Duration:</strong> <?php echo htmlspecialchars($course['duration']); ?></p>
          
          <?php if ($course['hours']): ?>
          <p class="meta"><strong>Hours:</strong> <?php echo htmlspecialchars($course['hours']); ?></p>
          <?php endif; ?>
          
          <?php if ($course['fees'] > 0): ?>
          <p class="meta"><strong>Fees:</strong> ₹<?php echo number_format($course['fees'], 2); ?></p>
          <?php endif; ?>
          
          <?php if ($course['last_date']): ?>
          <p class="meta chip-deadline">
            <strong>Last Date:</strong> <?php echo $course['formatted_last_date']; ?> 
            <small><?php echo $course['days_text']; ?></small>
          </p>
          <?php endif; ?>
          
          <?php if ($course['eligibility']): ?>
          <p class="meta"><strong>Eligibility:</strong> <?php echo htmlspecialchars($course['eligibility']); ?></p>
          <?php endif; ?>
          
          <div class="mt-auto pt-3">
            <?php if ($course['days_remaining'] >= 0): ?>
              <a href="Course_Registration.html?course=<?php echo urlencode($course['course_code'] ?: $course['title']); ?>" 
                 class="btn btn-primary w-100 apply-btn">Apply Course</a>
            <?php else: ?>
              <button class="btn btn-secondary w-100 apply-btn" disabled>Registration Closed</button>
            <?php endif; ?>
          </div>
          
          <?php if ($course['syllabus_url']): ?>
          <div class="mt-2">
            <a href="<?php echo htmlspecialchars($course['syllabus_url']); ?>" 
               class="btn btn-outline-primary btn-sm w-100" target="_blank">
              <i class="fa-solid fa-download me-1"></i> Download Syllabus
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if (!empty($courses)): ?>
    <div class="mt-5 p-4 bg-white rounded" style="border-radius: var(--card-radius);">
      <h4><i class="fa-solid fa-info-circle text-primary me-2"></i>Important Information</h4>
      <ul class="mb-0">
        <li>All course content is dynamically managed by the admin panel</li>
        <li>Registration deadlines are automatically calculated</li>
        <li>Course fees and eligibility criteria are updated in real-time</li>
        <li>For any queries, please contact our support team</li>
      </ul>
    </div>
    <?php endif; ?>
  </main>

  <footer class="py-4 text-center text-muted">
    <small>&copy; <span id="year"></span> NIELIT Chhattisgarh</small>
  </footer>

  <script>
    document.getElementById('year').textContent = new Date().getFullYear();
  </script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
