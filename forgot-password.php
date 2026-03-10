<?php
session_start();
include('data.php'); // Your DB connection

$message = '';
$error = '';

if (isset($_POST['reset_request_btn'])) {
    $email = $_POST['email'];

    // Check if email exists in database
    $stmt = $con->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // In a full system, you would generate a token and send an email here.
        // For now, we will simulate the success.
        $message = "A password reset link has been sent to your email!";
    } else {
        $error = "No account found with that email address.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f2f7ff; font-family: 'Roboto', sans-serif; }
        .card { border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); margin-top: 50px; }
        .btn-primary { background: linear-gradient(90deg,#4e54c8,#8f94fb); border: none; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header text-center"><h4>Reset Password</h4></div>
                <div class="card-body">
                    
                    <?php if($message): ?>
                        <div class="alert alert-success"><?= $message; ?></div>
                    <?php endif; ?>

                    <?php if($error): ?>
                        <div class="alert alert-danger"><?= $error; ?></div>
                    <?php endif; ?>

                    <p class="text-muted small">Enter your email address and we'll send you a link to reset your password.</p>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                        </div>
                        <button type="submit" name="reset_request_btn" class="btn btn-primary w-100">Send Reset Link</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="login.php">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>