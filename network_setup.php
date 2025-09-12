<?php
echo "<h1>🌐 NIELIT Chhattisgarh - Network Setup Guide</h1>";
echo "<style>body{font-family:Arial,sans-serif;margin:2rem;background:#f5f5f5;} .card{background:white;padding:1.5rem;margin:1rem 0;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);} .success{color:green;font-weight:bold;} .error{color:red;font-weight:bold;} .info{color:blue;font-weight:bold;} .warning{color:orange;font-weight:bold;}</style>";

// Get server information
$server_ip = $_SERVER['SERVER_ADDR'] ?? 'Unknown';
$server_name = $_SERVER['SERVER_NAME'] ?? 'localhost';
$server_port = $_SERVER['SERVER_PORT'] ?? '80';
$document_root = $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown';

echo "<div class='card'>";
echo "<h2>📊 Current Server Status</h2>";
echo "<div class='info'>✓ XAMPP is running and accessible!</div>";
echo "<p><strong>Server IP:</strong> $server_ip</p>";
echo "<p><strong>Server Name:</strong> $server_name</p>";
echo "<p><strong>Port:</strong> $server_port</p>";
echo "<p><strong>Document Root:</strong> $document_root</p>";
echo "<p><strong>Current URL:</strong> " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]</p>";
echo "</div>";

echo "<div class='card'>";
echo "<h2>🏠 Local Access URLs</h2>";
echo "<p>Use these URLs on <strong>this computer</strong>:</p>";
echo "<ul>";
echo "<li><strong>Main Website:</strong> <a href='http://localhost/nielit-chhattisgarh/' target='_blank'>http://localhost/nielit-chhattisgarh/</a></li>";
echo "<li><strong>Admin Login:</strong> <a href='http://localhost/nielit-chhattisgarh/admin_login.php' target='_blank'>http://localhost/nielit-chhattisgarh/admin_login.php</a></li>";
echo "<li><strong>Student Login:</strong> <a href='http://localhost/nielit-chhattisgarh/student_login.php' target='_blank'>http://localhost/nielit-chhattisgarh/student_login.php</a></li>";
echo "</ul>";
echo "</div>";

// Get network IP
$network_ip = '192.168.1.102'; // From ipconfig output

echo "<div class='card'>";
echo "<h2>🌐 Network Access URLs</h2>";
echo "<p>Use these URLs from <strong>other devices</strong> on your network (phones, tablets, other computers):</p>";
echo "<div class='info'>Your Network IP Address: <strong>$network_ip</strong></div>";
echo "<ul>";
echo "<li><strong>Main Website:</strong> <a href='http://$network_ip/nielit-chhattisgarh/' target='_blank'>http://$network_ip/nielit-chhattisgarh/</a></li>";
echo "<li><strong>Admin Login:</strong> <a href='http://$network_ip/nielit-chhattisgarh/admin_login.php' target='_blank'>http://$network_ip/nielit-chhattisgarh/admin_login.php</a></li>";
echo "<li><strong>Student Login:</strong> <a href='http://$network_ip/nielit-chhattisgarh/student_login.php' target='_blank'>http://$network_ip/nielit-chhattisgarh/student_login.php</a></li>";
echo "</ul>";
echo "</div>";

echo "<div class='card'>";
echo "<h2>⚙️ Configuration Steps for Network Access</h2>";
echo "<h3>1. Configure Apache (httpd.conf)</h3>";
echo "<p>To allow access from other devices, you may need to modify Apache configuration:</p>";
echo "<p><strong>File Location:</strong> <code>C:\\xampp\\apache\\conf\\httpd.conf</code></p>";
echo "<p>Look for and modify these lines:</p>";
echo "<pre style='background:#f0f0f0;padding:10px;border-radius:4px;'>";
echo "# Change from:\n";
echo "Listen 127.0.0.1:80\n\n";
echo "# To:\n";
echo "Listen 80\n\n";
echo "# Or add both:\n";
echo "Listen 127.0.0.1:80\n";
echo "Listen $network_ip:80";
echo "</pre>";

