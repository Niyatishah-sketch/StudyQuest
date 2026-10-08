<?php

session_start();

require_once 'database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'error' => 'Unauthorized'
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT
        COUNT(*) as total_tasks,
        SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed_tasks,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_tasks,
        SUM(CASE WHEN task_date = CURDATE() THEN 1 ELSE 0 END) as today_tasks
    FROM tasks
    WHERE user_id = ?
");

$stmt->execute([$user_id]);

$stats = $stmt->fetch(PDO::FETCH_ASSOC);

$total = $stats['total_tasks'] ?: 0;
$completed = $stats['completed_tasks'] ?: 0;

$stats['progress'] = $total > 0
    ? round(($completed / $total) * 100)
    : 0;


/* Today's tasks */

$stmt = $conn->prepare("
    SELECT t.*, s.subject_name
    FROM tasks t
    JOIN subjects s ON t.subject_id = s.id
    WHERE t.user_id = ?
    AND t.task_date = CURDATE()
    ORDER BY t.start_time ASC
");

$stmt->execute([$user_id]);

$today_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* Calculate streak */

$stmt = $conn->prepare("
    SELECT DISTINCT task_date
    FROM tasks
    WHERE user_id = ?
    AND status = 'Completed'
    ORDER BY task_date DESC
");

$stmt->execute([$user_id]);

$dates = $stmt->fetchAll(PDO::FETCH_COLUMN);

$streak = 0;

$current_check = new DateTime();

foreach ($dates as $d) {

    $task_date = new DateTime($d);

    $diff = $current_check->diff($task_date)->days;

    if ($diff <= 1) {

        $streak++;

        $current_check = $task_date;

    } else {

        break;

    }
}


/* Daily goal */

$stmt = $conn->prepare("
    SELECT target_tasks
    FROM daily_goals
    WHERE user_id = ?
    AND goal_date = CURDATE()
");

$stmt->execute([$user_id]);

$goal = $stmt->fetch(PDO::FETCH_ASSOC);

$target_tasks = $goal
    ? $goal['target_tasks']
    : 4;


/* Completed tasks today */

$stmt = $conn->prepare("
    SELECT COUNT(*) as completed_today
    FROM tasks
    WHERE user_id = ?
    AND task_date = CURDATE()
    AND status = 'Completed'
");

$stmt->execute([$user_id]);

$completed_today = $stmt->fetch(PDO::FETCH_ASSOC)['completed_today'];


/* Send JSON response */

echo json_encode([

    'user_name' => $_SESSION['user_name'],

    'stats' => $stats,

    'today_tasks' => $today_tasks,

    'upcoming_tasks' => [],

    'streak' => $streak,

    'daily_goal' => [
        'target' => $target_tasks,
        'completed' => $completed_today
    ]

]);

?>