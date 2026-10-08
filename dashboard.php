<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" ng-app="studyQuestApp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>StudyQuest — Student Study Planner</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>

        .text-muted {
            color: red !important;
        }

        :root {
            --bg-color: #0f172a;
            --sidebar-bg: #1e293b;
            --card-bg: rgba(30, 41, 59, 0.7);
            --text-color: #f8fafc;
            --accent-purple: #8b5cf6;
            --accent-cyan: #06b6d4;
        }

        body.light-mode {
            --bg-color: rgba(78, 165, 173);
            --sidebar-bg: #ffffff;
            --card-bg: #000000;
            --text-color: #000000;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            background: var(--sidebar-bg);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
            z-index: 1000;
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
        }

        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 1rem;
            color: #EE82EE !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .glass-card p,
        .glass-card small,
        .glass-card span {
            color: #e2e8f0 !important;
        }

        .nav-link {
            color: var(--text-color);
            border-radius: 0.5rem;
            margin-bottom: 5px;
            transition: 0.2s;
        }

        .nav-link:hover,
        .nav-link.active {
            background: linear-gradient(
                135deg,
                var(--accent-purple),
                var(--accent-cyan)
            );
            color: white !important;
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
            border-radius: 50px;
        }

        @media(max-width: 768px) {

            .sidebar {
                width: 70px;
            }

            .sidebar .brand-text,
            .sidebar .nav-link span {
                display: none;
            }

            .main-content {
                margin-left: 70px;
            }
        }

    </style>

</head>

