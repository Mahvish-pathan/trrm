<?php
/**
 * Setup Script for Farmer Advisory System
 * This script helps with the initial setup and database creation
 */

$setup_complete = false;
$messages = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = $_POST['db_host'] ?? 'localhost';
    $db_user = $_POST['db_user'] ?? 'root';
    $db_pass = $_POST['db_pass'] ?? '';
    $db_name = $_POST['db_name'] ?? 'farmer_advisory';
    
    try {
        // Test database connection
        $conn = new mysqli($db_host, $db_user, $db_pass);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        // Create database
        $sql = "CREATE DATABASE IF NOT EXISTS `$db_name`";
        if ($conn->query($sql)) {
            $messages[] = "Database '$db_name' created successfully";
        }
        
        // Select database
        $conn->select_db($db_name);
        
        // Read and execute SQL file
        $sql_file = 'database/farmer_queries.sql';
        if (file_exists($sql_file)) {
            $sql_content = file_get_contents($sql_file);
            
            // Split SQL commands
            $sql_commands = explode(';', $sql_content);
            
            foreach ($sql_commands as $command) {
                $command = trim($command);
                if (!empty($command) && !preg_match('/^--/', $command)) {
                    if ($conn->query($command)) {
                        // Success
                    } else {
                        if (!empty($conn->error)) {
                            $errors[] = "SQL Error: " . $conn->error;
                        }
                    }
                }
            }
            
            if (empty($errors)) {
                $messages[] = "Database tables created successfully";
                $messages[] = "Sample data inserted successfully";
            }
        } else {
            $errors[] = "SQL file not found: $sql_file";
        }
        
        // Update database configuration file
        $config_content = "<?php
// Database configuration
\$servername = \"$db_host\";
\$username = \"$db_user\";
\$password = \"$db_pass\";
\$dbname = \"$db_name\";

// Create connection
\$conn = new mysqli(\$servername, \$username, \$password, \$dbname);

// Check connection
if (\$conn->connect_error) {
    die(\"Connection failed: \" . \$conn->connect_error);
}

// Set charset to utf8
\$conn->set_charset(\"utf8\");

// Function to sanitize input
function sanitizeInput(\$data) {
    global \$conn;
    \$data = trim(\$data);
    \$data = stripslashes(\$data);
    \$data = htmlspecialchars(\$data);
    return \$conn->real_escape_string(\$data);
}
?>";
        
        if (file_put_contents('php/db_connect.php', $config_content)) {
            $messages[] = "Database configuration updated successfully";
            $setup_complete = true;
        } else {
            $errors[] = "Failed to update database configuration file";
        }
        
        $conn->close();
        
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup - Farmer Advisory System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f5dc 0%, #d2b48c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .setup-container {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
        }
        
        .setup-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .setup-header h1 {
            color: #2d5016;
            margin-bottom: 0.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2d5016;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #f8f9fa;
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #4a7c59;
        }
        
        .btn {
            width: 100%;
            padding: 12px 24px;
            background: linear-gradient(135deg, #2d5016, #4a7c59);
            color: white;
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .message {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .success-container {
            text-align: center;
        }
        
        .success-container h2 {
            color: #28a745;
            margin-bottom: 1rem;
        }
        
        .success-links {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .success-links a {
            padding: 10px 20px;
            background: #2d5016;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: background 0.3s ease;
        }
        
        .success-links a:hover {
            background: #4a7c59;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <?php if ($setup_complete): ?>
            <div class="success-container">
                <h2>🎉 Setup Complete!</h2>
                <p>Your Farmer Advisory System has been set up successfully.</p>
                
                <?php foreach ($messages as $message): ?>
                    <div class="message success"><?php echo htmlspecialchars($message); ?></div>
                <?php endforeach; ?>
                
                <div class="success-links">
                    <a href="index.html">Visit Site</a>
                    <a href="admin.html">Admin Panel</a>
                </div>
            </div>
        <?php else: ?>
            <div class="setup-header">
                <h1>🌾 Farmer Advisory System</h1>
                <p>Setup Database Configuration</p>
            </div>
            
            <?php foreach ($messages as $message): ?>
                <div class="message success"><?php echo htmlspecialchars($message); ?></div>
            <?php endforeach; ?>
            
            <?php foreach ($errors as $error): ?>
                <div class="message error"><?php echo htmlspecialchars($error); ?></div>
            <?php endforeach; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="db_host">Database Host:</label>
                    <input type="text" id="db_host" name="db_host" value="localhost" required>
                </div>
                
                <div class="form-group">
                    <label for="db_user">Database Username:</label>
                    <input type="text" id="db_user" name="db_user" value="root" required>
                </div>
                
                <div class="form-group">
                    <label for="db_pass">Database Password:</label>
                    <input type="password" id="db_pass" name="db_pass" placeholder="Leave blank if no password">
                </div>
                
                <div class="form-group">
                    <label for="db_name">Database Name:</label>
                    <input type="text" id="db_name" name="db_name" value="farmer_advisory" required>
                </div>
                
                <button type="submit" class="btn">Setup Database</button>
            </form>
            
            <div style="margin-top: 2rem; padding: 1rem; background: #e9ecef; border-radius: 10px; font-size: 0.9rem;">
                <strong>Prerequisites:</strong>
                <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
                    <li>WAMP/XAMPP server running</li>
                    <li>MySQL service started</li>
                    <li>PHP 7.4+ installed</li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
