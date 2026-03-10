<?php
session_start();
include('data.php');

$message = '';
$error = '';

// In a real app, you'd get a 'token' from the URL. 
// For this stage, we will use the email to identify the user.
if (isset($_POST['password_update_btn'])) {
    $email = $_POST['email'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password == $confirm_password) {
        // Hash the new password before saving!
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $con->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);
        
        if ($stmt->execute()) {
            header("Location: login.php?msg=password_updated");
            exit();
        } else {
            $error = "Something went wrong. Please try again.";
        }
        $stmt->close();
    } else {
        $error = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f2f7ff; }
        .card { border-radius: 15px; margin-top: 50px; border: none; box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .btn-primary { background: linear-gradient(90deg,#4e54c8,#8f94fb); border: none; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header text-center"><h4>Create New Password</h4></div>
                <div class="card-body">
                    <?php if($error): ?>
                        <div class="alert alert-danger"><?= $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="email" value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                        
                        <div class="mb-3">
                            <label>New Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" name="password_update_btn" class="btn btn-primary w-100">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>