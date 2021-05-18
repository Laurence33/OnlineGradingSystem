<?php
session_start();
include 'includes/config.php';

if (!isset($_SESSION['alogin']) and !isset($_SESSION['plogin'])) {
    header("Location: index.php");
}
// Admin or professor is logged in and can access the page

if (isset($_POST['changePassword'])) {

    $oldPw = $_POST['opassword'];
    $newPw = $_POST['password'];
    $confirmPw = $_POST['cpassword'];

    $table = "tbladmin";
    if (isset($_SESSION['plogin'])) {
        $table = 'tbllogin';
        $username = $_SESSION['plogin'];
    } else {
        $username = $_SESSION['alogin'];
    }

    if ($newPw == $confirmPw) { // check if password matches
        // check if the old password is correct
        $sql = "SELECT * FROM $table WHERE UserName=:username AND Password=:password";
        $query = $dbh->prepare($sql);
        $query->bindParam(":username", $username);
        $query->bindParam(":password", md5($oldPw));
        $query->execute();
        $result = $query->rowCount();
        if ($result == 1) {
            //proceed with change password
            $sql = "UPDATE $table SET Password=:password WHERE Username=:username";
            $query = $dbh->prepare($sql);
            $query->bindParam(":username", $username);
            $query->bindParam(":password", md5($newPw));
            $success = $query->execute();
            if ($success) {
                $msg = "Change Password Successful.";
            } else {
                $error = "Unknown error occured.";
            }
        } else {
            $error = "Incorrect password, please try again.";
        }
    } else {
        $error = "Password does not match.";
    }
}
include "header.php";
?>

<div class="wrapper">
    <?php include "includes/admin-sidenav.php"; ?>

    <!-- Page Content  -->
    <div id="content">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">

                <button class="btn btn-dark" type="button" id="sidebarCollapse" class="btn btn-info">
                    <i class="fas fa-align-justify"></i>
                    <span>Menu</span>
                </button>

                <h2>&nbsp; Online Grading System</h2>

            </div>
        </nav>

        <h2>Change Password</h2>

        <!-- Success/Error Message -->
        <?php if ($msg) { ?>
            <div class="alert alert-success left-icon-alert" role="alert">
                <strong>Success! </strong><?php echo htmlentities($msg); ?>
            </div> <?php
                } else if ($error) { ?>
            <div class="alert alert-danger left-icon-alert" role="alert">
                <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
            </div>
        <?php } ?>
        <!-- END or Success/Error Message -->

        <form method="POST">

            <div class="form-group">
                <label for="username">Username</label>
                <input readonly type="text" name="username" class="form-control" id="username" value="<?php echo $_SESSION['alogin'];
                                                                                                        echo $_SESSION['plogin']; ?>">
            </div>

            <div class="form-group">
                <label for="opassword">Old Password</label>
                <input type="password" name="opassword" class="form-control" id="opassword">
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" name="password" class="form-control" id="password">
            </div>

            <div class="form-group">
                <label for="cpassword">Confirm Password</label>
                <input type="password" name="cpassword" class="form-control" id="cpassword">
            </div>

            <button type="submit" name="changePassword" class="btn btn-primary">Submit</button>

        </form>

    </div>


</div>
<!-- closing div for .wrapper -->
<script>
    document.getElementById("changePassword").setAttribute("class", "active")
</script>

<?php include "footer.php";
?>