<body ng-controller="MainController"
      ng-class="{'light-mode': isLightMode}">

    <!-- Sidebar Navigation -->

    <div class="sidebar d-flex flex-column p-3">

        <a href="#"
           class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none text-white">

            <i class="fas fa-graduation-cap fs-4 text-cyan me-2"></i>

            <span class="fs-5 fw-bold brand-text"
                  style="background: linear-gradient(135deg, #8b5cf6, #06b6d4);
                         -webkit-background-clip: text;
                         -webkit-text-fill-color: transparent;">
                StudyQuest
            </span>

        </a>

        <hr class="text-secondary">

        <ul class="nav nav-pills flex-column mb-auto">

            <li class="nav-item">
                <a href="#"
                   ng-click="currentTab = 'dashboard'"
                   class="nav-link"
                   ng-class="{'active': currentTab === 'dashboard'}">

                    <i class="fas fa-home me-2"></i>
                    <span>Dashboard</span>

                </a>
            </li>

            <li>
                <a href="#"
                   ng-click="currentTab = 'subjects'"
                   class="nav-link"
                   ng-class="{'active': currentTab === 'subjects'}">

                    <i class="fas fa-book me-2"></i>
                    <span>Subjects</span>

                </a>
            </li>

            <li>
                <a href="#"
                   ng-click="currentTab = 'planner'"
                   class="nav-link"
                   ng-class="{'active': currentTab === 'planner'}">

                    <i class="fas fa-calendar-alt me-2"></i>
                    <span>Study Planner</span>

                </a>
            </li>

            <li>
                <a href="#"
                   ng-click="currentTab = 'progress'"
                   class="nav-link"
                   ng-class="{'active': currentTab === 'progress'}">

                    <i class="fas fa-chart-pie me-2"></i>
                    <span>Progress</span>

                </a>
            </li>

            <li>
                <a href="#"
                   ng-click="currentTab = 'timer'"
                   class="nav-link"
                   ng-class="{'active': currentTab === 'timer'}">

                    <i class="fas fa-stopwatch me-2"></i>
                    <span>Study Timer</span>

                </a>
            </li>

            <li>
                <a href="#"
                   ng-click="currentTab = 'settings'"
                   class="nav-link"
                   ng-class="{'active': currentTab === 'settings'}">

                    <i class="fas fa-cog me-2"></i>
                    <span>Settings</span>

                </a>
            </li>

        </ul>

        <hr class="text-secondary">

        <div class="dropdown">

            <a href="logout.php"
               class="d-flex align-items-center text-decoration-none text-danger">

                <i class="fas fa-sign-out-alt me-2"></i>
                <span>Logout</span>

            </a>

        </div>

    </div>


    <!-- Main Content Area -->

    <div class="main-content">

        <!-- DASHBOARD TAB -->

        <div ng-if="currentTab === 'dashboard'">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h2 class="fw-bold">
                        Good Morning, {{dashboardData.user_name}} 👋
                    </h2>

                    <p class="text-muted mb-0">
                        Today is {{todayDate | date:'fullDate'}}
                    </p>

                </div>

                <div class="d-flex align-items-center gap-3">

                    <span class="badge bg-purple p-2 rounded-pill">

                        <i class="fas fa-fire text-warning"></i>

                        {{dashboardData.streak}} Day Streak

                    </span>

                    <button class="btn btn-outline-secondary rounded-circle"
                            ng-click="toggleTheme()">

                        <i class="fas"
                           ng-class="isLightMode ? 'fa-moon' : 'fa-sun'"></i>

                    </button>

                </div>

            </div>


            <!-- Stats Cards -->

            <div class="row g-4 mb-4">

                <div class="col-md-3">

                    <div class="glass-card p-3">

                        <p class="text-muted mb-1">
                            Total Tasks
                        </p>

                        <h3 class="fw-bold">
                            {{dashboardData.stats.total_tasks || 0}}
                        </h3>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="glass-card p-3">

                        <p class="text-muted mb-1">
                            Completed Tasks
                        </p>

                        <h3 class="fw-bold text-success">
                            {{dashboardData.stats.completed_tasks || 0}}
                        </h3>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="glass-card p-3">

                        <p class="text-muted mb-1">
                            Pending Tasks
                        </p>

                        <h3 class="fw-bold text-warning">
                            {{dashboardData.stats.pending_tasks || 0}}
                        </h3>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="glass-card p-3">

                        <p class="text-muted mb-1">
                            Overall Progress
                        </p>

                        <h3 class="fw-bold text-cyan">
                            {{dashboardData.stats.progress || 0}}%
                        </h3>

                    </div>

                </div>

            </div>


            <!-- Today's Tasks & Daily Goal -->

            <div class="row g-4">

                <div class="col-lg-8">

                    <div class="glass-card p-4">

                        <h4 class="fw-bold mb-3">

                            <i class="fas fa-tasks text-purple me-2"></i>

                            Today's Study Plan

                        </h4>

                        <div ng-if="dashboardData.today_tasks.length === 0"
                             class="text-muted py-3">

                            No tasks scheduled for today.
                            Add some in Study Planner!

                        </div>


                        <div class="list-group list-group-flush bg-transparent">

                            <div ng-repeat="task in dashboardData.today_tasks"
                                 class="list-group-item bg-transparent text-white border-secondary d-flex justify-content-between align-items-center">

                                <div>

                                    <h6 class="mb-1"
                                        ng-class="{'text-decoration-line-through text-muted': task.status === 'Completed'}">

                                        {{task.task_name}}

                                    </h6>

                                    <small class="text-muted">

                                        <i class="fas fa-book me-1"></i>
                                        {{task.subject_name}}

                                        |

                                        <i class="fas fa-clock me-1"></i>
                                        {{task.start_time}}

                                    </small>

                                </div>


                                <button class="btn btn-sm"
                                        ng-class="task.status === 'Completed'
                                        ? 'btn-success'
                                        : 'btn-outline-success'"
                                        ng-click="toggleTaskStatus(task)">

                                    <i class="fas"
                                       ng-class="task.status === 'Completed'
                                       ? 'fa-check-circle'
                                       : 'fa-circle'"></i>

                                    {{task.status}}

                                </button>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-lg-4">

                    <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">

                        <div>

                            <h4 class="fw-bold mb-3">

                                <i class="fas fa-bullseye text-cyan me-2"></i>

                                Daily Goal

                            </h4>

                            <p class="text-muted">

                                Target:
                                {{dashboardData.daily_goal.target}}
                                tasks per day

                            </p>

                            <div class="progress mb-3 bg-dark"
                                 style="height: 20px;">

                                <div class="progress-bar bg-gradient"
                                     role="progressbar"
                                     ng-style="{
                                         'width':
                                         (dashboardData.daily_goal.completed /
                                         dashboardData.daily_goal.target * 100)
                                         + '%'
                                     }">

                                </div>

                            </div>

                            <p class="fw-semibold">

                                {{dashboardData.daily_goal.completed}}
                                /
                                {{dashboardData.daily_goal.target}}
                                Tasks Completed

                            </p>

                        </div>


                        <div class="alert alert-purple bg-opacity-25 border border-purple text-light mt-3 p-3 rounded-3">

                            <small>

                                <i class="fas fa-quote-left me-1"></i>

                                Small progress every day leads to big results.

                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- SUBJECTS TAB -->

        <div ng-if="currentTab === 'subjects'">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h2 class="fw-bold">
                    Subjects Management
                </h2>

                <button class="btn btn-gradient"
                        data-bs-toggle="modal"
                        data-bs-target="#addSubjectModal">

                    <i class="fas fa-plus me-1"></i>
                    Add Subject

                </button>

            </div>


            <div class="row g-4">

                <div class="col-md-4"
                     ng-repeat="subject in subjects">

                    <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">

                        <div>

                            <div class="d-flex justify-content-between align-items-start mb-2">

                                <h4 class="fw-bold text-cyan">
                                    {{subject.subject_name}}
                                </h4>

                                <button class="btn btn-sm btn-outline-danger"
                                        ng-click="deleteSubject(subject.id)">

                                    <i class="fas fa-trash"></i>

                                </button>

                            </div>

                            <p class="text-muted small">

                                {{subject.description ||
                                'No description provided.'}}

                            </p>

                        </div>


                        <div>

                            <div class="d-flex justify-content-between small text-muted mb-1">

                                <span>Progress</span>

                                <span>
                                    {{subject.completed_tasks}}
                                    /
                                    {{subject.total_tasks}}
                                    tasks
                                </span>

                            </div>

                            <div class="progress bg-dark"
                                 style="height: 8px;">

                                <div class="progress-bar bg-success"
                                     ng-style="{
                                         'width':
                                         (subject.total_tasks > 0
                                         ? (subject.completed_tasks /
                                         subject.total_tasks * 100)
                                         : 0) + '%'
                                     }">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- STUDY PLANNER TAB -->

        <div ng-if="currentTab === 'planner'">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h2 class="fw-bold">
                    Study Planner & Tasks
                </h2>

                <button class="btn btn-gradient"
                        data-bs-toggle="modal"
                        data-bs-target="#addTaskModal">

                    <i class="fas fa-plus me-1"></i>
                    Add New Task

                </button>

            </div>


            <div class="glass-card p-4">

                <div class="table-responsive">

                    <table class="table table-dark table-hover align-middle bg-transparent">

                        <thead>

                            <tr>

                                <th>Task Name</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Actions</th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr ng-repeat="task in tasks">

                                <td class="fw-semibold">
                                    {{task.task_name}}
                                </td>

                                <td>

                                    <span class="badge bg-secondary">
                                        {{task.subject_name}}
                                    </span>

                                </td>

                                <td>
                                    {{task.task_date}}
                                </td>

                                <td>
                                    {{task.start_time}} -
                                    {{task.end_time}}
                                </td>

                                <td>

                                    <span class="badge"
                                          ng-class="{
                                              'bg-danger':
                                              task.priority === 'High',

                                              'bg-warning text-dark':
                                              task.priority === 'Medium',

                                              'bg-info':
                                              task.priority === 'Low'
                                          }">

                                        {{task.priority}}

                                    </span>

                                </td>

                                <td>

                                    <span class="badge"
                                          ng-class="task.status === 'Completed'
                                          ? 'bg-success'
                                          : 'bg-warning text-dark'">

                                        {{task.status}}

                                    </span>

                                </td>

                                <td>

                                    <button class="btn btn-sm btn-outline-success me-1"
                                            ng-click="toggleTaskStatus(task)">

                                        <i class="fas fa-check"></i>

                                    </button>

                                    <button class="btn btn-sm btn-outline-danger"
                                            ng-click="deleteTask(task.id)">

                                        <i class="fas fa-trash"></i>

                                    </button>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <!-- PROGRESS TAB -->

        <div ng-if="currentTab === 'progress'">

            <h2 class="fw-bold mb-4">
                Progress Analytics
            </h2>

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="glass-card p-4">

                        <h4 class="fw-bold mb-3">
                            Overall Completion Status
                        </h4>

                        <canvas id="progressDoughnut"></canvas>

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="glass-card p-4">

                        <h4 class="fw-bold mb-3">
                            Subject-wise Completion
                        </h4>

                        <canvas id="subjectBarChart"></canvas>

                    </div>

                </div>

            </div>

        </div>


        <!-- STUDY TIMER TAB -->

        <div ng-if="currentTab === 'timer'">

            <div class="row justify-content-center">

                <div class="col-md-6 text-center">

                    <div class="glass-card p-5">

                        <h2 class="fw-bold mb-3">

                            <i class="fas fa-stopwatch text-cyan"></i>

                            Focus Timer

                        </h2>

                        <h1 class="display-1 fw-bold my-4 font-monospace">

                            {{timerDisplay}}

                        </h1>


                        <div class="d-flex justify-content-center gap-3 mb-4">

                            <button class="btn btn-success btn-lg px-4"
                                    ng-click="startTimer()"
                                    ng-if="!timerRunning">

                                Start

                            </button>

                            <button class="btn btn-warning btn-lg px-4"
                                    ng-click="pauseTimer()"
                                    ng-if="timerRunning">

                                Pause

                            </button>

                            <button class="btn btn-outline-light btn-lg px-4"
                                    ng-click="resetTimer()">

                                Reset

                            </button>

                        </div>


                        <div class="btn-group">

                            <button class="btn btn-outline-secondary"
                                    ng-click="setTimerDuration(25)">

                                25m Study

                            </button>

                            <button class="btn btn-outline-secondary"
                                    ng-click="setTimerDuration(45)">

                                45m Study

                            </button>

                            <button class="btn btn-outline-secondary"
                                    ng-click="setTimerDuration(60)">

                                60m Study

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- SETTINGS TAB -->

        <div ng-if="currentTab === 'settings'">

            <div class="row justify-content-center">

                <div class="col-md-6">

                    <div class="glass-card p-4">

                        <h2 class="fw-bold mb-4">
                            Account Settings
                        </h2>

                        <form ng-submit="updateSettings()">

                            <div class="mb-3">

                                <label class="form-label">
                                    Full Name
                                </label>

                                <input type="text"
                                       ng-model="settingsForm.name"
                                       class="form-control bg-dark text-white border-secondary"
                                       required>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Daily Task Goal Target
                                </label>

                                <input type="number"
                                       ng-model="settingsForm.target_tasks"
                                       class="form-control bg-dark text-white border-secondary"
                                       min="1"
                                       max="20"
                                       required>

                            </div>


                            <button type="submit"
                                    class="btn btn-gradient">

                                Save Changes

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ADD SUBJECT MODAL -->

    <div class="modal fade"
         id="addSubjectModal"
         tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content bg-dark text-white border-secondary">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Add New Subject
                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <form ng-submit="addSubject()">

                        <div class="mb-3">

                            <label class="form-label">
                                Subject Name
                            </label>

                            <input type="text"
                                   ng-model="newSubject.subject_name"
                                   class="form-control bg-secondary text-white border-0"
                                   required>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea ng-model="newSubject.description"
                                      class="form-control bg-secondary text-white border-0">
                            </textarea>

                        </div>


                        <button type="submit"
                                class="btn btn-gradient w-100"
                                data-bs-dismiss="modal">

                            Save Subject

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>


    <!-- ADD TASK MODAL -->

    <div class="modal fade"
         id="addTaskModal"
         tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content bg-dark text-white border-secondary">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Add Study Task
                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <form ng-submit="addTask()">

                        <div class="mb-3">

                            <label class="form-label">
                                Task Name
                            </label>

                            <input type="text"
                                   ng-model="newTask.task_name"
                                   class="form-control bg-secondary text-white border-0"
                                   required>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Subject
                            </label>

                            <select ng-model="newTask.subject_id"
                                    class="form-control bg-secondary text-white border-0"
                                    required>

                                <option value="">
                                    Select Subject
                                </option>

                                <option ng-repeat="s in subjects"
                                        value="{{s.id}}">

                                    {{s.subject_name}}

                                </option>

                            </select>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Date
                            </label>

                            <input type="date"
                                   ng-model="newTask.task_date"
                                   class="form-control bg-secondary text-white border-0"
                                   required>

                        </div>


                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Start Time
                                </label>

                                <input type="time"
                                       ng-model="newTask.start_time"
                                       class="form-control bg-secondary text-white border-0">

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    End Time
                                </label>

                                <input type="time"
                                       ng-model="newTask.end_time"
                                       class="form-control bg-secondary text-white border-0">

                            </div>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Priority
                            </label>

                            <select ng-model="newTask.priority"
                                    class="form-control bg-secondary text-white border-0">

                                <option value="Low">
                                    Low
                                </option>

                                <option value="Medium" selected>
                                    Medium
                                </option>

                                <option value="High">
                                    High
                                </option>

                            </select>

                        </div>


                        <button type="submit"
                                class="btn btn-gradient w-100"
                                data-bs-dismiss="modal">

                            Add Task

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <!-- ANGULARJS -->

    <script>

        angular.module('studyQuestApp', [])

        .controller(
            'MainController',
            function($scope, $http, $timeout) {

                $scope.currentTab = 'dashboard';

                $scope.todayDate = new Date();

                $scope.isLightMode =
                    localStorage.getItem('studyquest_theme') === 'light';


                let doughnutChart = null;

                let barChart = null;


                /* =========================
                   THEME
                ========================= */

                $scope.toggleTheme = function() {

                    $scope.isLightMode =
                        !$scope.isLightMode;

                    localStorage.setItem(
                        'studyquest_theme',
                        $scope.isLightMode
                            ? 'light'
                            : 'dark'
                    );

                };


                /* =========================
                   LOAD DASHBOARD
                ========================= */

                $scope.loadDashboard = function() {

                    $http.get('get_dashboard.php')

                        .then(function(res) {

                            $scope.dashboardData =
                                res.data;

                            $scope.settingsForm = {

                                name:
                                    res.data.user_name,

                                target_tasks:
                                    res.data.daily_goal.target

                            };

                        });

                };


                /* =========================
                   LOAD SUBJECTS
                ========================= */

                $scope.loadSubjects = function() {

                    $http.get('get_subjects.php')

                        .then(function(res) {

                            $scope.subjects =
                                res.data;

                        });

                };


                /* =========================
                   LOAD TASKS
                ========================= */

                $scope.loadTasks = function() {

                    $http.get('get_tasks.php')

                        .then(function(res) {

                            $scope.tasks =
                                res.data;

                        });

                };


                /* =========================
                   INITIAL LOAD
                ========================= */

                $scope.loadDashboard();

                $scope.loadSubjects();

                $scope.loadTasks();


                /* =========================
                   PROGRESS CHARTS
                ========================= */

                $scope.$watch(
                    'currentTab',
                    function(newVal) {

                        if (newVal === 'progress') {

                            $timeout(
                                function() {

                                    $http.get(
                                        'get_subjects.php'
                                    )

                                    .then(function(res) {

                                        let subjectsData =
                                            res.data || [];


                                        let labels =
                                            subjectsData.map(
                                                function(d) {
                                                    return d.subject_name;
                                                }
                                            );


                                        let totals =
                                            subjectsData.map(
                                                function(d) {
                                                    return parseInt(
                                                        d.total_tasks
                                                    ) || 0;
                                                }
                                            );


                                        let completed =
                                            subjectsData.map(
                                                function(d) {
                                                    return parseInt(
                                                        d.completed_tasks
                                                    ) || 0;
                                                }
                                            );


                                        let totalCompleted =
                                            completed.reduce(
                                                function(a, b) {
                                                    return a + b;
                                                },
                                                0
                                            );


                                        let totalTasksCount =
                                            totals.reduce(
                                                function(a, b) {
                                                    return a + b;
                                                },
                                                0
                                            );


                                        let totalPending =
                                            totalTasksCount -
                                            totalCompleted;


                                        if (doughnutChart) {
                                            doughnutChart.destroy();
                                        }


                                        if (barChart) {
                                            barChart.destroy();
                                        }


                                        /* DOUGHNUT CHART */

                                        let ctx1 =
                                            document.getElementById(
                                                'progressDoughnut'
                                            );


                                        if (ctx1) {

                                            doughnutChart =
                                                new Chart(
                                                    ctx1.getContext('2d'),
                                                    {

                                                        type: 'doughnut',

                                                        data: {

                                                            labels: [
                                                                'Completed',
                                                                'Pending'
                                                            ],

                                                            datasets: [{

                                                                data: [

                                                                    totalCompleted,

                                                                    totalPending > 0
                                                                        ? totalPending
                                                                        : 0

                                                                ],

                                                                backgroundColor: [
                                                                    '#10b981',
                                                                    '#f59e0b'
                                                                ]

                                                            }]

                                                        },

                                                        options: {

                                                            responsive: true,

                                                            plugins: {

                                                                legend: {

                                                                    labels: {

                                                                        color:
                                                                            '#f8fafc'

                                                                    }

                                                                }

                                                            }

                                                        }

                                                    }
                                                );

                                        }


                                        /* BAR CHART */

                                        let ctx2 =
                                            document.getElementById(
                                                'subjectBarChart'
                                            );


                                        if (ctx2) {

                                            barChart =
                                                new Chart(
                                                    ctx2.getContext('2d'),
                                                    {

                                                        type: 'bar',

                                                        data: {

                                                            labels:
                                                                labels,

                                                            datasets: [{

                                                                label:
                                                                    'Completed Tasks',

                                                                data:
                                                                    completed,

                                                                backgroundColor:
                                                                    '#8b5cf6'

                                                            }]

                                                        },

                                                        options: {

                                                            responsive: true,

                                                            scales: {

                                                                x: {

                                                                    ticks: {

                                                                        color:
                                                                            '#f8fafc'

                                                                    }

                                                                },

                                                                y: {

                                                                    ticks: {

                                                                        color:
                                                                            '#f8fafc'

                                                                    }

                                                                }

                                                            },

                                                            plugins: {

                                                                legend: {

                                                                    labels: {

                                                                        color:
                                                                            '#f8fafc'

                                                                    }

                                                                }

                                                            }

                                                        }

                                                    }
                                                );

                                        }

                                    });

                                },

                                100
                            );

                        }

                    }
                );


                /* =========================
                   ADD SUBJECT
                ========================= */

                $scope.addSubject = function() {

                    $http.post(
                        'add_subject.php',
                        $scope.newSubject
                    )

                    .then(function() {

                        $scope.newSubject = {};

                        $scope.loadSubjects();

                    });

                };


                /* =========================
                   DELETE SUBJECT
                ========================= */

                $scope.deleteSubject = function(id) {

                    if (
                        confirm(
                            'Delete this subject? All associated tasks will be removed.'
                        )
                    ) {

                        $http.post(
                            'delete_subject.php',
                            {
                                id: id
                            }
                        )

                        .then(function() {

                            $scope.loadSubjects();

                            $scope.loadTasks();

                        });

                    }

                };


                /* =========================
                   ADD TASK
                ========================= */

                $scope.addTask = function() {

                    $http.post(
                        'add_task.php',
                        $scope.newTask
                    )

                    .then(function() {

                        $scope.newTask = {};

                        $scope.loadDashboard();

                        $scope.loadTasks();

                        $scope.loadSubjects();

                    });

                };


                /* =========================
                   TOGGLE TASK STATUS
                ========================= */

                $scope.toggleTaskStatus = function(task) {

                    let newStatus =
                        task.status === 'Completed'
                            ? 'Pending'
                            : 'Completed';


                    $http.post(
                        'update_task_status.php',
                        {
                            id: task.id,
                            status: newStatus
                        }
                    )

                    .then(function() {

                        $scope.loadDashboard();

                        $scope.loadTasks();

                        $scope.loadSubjects();

                    });

                };


                /* =========================
                   DELETE TASK
                ========================= */

                $scope.deleteTask = function(id) {

                    $http.post(
                        'delete_task.php',
                        {
                            id: id
                        }
                    )

                    .then(function() {

                        $scope.loadDashboard();

                        $scope.loadTasks();

                        $scope.loadSubjects();

                    });

                };


                /* =========================
                   SETTINGS
                ========================= */

                $scope.updateSettings = function() {

                    $http.post(
                        'update_settings.php',
                        $scope.settingsForm
                    )

                    .then(function() {

                        alert(
                            'Settings updated successfully!'
                        );

                        $scope.loadDashboard();

                    });

                };


                /* =========================
                   TIMER
                ========================= */

                let timerTimeout = null;

                let timerPromise = null;


                $scope.timerSeconds =
                    25 * 60;

                $scope.timerRunning =
                    false;


                function formatTime(sec) {

                    let m =
                        Math.floor(sec / 60);

                    let s =
                        sec % 60;


                    return (

                        (m < 10 ? '0' : '') +
                        m +
                        ':' +
                        (s < 10 ? '0' : '') +
                        s

                    );

                }


                $scope.timerDisplay =
                    formatTime(
                        $scope.timerSeconds
                    );


                $scope.setTimerDuration =
                    function(mins) {

                        $scope.pauseTimer();

                        $scope.timerSeconds =
                            mins * 60;

                        $scope.timerDisplay =
                            formatTime(
                                $scope.timerSeconds
                            );

                    };


                $scope.startTimer =
                    function() {

                        if (!$scope.timerRunning) {

                            $scope.timerRunning =
                                true;


                            timerPromise =
                                function() {

                                    if (
                                        $scope.timerSeconds > 0 &&
                                        $scope.timerRunning
                                    ) {

                                        $scope.timerSeconds--;

                                        $scope.timerDisplay =
                                            formatTime(
                                                $scope.timerSeconds
                                            );


                                        timerTimeout =
                                            $timeout(
                                                timerPromise,
                                                1000
                                            );

                                    }

                                    else if (
                                        $scope.timerSeconds === 0
                                    ) {

                                        $scope.timerRunning =
                                            false;

                                        alert(
                                            'Focus session completed! Great job!'
                                        );


                                        $http.post(
                                            'save_session.php',
                                            {
                                                duration: 25
                                            }
                                        );

                                    }

                                };


                            timerTimeout =
                                $timeout(
                                    timerPromise,
                                    1000
                                );

                        }

                    };


                $scope.pauseTimer =
                    function() {

                        $scope.timerRunning =
                            false;


                        if (timerTimeout) {

                            $timeout.cancel(
                                timerTimeout
                            );

                            timerTimeout =
                                null;

                        }

                    };


                $scope.resetTimer =
                    function() {

                        $scope.pauseTimer();

                        $scope.timerSeconds =
                            25 * 60;

                        $scope.timerDisplay =
                            formatTime(
                                $scope.timerSeconds
                            );

                    };

            }
        );

    </script>

</body>

</html>