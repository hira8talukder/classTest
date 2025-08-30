<?php
require_once 'db_connect.php';

if (!isset($_GET['genre']) || empty($_GET['genre'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Genre is required']);
    exit;
}

$genre_name = $_GET['genre'];

$stmt = $pdo->prepare("SELECT b.id, b.title, b.author, g.name as genre, b.is_available FROM books b INNER JOIN genres g ON b.genre_id = g.id WHERE g.name = ?");
$stmt->execute([$genre_name]);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($books);
?>