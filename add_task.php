<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['task_name']) && !empty($data['subject_id'])) {
    $stmt = $conn->prepare(
        "INSERT INTO tasks (user_id, subject_id, task_name, task_date, start_time, end_time, priority, description) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->execute([
        $_SESSION['user_id'],
        $data['subject_id'],
        $data['task_name'],
        $data['task_date'],
        $data['start_time'] ?: null,
        $data['end_time'] ?: null,
        $data['priority'] ?? 'Medium',
        $data['description'] ?? ''
    ]);

    echo json_encode(['success' => true]);
}
?>