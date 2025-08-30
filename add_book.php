<?php
require_once 'db_connect.php';

$connect = mysqli_connect(HOST, USER, PASS, DB) or die("Can not connect");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? mysqli_real_escape_string($connect, $_POST['title']) : '';
    $author = isset($_POST['author']) ? mysqli_real_escape_string($connect, $_POST['author']) : '';
    $genre_name = isset($_POST['genre']) ? mysqli_real_escape_string($connect, $_POST['genre']) : '';
    $genre_id = null;

    if (!empty($title) && !empty($author)) {
        if (!empty($genre_name)) {
            // Check if genre exists
            $result = mysqli_query($connect, "SELECT id FROM genres WHERE name = '$genre_name'");
            if (mysqli_num_rows($result) > 0) {
                $genre = mysqli_fetch_assoc($result);
                $genre_id = $genre['id'];
            } else {
                // Insert new genre
                mysqli_query($connect, "INSERT INTO genres (name) VALUES ('$genre_name')");
                $genre_id = mysqli_insert_id($connect);
            }
        }

        // Insert book
        $insert_query = "INSERT INTO books (title, author, genre_id) VALUES ('$title', '$author', " . ($genre_id ? $genre_id : 'NULL') . ")";
        if (mysqli_query($connect, $insert_query)) {
            echo "Book added successfully!";
        } else {
            echo "Error adding book: " . mysqli_error($connect);
        }
    } else {
        echo "Title and Author are required fields.";
    }
}
?>