<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['subject_name'])) {
    $stmt = $conn->prepare(
        "INSERT INTO subjects (user_id, subject_name, description) VALUES (?, ?, ?)"
    );

    $stmt->execute([
        $_SESSION['user_id'],
        $data['subject_name'],
        $data['description'] ?? ''
    ]);

    echo json_encode(['success' => true]);
}
?>