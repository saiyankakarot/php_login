<?php

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