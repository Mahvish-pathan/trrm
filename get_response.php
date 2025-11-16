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

// Validate input
if (empty($query_type) || empty($question)) {
    echo json_encode(['success' => false, 'message' => 'Query type and question are required']);
    exit;
}

// Search for exact match first
$sql = "SELECT answer FROM responses WHERE query_type = ? AND question = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $query_type, $question);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['success' => true, 'answer' => $row['answer']]);
} else {
    // If no exact match, search for similar questions using keywords
    $keywords = explode(' ', $question);
    $keyword_conditions = [];
    $params = [];
    $types = '';
    
    // Add query type condition
    $keyword_conditions[] = "query_type = ?";
    $params[] = $query_type;
    $types .= 's';
    
    // Add keyword conditions
    foreach ($keywords as $keyword) {
        if (strlen(trim($keyword)) > 3) { // Only consider words longer than 3 characters
            $keyword_conditions[] = "(question LIKE ? OR answer LIKE ?)";
            $params[] = '%' . $keyword . '%';
            $params[] = '%' . $keyword . '%';
            $types .= 'ss';
        }
    }
    
    if (count($keyword_conditions) > 1) {
        $sql = "SELECT answer, question FROM responses WHERE " . implode(' AND ', $keyword_conditions) . " LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode(['success' => true, 'answer' => $row['answer']]);
        } else {
            // If still no match, get a general response for the category
            $sql = "SELECT answer FROM responses WHERE query_type = ? ORDER BY RAND() LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $query_type);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $general_response = "I found some related information: " . $row['answer'] . "\n\nFor more specific guidance about your question, please contact our agricultural experts.";
                echo json_encode(['success' => true, 'answer' => $general_response]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No information found for this category']);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Please provide more specific details in your question']);
    }
}

// Optional: Log the query for future reference
$log_sql = "INSERT INTO query_logs (query_type, question, timestamp) VALUES (?, ?, NOW())";
$log_stmt = $conn->prepare($log_sql);
if ($log_stmt) {
    $log_stmt->bind_param("ss", $query_type, $question);
    $log_stmt->execute();
    $log_stmt->close();
}

$stmt->close();
$conn->close();
?>
