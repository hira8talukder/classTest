<?php
require_once 'db_connect.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Book ID is required']);
    exit;
}

$book_id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
$stmt->execute([$book_id]);

if ($stmt->rowCount() > 0) {
    http_response_code(200);
    echo json_encode(['message' => 'Book deleted successfully']);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Book not found']);
}
?>