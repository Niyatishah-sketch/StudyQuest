<?php
require_once 'database.php';
session_start();
$error = '';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - StudyQuest</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
            background: linear-gradient(135deg, #8b5cf6, #06b6d4);
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

                    <i class="fas fa-graduation-cap text-cyan"></i>
                    StudyQuest

                </a>

                <p class="text-muted mt-2">
                    Welcome back! Please login
                </p>

            </div>

            <?php if (!empty($error)): ?>

                <div class="alert alert-danger py-2">
                    <?php echo $error; ?>
                </div>

            <?php endif; ?>

            <form method="POST" action="">

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

                <button type="submit" class="btn btn-gradient mt-3">
                    Login
                </button>

            </form>

            <div class="text-center mt-3">

                <p class="text-muted small">
                    Don't have an account?

                    <a href="register.php"
                       class="text-cyan text-decoration-none">
                        Register here
                    </a>
                </p>

            </div>

        </div>

    </div>

</body>
</html>