<?php

require_once 'database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {

        $error = "All fields are required.";

    } elseif ($password !== $confirm_password) {

        $error = "Passwords do not match.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {

            $error = "Email is already registered.";

        } else {

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $conn->prepare(
                "INSERT INTO users (name, email, password)
                 VALUES (?, ?, ?)"
            );

            if ($stmt->execute([
                $name,
                $email,
                $hashed_password
            ])) {

                $user_id = $conn->lastInsertId();

                $goal_stmt = $conn->prepare(
                    "INSERT INTO daily_goals
                    (user_id, goal_date, target_tasks)
                    VALUES (?, CURDATE(), 4)"
                );

                $goal_stmt->execute([$user_id]);

                $success = "Registration successful! Redirecting to login...";

                header("refresh:2;url=login.php");

            } else {

                $error = "Something went wrong. Please try again.";

            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Register - StudyQuest</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>

        body {
            background-color: #0f172a;
            color: #f8fafc;
            font-family: 'Segoe UI', sans-serif;
        }

        .auth-card {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
        }

        .btn-gradient {
            background: linear-gradient(
                135deg,
                #8b5cf6,
                #06b6d4
            );

            border: none;
            color: white;
            font-weight: 600;
            width: 100%;
            padding: 10px;
            border-radius: 50px;
        }

    </style>

</head>

<body>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <div class="col-md-5 auth-card p-5 shadow-lg">

            <div class="text-center mb-4">

                <a href="index.php"
                   class="text-decoration-none fs-3 fw-bold text-white">

                    <i class="fas fa-graduation-cap"></i>

                    StudyQuest

                </a>

                <p class="text-muted mt-2">
                    Create your student account
                </p>

            </div>


            <?php if (!empty($error)): ?>

                <div class="alert alert-danger py-2">

                    <?php echo htmlspecialchars($error); ?>

                </div>

            <?php endif; ?>


            <?php if (!empty($success)): ?>

                <div class="alert alert-success py-2">

                    <?php echo htmlspecialchars($success); ?>

                </div>

            <?php endif; ?>


            <form method="POST" action="">

                <div class="mb-3">

                    <label class="form-label">
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control bg-dark text-white border-secondary"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Email address
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control bg-dark text-white border-secondary"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control bg-dark text-white border-secondary"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        name="confirm_password"
                        class="form-control bg-dark text-white border-secondary"
                        required
                    >

                </div>


                <button type="submit"
                        class="btn btn-gradient mt-3">

                    Register

                </button>

            </form>


            <div class="text-center mt-3">

                <p class="text-muted small">

                    Already have an account?

                    <a href="login.php"
                       class="text-cyan text-decoration-none">

                        Login here

                    </a>

                </p>

            </div>

        </div>

    </div>

</body>

</html>