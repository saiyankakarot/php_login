<?php
$page_title = "Registration Form";
include('includes/header.php');
include('includes/Navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row text-center justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <?php
                        if (isset($_SESSION['status'])) {
                            echo '<div class="alert alert-info">' . $_SESSION['status'] . '</div>';
                            unset($_SESSION['status']);
                        }
                        ?>
                    <div class="card-header">
                        <h5>Registration Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="post">
                            <div class="form-group mb-3">
                                <label for="">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter Full Name" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Phone Number</label>
                                <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Confirm Password</label>
                                <input type="password" name="cpassword" class="form-control" placeholder="Confirm Password" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="register_btn" class="btn btn-primary">Register Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>


