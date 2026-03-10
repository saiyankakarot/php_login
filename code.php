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
        // SMTP settings
        $mail->isSMTP();
        $mail->SMTPDebug  = 0; // 0 = off, 2 = debug
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sahilk02523@gmail.com';       // your Gmail
        $mail->Password   = 'rtdwcqhuixahmqyz';           // 16-char app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender & receiver
        $mail->setFrom('sahilk02523@gmail.com', 'My Website');
        $mail->addAddress($email);

        // Email content
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

    // Password match check
    if ($password !== $cpassword) {
        $_SESSION['status'] = "Passwords do not match";
        header("Location: register.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $verify_token = md5(rand());

    // Check if email exists
    $check_email = "SELECT email, verify_token FROM users WHERE email='$email' LIMIT 1";
    $check_email_run = mysqli_query($con, $check_email);

    if (mysqli_num_rows($check_email_run) > 0) {
        $row = mysqli_fetch_assoc($check_email_run);
        sendemail_verify($name, $email, $row['verify_token']); // resend verification
        $_SESSION['status'] = "Email already exists. Verification email resent.";
        header("Location: register.php");
        exit();
    }

    // Insert new user
    $query = "INSERT INTO users (name, phone, email, password, verify_token)
              VALUES ('$name', '$phone', '$email', '$hashed_password', '$verify_token')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        sendemail_verify($name, $email, $verify_token); // send verification email
        $_SESSION['status'] = "Registration successful! Check your email to verify.";
        header("Location: register.php");
        exit();
    } else {
        $_SESSION['status'] = "Registration failed";
        header("Location: register.php");
        exit();
    }

} else {
    $_SESSION['status'] = "Invalid request";
    header("Location: register.php");
    exit();
}
?>
