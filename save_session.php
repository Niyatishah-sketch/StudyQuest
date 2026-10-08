<?php

session_start();

require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['duration'])) {

    $stmt = $conn->prepare(
        "INSERT INTO study_sessions
        (user_id, duration, session_date)
        VALUES (?, ?, CURDATE())"
    );

    $stmt->execute([
        $_SESSION['user_id'],
        $data['duration']
    ]);

    echo json_encode([
        'success' => true
    ]);
}

?>