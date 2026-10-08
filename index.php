<?php

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>StudyQuest – Plan Smart. Study Better. Achieve More.</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>

        :root {
            --bg-color: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --text-color: #f8fafc;
            --accent-purple: #8b5cf6;
            --accent-cyan: #06b6d4;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .hero-section {
            padding: 100px 0;

            background:
                radial-gradient(
                    circle at top right,
                    rgba(139, 92, 246, 0.15),
                    transparent 50%
                ),

                radial-gradient(
                    circle at bottom left,
                    rgba(6, 182, 212, 0.15),
                    transparent 50%
                );
        }

        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.2);
        }

        .btn-gradient {
            background: linear-gradient(
                135deg,
                var(--accent-purple),
                var(--accent-cyan)
            );

            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 50px;
            transition: opacity 0.3s;
        }

        .btn-gradient:hover {
            opacity: 0.9;
            color: white;
        }

    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top"
         style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(10px);">

        <div class="container">

            <a class="navbar-brand fw-bold fs-3" href="#">

                <i class="fas fa-graduation-cap"></i>

                StudyQuest

            </a>

            <div class="ms-auto">

                <a href="login.php"
                   class="btn btn-outline-light rounded-pill px-4 me-2">

                    Login

                </a>

                <a href="register.php"
                   class="btn btn-gradient">

                    Get Started

                </a>

            </div>

        </div>

    </nav>


    <header class="hero-section text-center">

        <div class="container mt-5">

            <h1 class="display-3 fw-bold mb-4">

                Plan Smart. Study Better.<br>

                <span style="
                    background: linear-gradient(135deg, #8b5cf6, #06b6d4);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                ">

                    Achieve More.

                </span>

            </h1>

            <p class="lead text-muted mb-5 mx-auto"
               style="max-width: 600px;">

                A modern, feature-packed student productivity platform with
                built-in planners, analytics, study timers, and automated
                streak tracking.

            </p>

            <div class="d-flex justify-content-center gap-3">

                <a href="register.php"
                   class="btn btn-gradient btn-lg">

                    Start Free Today

                </a>

                <a href="login.php"
                   class="btn btn-outline-light btn-lg rounded-pill px-4">

                    Sign In

                </a>

            </div>

        </div>

    </header>


    <section class="container py-5">

        <div class="row g-4">

            <div class="col-md-4">

                <div class="glass-card p-4 h-100">

                    <div class="fs-1 text-purple mb-3">

                        <i class="fas fa-tasks"></i>

                    </div>

                    <h3 class="h5 fw-bold">

                        Smart Task Planner

                    </h3>

                    <p class="text-muted">

                        Organize study assignments by subjects, prioritize tasks,
                        and track pending vs completed milestones seamlessly.

                    </p>

                </div>

            </div>


            <div class="col-md-4">

                <div class="glass-card p-4 h-100">

                    <div class="fs-1 text-cyan mb-3">

                        <i class="fas fa-stopwatch"></i>

                    </div>

                    <h3 class="h5 fw-bold">

                        Focus Timer

                    </h3>

                    <p class="text-muted">

                        Boost concentration using the Pomodoro technique with
                        customizable study blocks and automated session logs.

                    </p>

                </div>

            </div>


            <div class="col-md-4">

                <div class="glass-card p-4 h-100">

                    <div class="fs-1 text-warning mb-3">

                        <i class="fas fa-chart-line"></i>

                    </div>

                    <h3 class="h5 fw-bold">

                        Analytics & Streaks

                    </h3>

                    <p class="text-muted">

                        Monitor your productivity graphs, maintain study streaks,
                        and unlock achievement badges automatically.

                    </p>

                </div>

            </div>

        </div>

    </section>


    <footer class="text-center py-4 text-muted border-top border-secondary border-opacity-25">

        <p>

            &copy; 2026 StudyQuest.
            Developed for BTech CSE Web Technology Project.

        </p>

    </footer>

</body>

</html>