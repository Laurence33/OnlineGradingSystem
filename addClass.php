<?php

session_start();
include "includes/config.php";

if (strlen($_SESSION['alogin']) == '') {
    header("Location: index.php");
} else {
    if (isset($_POST['addClass'])) {
        $className = $_POST['className'];
        $year = $_POST['year'];
        $section = $_POST['section'];
        $status = 1;

        $sql = "INSERT INTO  tblclasses(ClassName,Year,Section,Status) VALUES(:classname,:year,:section,:status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':classname', $className, PDO::PARAM_STR);
        $query->bindParam(':year', $year, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if ($lastInsertId) {
            $msg = "Class Created successfully";
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

        <h2>Add Class Page</h2>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-primary">
                    <i class="fas fa-home"></i>
                    <a href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Classes</li>
                <li class="breadcrumb-item active" aria-current="page">Add Class</li>
            </ol>
        </nav>
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
                <label for="className">Class Name</label>
                <input type="text" name="className" class="form-control" id="className" placeholder="BSIT3A">
            </div>

            <div class="form-group">
                <label for="year">Year Level</label>
                <input type="number" minVal="1" maxVal="5" name="year" class="form-control" id="year" placeholder="3">
            </div>

            <div class="form-group">
                <label for="section">Section</label>
                <input type="text" minlength="1" maxlength="1" name="section" class="form-control" id="section" placeholder="A">
            </div>

            <button type="submit" name="addClass" class="btn btn-primary">Submit</button>

        </form>

    </div>


</div>
<!-- closing div for .wrapper -->
<script>
    document.getElementById("classes").setAttribute("class", "active")
    document.getElementById("classesSubmenu").classList.toggle("show");
</script>

<?php include "footer.php";
?>