<?php
require_once '../config.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$question = trim($_POST['question']);
$answer = trim($_POST['answer']);
$sort_order = isset($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;
$is_active = isset($_POST['is_active']) ? intval($_POST['is_active']) : 1;

// Validation
if(empty($question)) {
    echo json_encode(['success' => false, 'message' => 'Question is required']);
    exit;
}

if(empty($answer)) {
    echo json_encode(['success' => false, 'message' => 'Answer is required']);
    exit;
}

try {
    if($id > 0) {
        // Update existing FAQ
        $sql = "UPDATE faqs SET question = ?, answer = ?, sort_order = ?, is_active = ?, date_updated = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiii", $question, $answer, $sort_order, $is_active, $id);
        
        if($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'FAQ updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update FAQ']);
        }
    } else {
        // Insert new FAQ
        $sql = "INSERT INTO faqs (question, answer, sort_order, is_active) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $question, $answer, $sort_order, $is_active);
        
        if($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'FAQ added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add FAQ']);
        }
    }
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
