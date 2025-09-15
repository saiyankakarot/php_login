<?php 
session_start();
include('data.php');  // Make sure this contains $con (mysqli connection)

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendemail_verify($name, $email, $verify_token)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com';                    
        $mail->SMTPAuth   = true;                                  
        $mail->Username   = 'sahilk02523@gmail.com';                
        $mail->Password   = 'agjv gtws iyrd llla'; // Use your app password
        $mail->SMTPSecure = 'ssl';                                  
        $mail->Port       = 465;                                    

        $mail->setFrom('sahilk02523@gmail.com', $name);
        $mail->addAddress($email);     

        $mail->isHTML(true);                                  
        $mail->Subject = 'Email Verification from PHP Registration';

        $email_template = "
            <h2>You have registered with us</h2>
            <h5>Verify your email address to login with the below link</h5>
            <br><br>
            <a href='http://localhost/yo/verify-email.php?token=$verify_token&email=$email'>Click here to verify</a>
        ";
        $mail->Body = $email_template;

        $mail->send();

    } catch (Exception $e) {
        // You might want to log this error instead of echoing it
        // echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if (isset($_POST['register_btn'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $email = mysqli_real_escape_string($con, $_POST['email']);  // Make sure HTML input name="email"
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $verify_token = md5(rand());

    if ($password !== $cpassword) {
        $_SESSION['status'] = "Passwords do not match";
        header("Location: register.php");
        exit();
    }

    // Check if email already exists
    $check_email_query = "SELECT email FROM users WHERE email='$email' LIMIT 1";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if (mysqli_num_rows($check_email_query_run) > 0) {
        $_SESSION['status'] = "Email already exists";
        header("Location: register.php");
        exit();
    }

    // Hash password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data
    $query = "INSERT INTO users (name, phone, email, password, verify_token) 
              VALUES ('$name', '$phone', '$email', '$hashed_password', '$verify_token')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        sendemail_verify($name, $email, $verify_token);
        $_SESSION['status'] = "Registration successful! Please check your email to verify.";
        header("Location: register.php");
        exit();
    } else {
        $_SESSION['status'] = "Registration failed. Please try again.";
        header("Location: register.php");
        exit();
    }
} else {
    $_SESSION['status'] = "Invalid request.";
    header("Location: register.php");
    exit();
} 
?>




