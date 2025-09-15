<?php 
session_start();
include('data.php'); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';
function sendemail_verify($name, $email, $verify_token)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP Server Settings
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com';                    
        $mail->SMTPAuth   = true;                                  
        $mail->Username   = 'sahilk02523@gmail.com';                
        $mail->Password   = 'agjv gtws iyrd llla'; // App Password from Google Account
        $mail->SMTPSecure = 'ssl';                                  
        $mail->Port       = 465;                                    

        // Sender & Recipient
        $mail->setFrom('sahilk02523@gmail.com', $name);
        $mail->addAddress($email);     

        // Email Content
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
        // echo "Verification Email Sent";
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
if (isset($_POST['register_btn'])) {
    $name=$_POST['name'];
    $phone=$_POST['phone'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $cpassword= $_POST['cpassword'];
    $verify_token= md5(rand());

   
    if ($password !== $cpassword) {
        $_SESSION['status'] = "Passwords do not match";
        header("Location: register.php");
        exit();
    }
   
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $check_email_query = "SELECT email FROM users WHERE email='$email' LIMIT 1";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if (mysqli_num_rows($check_email_query_run) > 0) {
        // Email exists → resend verification
         $user_data = mysqli_fetch_assoc($check_email_query_run);
        $existing_token = $user_data['verify_token'];

        sendemail_verify($name, $email, $existing_token);
        $_SESSION['status'] = "Email already exists";
        header("Location: register.php");
        exit();
    }


    $query = "INSERT INTO users (name, phone, email, password, verify_token) 
              VALUES ('$name', '$phone', '$email', '$hashed_password', '$verify_token')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        sendemail_verify($name, $email, $verify_token);
        $_SESSION['status'] = "Registration successful! Please check your email to verify.";
        header("Location: register.php");
        exit();
    } else {
        $_SESSION['status'] = "Registration failed. Try again.";
        header("Location: register.php");
        exit();
    }
} else {
    $_SESSION['status'] = "Invalid request.";
    header("Location: register.php");
    exit();
}
?> 
