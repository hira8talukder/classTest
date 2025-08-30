<?php
require_once 'db_connect.php';

$stmt = $pdo->query("SELECT b.id, b.title, b.author, g.name as genre, b.is_available FROM books b LEFT JOIN genres g ON b.genre_id = g.id");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($books);
?>