<?php

session_start();

require_once 'database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("
    SELECT s.*,
        (SELECT COUNT(*) FROM tasks WHERE subject_id = s.id) as total_tasks,
        (SELECT COUNT(*) FROM tasks WHERE subject_id = s.id AND status = 'Completed') as completed_tasks
    FROM subjects s
    WHERE s.user_id = ?
");

$stmt->execute([
    $_SESSION['user_id']
]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);

?>