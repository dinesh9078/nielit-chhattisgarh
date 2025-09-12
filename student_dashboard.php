<?php
session_start();
if (empty($_SESSION['student_id'])) {
  header('Location: student_login.php');
  exit;
}

require_once 'db.php';

// Get student details
try {
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['student_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
    
    if (!$student) {
        session_destroy();
        header('Location: student_login.php?error=Invalid session');
        exit;
    }
} catch (Exception $e) {
    error_log("Student dashboard error: " . $e->getMessage());
    $student = ['name' => $_SESSION['student_name'], 'email' => $_SESSION['student_email']];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | NIELIT Chhattisgarh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .card { transition: transform 0.2s ease-in-out; }
        .card:hover { transform: translateY(-2px); }
    </style>
</head>
<body class="bg-gray-50">
    
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="index.php" class="text-xl font-bold text-blue-600 hover:text-blue-700 transition-colors">NIELIT Chhattisgarh</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, <strong><?php echo htmlspecialchars($student['name']); ?></strong></span>
                    <a href="student_logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Student Dashboard</h1>
            <p class="text-gray-600">Manage your courses, applications, and academic progress</p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Enrolled Courses</dt>
                                <dd class="text-lg font-medium text-gray-900">0</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Completed</dt>
                                <dd class="text-lg font-medium text-gray-900">0</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">In Progress</dt>
                                <dd class="text-lg font-medium text-gray-900">0</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Applications</dt>
                                <dd class="text-lg font-medium text-gray-900">0</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Quick Actions -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="course.php" class="card bg-blue-50 hover:bg-blue-100 p-4 rounded-lg border border-blue-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Apply for Course</h3>
                                    <p class="text-sm text-gray-600">Submit new course application</p>
                                </div>
                            </div>
                        </a>
                        
                        <div class="card bg-green-50 hover:bg-green-100 p-4 rounded-lg border border-green-200 cursor-pointer">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">View Results</h3>
                                    <p class="text-sm text-gray-600">Check exam results & certificates</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card bg-yellow-50 hover:bg-yellow-100 p-4 rounded-lg border border-yellow-200 cursor-pointer">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Download Resources</h3>
                                    <p class="text-sm text-gray-600">Access study materials & syllabus</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card bg-purple-50 hover:bg-purple-100 p-4 rounded-lg border border-purple-200 cursor-pointer">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Update Profile</h3>
                                    <p class="text-sm text-gray-600">Edit personal information</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- My Applications -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">My Applications</h2>
                    <div class="text-center py-6 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="mt-2">No applications found</p>
                        <a href="course.php" class="mt-2 inline-flex items-center text-blue-600 hover:text-blue-500">
                            Apply for a course
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                
                <!-- Profile Summary -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Profile Summary</h2>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Full Name</label>
                            <p class="text-gray-900"><?php echo htmlspecialchars($student['name']); ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-900"><?php echo htmlspecialchars($student['email'] ?? 'Not provided'); ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Phone</label>
                            <p class="text-gray-900"><?php echo htmlspecialchars($student['phone'] ?? 'Not provided'); ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Course</label>
                            <p class="text-gray-900"><?php echo htmlspecialchars($student['course'] ?? 'Not enrolled'); ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Member Since</label>
                            <p class="text-gray-900"><?php echo date('M Y', strtotime($student['created_at'] ?? 'now')); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Recent Notices -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Notices</h2>
                    <div class="space-y-3">
                        <?php
                        // Get recent notices
                        try {
                            $notices_result = $conn->query("SELECT title, date FROM notices WHERE status = 'live' ORDER BY date DESC LIMIT 3");
                            if ($notices_result && $notices_result->num_rows > 0) {
                                while ($notice = $notices_result->fetch_assoc()) {
                                    echo '<div class="border-l-4 border-blue-400 pl-4">';
                                    echo '<p class="text-sm font-medium text-gray-900">' . htmlspecialchars($notice['title']) . '</p>';
                                    echo '<p class="text-xs text-gray-500">' . date('M j, Y', strtotime($notice['date'])) . '</p>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p class="text-gray-500 text-sm">No recent notices</p>';
                            }
                        } catch (Exception $e) {
                            echo '<p class="text-gray-500 text-sm">Unable to load notices</p>';
                        }
                        ?>
                        <a href="index.php#notices" class="text-sm text-blue-600 hover:text-blue-500">View all notices →</a>
                    </div>
                </div>

                <!-- Contact Support -->
                <div class="bg-blue-50 shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-2">Need Help?</h2>
                    <p class="text-sm text-gray-600 mb-4">Contact our support team for assistance</p>
                    <div class="space-y-2 text-sm">
                        <p><strong>Email:</strong> support@nielit.gov.in</p>
                        <p><strong>Phone:</strong> +91 XXX-XXX-XXXX</p>
                        <p><strong>Hours:</strong> Mon-Fri, 9 AM - 6 PM</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
