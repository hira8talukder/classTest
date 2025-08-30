<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Book ID is required']);
        exit;
    }

    $book_id = $_GET['id'];

    // Check if book exists
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
    $book = $stmt->fetch();

    if (!$book) {
        http_response_code(404);
        echo json_encode(['message' => 'Book not found']);
        exit;
    }

    $title = $_POST['title'] ?? $book['title'];
    $author = $_POST['author'] ?? $book['author'];
    $is_available = $_POST['is_available'] ?? $book['is_available'];
    $genre_name = $_POST['genre'] ?? null;
    $genre_id = $book['genre_id'];

    if ($genre_name) {
        // Check if genre exists
        $stmt = $pdo->prepare("SELECT id FROM genres WHERE name = ?");
        $stmt->execute([$genre_name]);
        $genre = $stmt->fetch();

        if ($genre) {
            $genre_id = $genre['id'];
        } else {
            // Insert new genre
            $stmt = $pdo->prepare("INSERT INTO genres (name) VALUES (?)");
            $stmt->execute([$genre_name]);
            $genre_id = $pdo->lastInsertId();
        }
    }

    $sql = "UPDATE books SET title = ?, author = ?, is_available = ?, genre_id = ? WHERE id = ?";
    $params = [$title, $author, $is_available, $genre_id, $book_id];

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    http_response_code(200);
    echo json_encode(['message' => 'Book updated successfully']);
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed']);
}
?>