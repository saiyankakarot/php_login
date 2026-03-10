<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            font-family: 'Roboto', sans-serif;
        }
        .card {
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        .btn-custom {
            background: linear-gradient(90deg,#4e54c8,#8f94fb);
            border: none;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card p-5">
                <h1>Welcome to My Website</h1>
                <p class="lead">A modern authentication system built with PHP & Bootstrap</p>

                <?php if(isset($_SESSION['user'])): ?>
                    <h3>Hello, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!</h3>
                    <a href="dashboard.php" class="btn btn-custom mt-3">Go to Dashboard</a>
                    <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-custom mt-3">Login</a>
                    <a href="register.php" class="btn btn-outline-light mt-3">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


