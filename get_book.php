<?php
require_once 'db_connect.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Book ID is required']);
    exit;
}

$book_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT b.id, b.title, b.author, g.name as genre, b.is_available FROM books b LEFT JOIN genres g ON b.genre_id = g.id WHERE b.id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if ($book) {
    header('Content-Type: application/json');
    echo json_encode($book);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Book not found']);
}
?>