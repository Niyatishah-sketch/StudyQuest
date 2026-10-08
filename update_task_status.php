<?php

session_start();

require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['id']) && isset($data['status'])) {

    $stmt = $conn->prepare(
        "UPDATE tasks
         SET status = ?
         WHERE id = ? AND user_id = ?"
    );

    $stmt->execute([
        $data['status'],
        $data['id'],
        $_SESSION['user_id']
    ]);

    echo json_encode([
        'success' => true
    ]);
}

?>