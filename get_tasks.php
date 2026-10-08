<?php

session_start();

require_once 'database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("
    SELECT t.*, s.subject_name
    FROM tasks t
    JOIN subjects s ON t.subject_id = s.id
    WHERE t.user_id = ?
    ORDER BY t.task_date ASC, t.start_time ASC
");

$stmt->execute([
    $_SESSION['user_id']
]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);

?>