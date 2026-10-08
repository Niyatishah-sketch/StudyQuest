<?php

session_start();

require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $_SESSION['user_id'];

if (!empty($data['name'])) {

    $stmt = $conn->prepare(
        "UPDATE users SET name = ? WHERE id = ?"
    );

    $stmt->execute([
        $data['name'],
        $user_id
    ]);

    $_SESSION['user_name'] = $data['name'];
}

if (!empty($data['target_tasks'])) {

    $stmt = $conn->prepare(
        "INSERT INTO daily_goals
        (user_id, goal_date, target_tasks)
        VALUES (?, CURDATE(), ?)
        ON DUPLICATE KEY UPDATE target_tasks = ?"
    );

    $stmt->execute([
        $user_id,
        $data['target_tasks'],
        $data['target_tasks']
    ]);
}

echo json_encode([
    'success' => true
]);

?>