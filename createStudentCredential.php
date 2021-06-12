<?php

session_start();
include "includes/config.php";

if (strlen($_SESSION['alogin']) == '') {
    header("Location: index.php");
}
// Admin is logged in and can access the page


if (isset($_POST['createPassword'])) {
    $studId = $_POST['studId'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    if ($password != $cpassword) {
        $error = "Password does not match, please try again.";
    } else if ($username == "") {
        $error = "Please enter a username.";
    } else {
        // Check if username is in use
        $sql1 = "SELECT * from tbllogin WHERE UserName=:username";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':username', $username, PDO::PARAM_STR);
        $query1->execute();
        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
        if ($query1->rowCount() > 0) {
            $error = "Username is already in use.";
        } else {
            // Username is unused and password matches, add the credential to database
            $hash = md5($password);
            $sql2 = "INSERT INTO tbllogin(UserId, Role, UserName, Password) VALUES(:studid, 3, :username, :password);";
            $query2 = $dbh->prepare($sql2);
            $query2->bindParam(':studid', $studId, PDO::PARAM_STR);
            $query2->bindParam(':username', $username, PDO::PARAM_STR);
            $query2->bindParam(':password', $hash, PDO::PARAM_STR);
            $query2->execute();
            $lastInsertId = $dbh->lastInsertId();
            if ($lastInsertId) {
                $msg = "Credential Created successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
        }
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

        <h2>Create Student Credential</h2>

        <!-- BREADCRUMB -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <i class="fas fa-home"></i>
                    <a class="text-primary" href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Students</li>
                <li class="breadcrumb-item active" aria-current="page">Create Student Credential</li>
            </ol>
        </nav>
        <!-- END OF BREADCRUMB -->

        <!-- Success/Error Message -->
        <?php if ($msg) { ?>
            <div class="alert alert-success left-icon-alert" role="alert">
                <strong>Success! </strong><?php echo htmlentities($msg); ?>
            </div> <?php
                } else if ($error) { ?>
            <div class="alert alert-danger left-icon-alert" role="alert">
                <strong>Oh snap! </strong> <?php echo htmlentities($error); ?>
            </div>
        <?php } ?>
        <!-- END or Success/Error Message -->

        <form method="POST">

            <div class="form-group">
                <label for="studId">Student</label>
                <select class="form-control" id="studId" name="studId">
                    <?php
                    $sql = "SELECT id,StudentName,StudentId from tblstudents";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    $count = 0;
                    if ($query->rowCount() > 0) {
                        foreach ($results as $result) {
                            $loginSql = "SELECT * FROM tbllogin WHERE UserId=:studid AND Role=3";
                            $loginQuery = $dbh->prepare($loginSql);
                            $loginQuery->bindParam(':studid', $result->id);
                            $loginQuery->execute();
                            if ($loginQuery->rowCount() > 0) continue;
                    ?>
                            <option value="<?php echo htmlentities($result->id) ?>"><?php echo htmlentities($result->StudentName) ?></option>
                    <?php
                            $count += 1;
                        }
                        if ($count == 0) {
                            echo '<option> No Student to create credentials.</option>';
                        }
                    } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" id="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password">
            </div>

            <div class="form-group">
                <label for="cpassword">Confirm Password</label>
                <input type="password" name="cpassword" class="form-control" id="cpassword">
            </div>

            <button type="submit" name="createPassword" <?php if ($count == 0) echo 'disabled'; ?> class="btn btn-primary">Submit</button>

        </form>

    </div>


</div>
<!-- closing div for .wrapper -->
<script>
    document.getElementById("students").setAttribute("class", "active")
    document.getElementById("studSubmenu").classList.toggle("show");
</script>

<?php include "footer.php";
?>