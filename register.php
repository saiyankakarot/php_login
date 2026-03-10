<?php
session_start();
include('data.php'); // your DB connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // PHPMailer autoload

/* ================= EMAIL FUNCTION ================= */
function sendemail_verify($name, $email, $verify_token)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->SMTPDebug  = 0;
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sahilk02523@gmail.com';
        $mail->Password   = 'rtdwcqhuixahmqyz';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('sahilk02523@gmail.com', 'My Website');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body = "
            <h2>Hello $name</h2>
            <p>Thanks for registering on our website.</p>
            <p>Please verify your email by clicking the link below:</p>
            <a href='http://localhost/yo/verify-email.php?token=$verify_token&email=$email'>
                Verify Email
            </a>
        ";
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        $mail->send();
        return true;

    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        exit;
    }
}

/* ================= REGISTRATION LOGIC ================= */
if (isset($_POST['register_btn'])) {

    $name       = mysqli_real_escape_string($con, $_POST['name']);
    $phone      = mysqli_real_escape_string($con, $_POST['phone']);
    $email      = mysqli_real_escape_string($con, $_POST['email']);
    $password   = $_POST['password'];
    $cpassword  = $_POST['cpassword'];

    if ($password !== $cpassword) {
        $_SESSION['status'] = "Passwords do not match";
        header("Location: register.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $verify_token = md5(rand());

    $check_email = "SELECT email, verify_token FROM users WHERE email='$email' LIMIT 1";
    $check_email_run = mysqli_query($con, $check_email);

    if (mysqli_num_rows($check_email_run) > 0) {
        $row = mysqli_fetch_assoc($check_email_run);
        sendemail_verify($name, $email, $row['verify_token']);
        $_SESSION['status'] = "Email already exists. Verification email resent.";
        header("Location: register.php");
        exit();
    }

    // $query = "INSERT INTO users (name, phone, email, password, verify_token)
    //           VALUES ('$name', '$phone', '$email', '$hashed_password', '$verify_token')";
    // $query_run = mysqli_query($con, $query);

    // if ($query_run) {
    //     sendemail_verify($name, $email, $verify_token);
    //     $_SESSION['status'] = "Registration successful! Check your email to verify.";
    //     header("Location: register.php");
    //     exit();
    // } else {
    //     $_SESSION['status'] = "Registration failed";
    //     header("Location: register.php");
    //     exit();

     $insert = mysqli_query($con, "INSERT INTO users (name, phone, email, password, verify_token) 
        VALUES ('$name','$phone','$email','$hashed_password','$verify_token')");

    if($insert){
        sendemail_verify($name,$email,$verify_token);
        $_SESSION['status'] = "Registration successful! Check your email.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['status'] = "Registration failed";
        header("Location: register.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Registration</title>
    <! Bootstrap 5 CSS >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <! Google Fonts >
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #f2f7ff;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background: linear-gradient(90deg, #4e54c8, #8f94fb);
            border: none;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #4e54c8;
        }
        .form-header {
            font-weight: 700;
            color: #4e54c8;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h3 class="text-center form-header mb-4">Register</h3>

                <?php if(isset($_SESSION['status'])): ?>
                    <div class="alert alert-info"><?php 
                        echo $_SESSION['status']; 
                        unset($_SESSION['status']);
                    ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="Enter phone number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="cpassword" class="form-control" placeholder="Confirm password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="register_btn" class="btn btn-primary btn-block">Register</button>
                    </div>
                    <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
                </form>

            </div>
        </div>
    </div>
</div>

<Bootstrap 5 JS >
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



































