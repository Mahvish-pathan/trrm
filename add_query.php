<?php
header('Content-Type: application/json');
include 'db_connect.php';

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get and sanitize input
$query_type = isset($_POST['query_type']) ? sanitizeInput($_POST['query_type']) : '';
$question = isset($_POST['question']) ? sanitizeInput($_POST['question']) : '';
$farmer_name = isset($_POST['farmer_name']) ? sanitizeInput($_POST['farmer_name']) : 'Anonymous';
$farmer_phone = isset($_POST['farmer_phone']) ? sanitizeInput($_POST['farmer_phone']) : '';

// Validate required input
if (empty($query_type) || empty($question)) {
    echo json_encode(['success' => false, 'message' => 'Query type and question are required']);
    exit;
}

// Insert query into database
$sql = "INSERT INTO farmer_queries (query_type, question, farmer_name, farmer_phone, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssss", $query_type, $question, $farmer_name, $farmer_phone);
    
    if ($stmt->execute()) {
        $query_id = $conn->insert_id;
        echo json_encode([
            'success' => true, 
            'message' => 'Your query has been submitted successfully. Our experts will review it and add it to our knowledge base.',
            'query_id' => $query_id
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error submitting query: ' . $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$conn->close();
?>
