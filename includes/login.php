<?php
session_start();
include('data.php'); // your DB connection

if (isset($_POST['login_btn'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];

    // Fetch user from DB
    $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            // Login successful → set session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ];

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "No user found with this email";
    }
}
?>
























<!--?php

$page_title="Login Form";
include('includes/header.php');
include('includes/Navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row text-center justify-content-center">
            <div class="col-nd-6">
            <div class="card shadow">
                <div class="card-header">
                    <h5>Login Form</h5>
                </div>
                <div class="card-body">
                    <form action="">
                        <div class="form-group mb-3">
                            <label for="">Email Address</label>
                            <input type="text" name="Email" class="form-contol">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Password</label>
                            <input type="text" name="password" class="form-contol">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Login Now</button>
                        </div>

                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>



<?php include('includes/footer.php');?>