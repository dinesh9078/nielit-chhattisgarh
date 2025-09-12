<?php
session_start();

// Check if student is logged in
if (!isset($_SESSION['student_id']) || empty($_SESSION['student_id'])) {
    // User not logged in, redirect to login with course parameter
    $courseCode = $_GET['course'] ?? '';
    $redirectUrl = "Course_Registration.php?course=" . urlencode($courseCode);
    header("Location: student_login.php?redirect=" . urlencode($redirectUrl));
    exit;
}

// User is logged in, get student info
$studentName = $_SESSION['student_name'] ?? '';
$studentEmail = $_SESSION['student_email'] ?? '';
$courseCode = $_GET['course'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form | NIELIT Chhattisgarh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
        }
        .progress-step {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }
        .file-input-label {
            cursor: pointer;
            border: 2px dashed #cbd5e1;
            padding: 2rem 1rem;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }
        .file-input-label:hover {
            background-color: #f8fafc;
            border-color: #3b82f6;
        }
        /* Hide the default file input */
        input[type="file"] {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4 sm:p-6 lg:p-8 max-w-6xl">
        <div class="bg-white rounded-xl shadow-lg">
            <!-- Header Section -->
            <header class="bg-blue-700 text-white p-6 sm:p-8 rounded-t-xl">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold">NIELIT Chhattisgarh</h1>
                        <p class="text-sm sm:text-base text-blue-200">Application Form</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-blue-200">Welcome,</p>
                        <p class="font-semibold"><?php echo htmlspecialchars($studentName); ?></p>
                        <a href="student_logout.php" class="text-xs text-blue-200 hover:underline">Logout</a>
                    </div>
                </div>
                <div id="selected-course" class="mt-3 p-3 bg-blue-600 rounded-lg" style="display: none;">
                    <p class="text-sm text-blue-100">Applying for:</p>
                    <p id="course-name" class="font-semibold text-lg text-white"></p>
                </div>
            </header>

            <!-- Rest of the form content from Course_Registration.html -->
            <!-- Progress Bar -->
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <div class="progress-step flex-1 text-center" id="progress-1">
                        <div class="w-10 h-10 mx-auto bg-blue-600 text-white rounded-full text-lg flex items-center justify-center">1</div>
                        <p class="text-sm mt-2 font-medium text-gray-700">Basic Information</p>
                    </div>
                    <div class="flex-1 border-t-2 border-gray-300"></div>
                    <div class="progress-step flex-1 text-center" id="progress-2">
                        <div class="w-10 h-10 mx-auto bg-white border-2 border-gray-300 rounded-full text-lg flex items-center justify-center text-gray-500">2</div>
                        <p class="text-sm mt-2 font-medium text-gray-500">Academics</p>
                    </div>
                    <div class="flex-1 border-t-2 border-gray-300"></div>
                    <div class="progress-step flex-1 text-center" id="progress-3">
                        <div class="w-10 h-10 mx-auto bg-white border-2 border-gray-300 rounded-full text-lg flex items-center justify-center text-gray-500">3</div>
                        <p class="text-sm mt-2 font-medium text-gray-500">Add Address</p>
                    </div>
                     <div class="flex-1 border-t-2 border-gray-300"></div>
                    <div class="progress-step flex-1 text-center" id="progress-4">
                        <div class="w-10 h-10 mx-auto bg-white border-2 border-gray-300 rounded-full text-lg flex items-center justify-center text-gray-500">4</div>
                        <p class="text-sm mt-2 font-medium text-gray-500">Confirmation</p>
                    </div>
                </div>
            </div>

            <form id="multiStepForm" class="p-6 sm:p-8" enctype="multipart/form-data" onsubmit="submitForm(event)">
                <!-- Pre-fill some basic info from session -->
                <input type="hidden" id="studentName" value="<?php echo htmlspecialchars($studentName); ?>">
                <input type="hidden" id="studentEmail" value="<?php echo htmlspecialchars($studentEmail); ?>">
                
                <!-- Include the rest of the form content here -->
                <div class="text-center p-8">
                    <h3 class="text-xl font-semibold text-gray-800">Form Loading...</h3>
                    <p class="text-gray-600">Please wait while we load the registration form.</p>
                </div>
                
                <!-- Navigation Buttons -->
                <div class="mt-8 pt-5 border-t flex justify-between">
                    <button type="button" id="prev-btn" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 disabled:opacity-50" disabled>Previous</button>
                    <button type="button" id="next-btn" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700">Next</button>
                    <button type="submit" id="submit-btn" class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 hidden">Confirm & Submit</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Redirect to full form with session protection
        window.location.href = 'Course_Registration.html?course=<?php echo htmlspecialchars($courseCode); ?>';
    </script>
</body>
</html>
