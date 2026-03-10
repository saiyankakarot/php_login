<?php
session_start();
include('data.php'); // DB connection

// If token not provided
if (!isset($_GET['token']) || empty($_GET['token'])) {
    $_SESSION['status'] = "Invalid verification request.";
    header("Location: login.php");
    exit();
}

// Sanitize token
$token = mysqli_real_escape_string($con, $_GET['token']);

// Find user by token
$query = "SELECT id, is_verified 
          FROM users 
          WHERE verify_token='$token' 
          LIMIT 1";

$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) === 1) {

    $user = mysqli_fetch_assoc($result);

    // If already verified
    if ((int)$user['is_verified'] === 1) {
        $_SESSION['status'] = "Email already verified. Please login.";
    }
    // Verify user now
    else {
        $update = "UPDATE users 
                   SET is_verified = 1, verify_token = NULL 
                   WHERE id = '{$user['id']}'";
        mysqli_query($con, $update);

        $_SESSION['status'] = "Email verified successfully. You can login now.";
    }

} else {
    $_SESSION['status'] = "Verification failed. Invalid or expired token.";
}

// Redirect to login
header("Location: login.php");
exit();
