<?php

session_start();
include "includes/config.php";

if (strlen($_SESSION['alogin']) == '') {
    header("Location: index.php");
}
// Admin is logged in and can access the page


if (isset($_POST['resetPassword'])) {
    $profId = $_POST['profId'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if ($password != $cpassword) {
        $error = "Password does not match, please try again.";
    } else {

        $sql2 = "UPDATE tbllogin SET UserName=:username Password=:password WHERE ProfessorId=:profid;";
        $query2 = $dbh->prepare($sql2);
        $query2->bindParam(':profid', $profId, PDO::PARAM_STR);
        $query2->bindParam(':username', $username, PDO::PARAM_STR);
        $query2->bindParam(':password', md5($password), PDO::PARAM_STR);
        $success = $query2->execute();

        if ($success) {
            $msg = "Credential updated successfully";
        } else {
            $error = "Something went wrong. Please try again";
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

        <h2>Create Professor Credential</h2>

        <!-- BREADCRUMB -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <i class="fas fa-home"></i>
                    <a class="text-primary" href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Professors</li>
                <li class="breadcrumb-item active" aria-current="page">Create Professor Credential</li>
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
                <label for="profId">Professor</label>
                <select class="form-control" id="profId" name="profId">
                    <?php
                    // get professor details
                    $sql = "SELECT id,ProfessorName,Status from tblprofessors";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    if ($query->rowCount() > 0) {
                        foreach ($results as $result) {
                            if (!$result->Status) continue; // do not show professor if inactive
                            // if the professor already has a credential, do not show on the options
                            $loginSql = "SELECT * FROM tbllogin WHERE ProfessorId=:profid";
                            $loginQuery = $dbh->prepare($loginSql);
                            $loginQuery->bindParam('profid', $result->id);
                            $loginQuery->execute();
                            if ($loginQuery->rowCount() > 0) continue;
                    ?>
                            <option value="<?php echo htmlentities($result->id) ?>"><?php echo htmlentities($result->ProfessorName) ?></option>
                    <?php }
                    } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" id="username" placeholder="johnmichaels">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password">
            </div>

            <div class="form-group">
                <label for="cpassword">Confirm Password</label>
                <input type="password" name="cpassword" class="form-control" id="cpassword">
            </div>

            <button type="submit" name="createPassword" class="btn btn-primary">Submit</button>

        </form>

    </div>


</div>
<!-- closing div for .wrapper -->
<script>
    document.getElementById("professors").setAttribute("class", "active")
    document.getElementById("profSubmenu").classList.toggle("show");
</script>

<?php include "footer.php";
?>