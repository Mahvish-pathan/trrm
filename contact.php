<?php
header('Content-Type: application/json');
include 'db_connect.php';

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get and sanitize input
$name = isset($_POST['name']) ? sanitizeInput($_POST['name']) : '';
$phone = isset($_POST['phone']) ? sanitizeInput($_POST['phone']) : '';
$email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
$subject = isset($_POST['subject']) ? sanitizeInput($_POST['subject']) : '';
$message = isset($_POST['message']) ? sanitizeInput($_POST['message']) : '';

// Validate required fields
if (empty($name) || empty($phone) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Name, phone, and message are required fields']);
    exit;
}

// Validate phone number (basic validation)
if (!preg_match('/^[0-9+\-\s()]{10,15}$/', $phone)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid phone number']);
    exit;
}

// Validate email if provided
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
    exit;
}

// Insert contact message into database
$sql = "INSERT INTO contact_messages (name, phone, email, subject, message, status, created_at) VALUES (?, ?, ?, ?, ?, 'new', NOW())";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sssss", $name, $phone, $email, $subject, $message);
    
    if ($stmt->execute()) {
        $message_id = $conn->insert_id;
        
        // Here you could add email notification logic
        // sendNotificationEmail($name, $email, $subject, $message);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Thank you for contacting us! We will get back to you within 24 hours.',
            'message_id' => $message_id
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error sending message: ' . $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$conn->close();

// Optional: Function to send email notification
function sendNotificationEmail($name, $email, $subject, $message) {
    // Configure your email settings here
    $to = "admin@krishiassistant.com";
    $email_subject = "New Contact Form Submission: " . $subject;
    $email_body = "Name: " . $name . "\n";
    $email_body .= "Email: " . $email . "\n";
    $email_body .= "Subject: " . $subject . "\n\n";
    $email_body .= "Message:\n" . $message;
    
    $headers = "From: noreply@krishiassistant.com\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    
    // Uncomment the line below to enable email sending
    // mail($to, $email_subject, $email_body, $headers);
}
?>
