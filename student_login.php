<?php
session_start();
if (isset($_SESSION["student_id"])) {
  header("Location: student_dashboard.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login | NIELIT Chhattisgarh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header Section -->
            <header class="bg-blue-700 text-white p-6 sm:p-8 text-center">
                <h1 class="text-2xl sm:text-3xl font-bold">NIELIT Chhattisgarh</h1>
                <p class="text-sm sm:text-base text-blue-200">Student Login Portal</p>
            </header>

            <!-- Login Form -->
            <form class="p-8 sm:p-10" method="POST" action="student_login_process.php">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">Welcome Back!</h2>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['success'])): ?>
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                        <?php echo htmlspecialchars($_GET['success']); ?>
                    </div>
                <?php endif; ?>
                
                <div class="mb-5">
                    <label for="emailOrPhone" class="block text-sm font-medium text-gray-700 mb-1">Email or Phone Number</label>
                    <input type="text" id="emailOrPhone" name="emailOrPhone" placeholder="Enter your email or phone" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <a href="#" class="text-sm text-blue-600 hover:underline">Forgot Password?</a>
                    </div>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Enter your password" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword()">
                            <svg id="eyeOpen" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eyeClosed" class="h-5 w-5 text-gray-400 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-300">
                        Login
                    </button>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account? 
                        <a href="student_registration.php" class="font-medium text-blue-600 hover:underline">Register</a>
                    </p>
                </div>

                <div class="mt-4 text-center">
                    <a href="index.php" class="text-sm text-gray-500 hover:text-gray-700">
                        ← Back to Homepage
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
