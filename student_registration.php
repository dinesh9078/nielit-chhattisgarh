<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration | NIELIT Chhattisgarh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-6 px-4">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                
                <!-- Header -->
                <header class="bg-blue-700 text-white p-6 text-center">
                    <h1 class="text-2xl font-bold">NIELIT Chhattisgarh</h1>
                    <p class="text-blue-200">Student Registration</p>
                </header>

                <!-- Registration Form -->
                <form class="p-6" method="POST" action="student_registration_process.php">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">Create Your Account</h2>
                    
                    <?php if (isset($_GET['error'])): ?>
                        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                            <?php echo htmlspecialchars($_GET['error']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" id="name" name="name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="email" name="email"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="course" class="block text-sm font-medium text-gray-700 mb-1">Course of Interest</label>
                            <select id="course" name="course" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select a course</option>
                                <option value="Python Programming">Python Programming</option>
                                <option value="AI/ML using Python">AI/ML using Python</option>
                                <option value="Cyber Security">Cyber Security</option>
                                <option value="Robotics">Robotics</option>
                                <option value="Drone Technology">Drone Technology</option>
                                <option value="CCC">Course on Computer Concepts (CCC)</option>
                            </select>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                            <input type="password" id="password" name="password" required minlength="6"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
                        </div>

                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                            <input type="password" id="confirm_password" name="confirm_password" required minlength="6"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="terms" name="terms" required 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="terms" class="ml-2 block text-sm text-gray-700">
                                I agree to the <a href="#" class="text-blue-600 hover:underline">Terms and Conditions</a>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-6 bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Create Account
                    </button>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Already have an account? 
                            <a href="student_login.php" class="font-medium text-blue-600 hover:underline">Login</a>
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
    </div>

    <script>
        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>
