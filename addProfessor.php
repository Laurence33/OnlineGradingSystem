<?php

session_start();
include "includes/config.php";

if (strlen($_SESSION['alogin']) == '') {
    header("Location: index.php");
} else {
    if (isset($_POST['addProfessor'])) {
        $profName = $_POST['profName'];
        $profEmail = $_POST['profEmail'];
        $gender = $_POST['gender'];
        $birthdate = $_POST['birthdate'];
        $status = 1;

        $sql = "INSERT INTO  tblprofessors(ProfessorName,ProfessorEmail,Gender,Birthdate,Status) VALUES(:profname,:profemail,:gender,:birthdate,:status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':profname', $profName, PDO::PARAM_STR);
        $query->bindParam(':profemail', $profEmail, PDO::PARAM_STR);
        $query->bindParam(':gender', $gender, PDO::PARAM_STR);
        $query->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if ($lastInsertId) {
            $msg = "Subject Created successfully";
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

        <h2>Add Professor Page</h2>

        <!-- BREADCRUMB -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <i class="fas fa-home"></i>
                    <a class="text-primary" href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Professors</li>
                <li class="breadcrumb-item active" aria-current="page">Add Professor</li>
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
                <label for="profName">Professor Name</label>
                <input type="text" name="profName" class="form-control" id="profName" placeholder="John Michaels">
            </div>

            <div class="form-group">
                <label for="profEmail">Professor Email</label>
                <input type="email" name="profEmail" class="form-control" id="profEmail" placeholder="johnmichaels@gmail.com">
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div class="form-group">
                <label for="birthdate">Birthdate</label>
                <input type="date" name="birthdate" class="form-control" id="birthdate">
            </div>

            <button type="submit" name="addProfessor" class="btn btn-primary">Submit</button>

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