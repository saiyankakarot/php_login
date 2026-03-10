<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}

include('data.php'); // DB connection

$error = '';

if (isset($_POST['login_btn'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. Use Prepared Statements for security
    $stmt = $con->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // 2. Check Password first
        if (password_verify($password, $user['password'])) {
            
            // 3. Check Verification Status
            // Note: We check if the key exists to prevent the 'Undefined array key' error
            if (isset($user['is_verified']) && $user['is_verified'] == 0) {
                $error = "Please verify your email before logging in.";
            } else {
                // Successful login
                session_regenerate_id(true);
                $_SESSION['user'] = [
                    'id'    => $user['id'],
                    'name'  => $user['name'],
                    'email' => $user['email']
                ];
                header("Location: dashboard.php");
                exit();
            }
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No account found with this email.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: #f2f7ff;
        font-family: 'Roboto', sans-serif;
    }
    .card {
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .btn-primary {
        background: linear-gradient(90deg,#4e54c8,#8f94fb);
        border: none;
    }
</style>
</head>

<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card">
                <div class="card-header text-center">
                    <h4>Login</h4>
                </div>

                <div class="card-body">

                    <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" name="login_btn" class="btn btn-primary w-100">
                            Login
                        </button>

                    </form>

                    <div class="text-center mt-3">
                        <a href="forgot-password.php">Forgot Password?</a>
                    </div>

                </div>
            </div>

            <p class="text-center mt-2">
                Don’t have an account?
                <a href="register.php">Register</a>
            </p>

        </div>
    </div>
</div>
</body>
</html>

