echo "<h3>2. Windows Firewall</h3>";
echo "<p>Make sure Windows Firewall allows Apache/XAMPP:</p>";
echo "<ul>";
echo "<li>Open Windows Defender Firewall</li>";
echo "<li>Allow 'Apache HTTP Server' through firewall</li>";
echo "<li>Or temporarily disable firewall for testing</li>";
echo "</ul>";
echo "</div>";

echo "<div class='card'>";
echo "<h2>🔧 Quick Setup Commands</h2>";
echo "<p>Run these commands in Command Prompt <strong>as Administrator</strong>:</p>";
echo "<pre style='background:#2d2d2d;color:white;padding:10px;border-radius:4px;'>";
echo "# Allow Apache through Windows Firewall\n";
echo "netsh advfirewall firewall add rule name=\"Apache\" dir=in action=allow protocol=TCP localport=80\n\n";
echo "# Check if port 80 is open\n";
echo "netstat -an | findstr :80";
echo "</pre>";
echo "</div>";

echo "<div class='card'>";
echo "<h2>🧪 Test Network Connectivity</h2>";
echo "<ol>";
echo "<li><strong>From this computer:</strong> Click the local URLs above</li>";
echo "<li><strong>From another device:</strong> ";
echo "<ul>";
echo "<li>Connect to the same Wi-Fi network</li>";
echo "<li>Open browser and go to: <strong>http://$network_ip/nielit-chhattisgarh/</strong></li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>From mobile phone:</strong> ";
echo "<ul>";
echo "<li>Connect to same Wi-Fi</li>";
echo "<li>Scan this QR code or type the URL manually</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<div class='card'>";
echo "<h2>📱 Mobile QR Code</h2>";
echo "<p>Scan this QR code with your phone to access the website:</p>";
echo "<div style='text-align:center;'>";
echo "<img src='https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=http://$network_ip/nielit-chhattisgarh/' alt='QR Code' style='border:1px solid #ccc;'>";
echo "<br><small>QR Code for: http://$network_ip/nielit-chhattisgarh/</small>";
echo "</div>";
echo "</div>";

echo "<div class='card'>";
echo "<h2>🔐 Login Credentials</h2>";
echo "<div style='display:flex;gap:2rem;'>";
echo "<div>";
echo "<h4>👨‍💼 Admin Login:</h4>";
echo "<p><strong>Email:</strong> admin@nielit.gov.in</p>";
echo "<p><strong>Password:</strong> admin123</p>";
echo "</div>";
echo "<div>";
echo "<h4>👨‍🎓 Student Login (Test Accounts):</h4>";
echo "<p><strong>Phone:</strong> 9876543210 | <strong>Password:</strong> student123</p>";
echo "<p><strong>Phone:</strong> 9876543211 | <strong>Password:</strong> student123</p>";
echo "<p><strong>Email:</strong> john.doe@example.com | <strong>Password:</strong> student123</p>";
echo "</div>";
echo "</div>";
echo "</div>";

echo "<div class='card'>";
echo "<h2>🚨 Troubleshooting</h2>";
echo "<h4>If network access doesn't work:</h4>";
echo "<ol>";
echo "<li><strong>Restart XAMPP</strong> after making configuration changes</li>";
echo "<li><strong>Check Windows Firewall</strong> - temporarily disable for testing</li>";
echo "<li><strong>Verify IP Address</strong> - run <code>ipconfig</code> to confirm your IP</li>";
echo "<li><strong>Test from another device</strong> on the same network</li>";
echo "<li><strong>Check router settings</strong> - some routers block inter-device communication</li>";
echo "</ol>";

echo "<h4>Alternative: Use Ngrok for Internet Access</h4>";
echo "<p>For access from anywhere on the internet:</p>";
echo "<ul>";
echo "<li>Download Ngrok: <a href='https://ngrok.com/' target='_blank'>https://ngrok.com/</a></li>";
echo "<li>Run: <code>ngrok http 80</code></li>";
echo "<li>Use the provided public URL</li>";
echo "</ul>";
echo "</div>";

echo "<div style='text-align:center;margin:2rem;'>";
echo "<p><strong>Your NIELIT Chhattisgarh website is ready to go! 🚀</strong></p>";
echo "</div>";
?>
