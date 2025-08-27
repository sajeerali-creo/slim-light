<?php
require_once '../config.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid FAQ ID']);
    exit;
}

try {
    $sql = "DELETE FROM faqs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'FAQ deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete FAQ']);
    }
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
