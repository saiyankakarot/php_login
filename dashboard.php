<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
// 1. SECURITY CHECK: If not logged in, boot them out
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; font-family: 'Inter', sans-serif; }
        .navbar { background: #4e54c8; background: linear-gradient(to right, #4e54c8, #8f94fb); }
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .welcome-text { color: #4e54c8; font-weight: 600; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark shadow-sm mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">MyAuth System</a>
            <div class="d-flex">
                <span class="navbar-text me-3 d-none d-md-inline">Logged in as: <?php echo htmlspecialchars($user['email']); ?></span>
                <a href="logout.php" class="btn btn-light btn-sm fw-bold px-3">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-5 text-center">
                    <h1 class="welcome-text mb-3">Welcome Back, <?php echo htmlspecialchars($user['name']); ?>! 🥳</h1>
                    <p class="text-muted">You have successfully bypassed the verification and login checks.</p>
                    
                    <div class="row mt-4">
                        <div class="col-sm-4">
                            <div class="p-3 border rounded mb-2 bg-white">
                                <h6 class="text-uppercase text-muted small">Status</h6>
                                <p class="mb-0 text-success fw-bold">Verified</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 border rounded mb-2 bg-white">
                                <h6 class="text-uppercase text-muted small">Role</h6>
                                <p class="mb-0 fw-bold">User</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 border rounded mb-2 bg-white">
                                <h6 class="text-uppercase text-muted small">Member Since</h6>
                                <p class="mb-0 fw-bold">Jan 2026</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>





































