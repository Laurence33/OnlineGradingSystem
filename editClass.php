<?php

session_start();
include "includes/config.php";

if (strlen($_SESSION['alogin']) == '') {
    header("Location: index.php");
}

// The admin is logged in and has access to the page

if (!isset($_GET['classid'])) {
    header("Location: dashoard.php");
}

if (isset($_POST['editClass'])) {
    $className = $_POST['className'];
    $year = $_POST['year'];
    $section = $_POST['section'];
    $status = $_POST['status'];
    $id = $_POST['id'];

    $sql = "UPDATE tblclasses SET ClassName=:classname, Year=:year, Section=:section, Status=:status WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':classname', $className, PDO::PARAM_STR);
    $query->bindParam(':year', $year, PDO::PARAM_STR);
    $query->bindParam(':section', $section, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->execute();
    $msg = "Data has been updated successfully";
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

        <h2>Edit Class</h2>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-primary">
                    <i class="fas fa-home"></i>
                    <a href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Classes</li>
                <li class="breadcrumb-item active" aria-current="page">Edit Class</li>
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

        <!-- GET CLASS INFO -->
        <?php
        $id = $_GET['classid'];
        $sql1 = "SELECT * FROM tblclasses WHERE id=:id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':id', $id, PDO::PARAM_STR);
        $query1->execute();
        if ($query1->rowCount() > 0) {
            $result1 = $query1->fetch(PDO::FETCH_OBJ); ?>

            <form method="POST">
                <input type="hidden" name="id" value="<?php echo htmlentities($result1->id) ?>">
                <div class="form-group">
                    <label for="className">Class Name</label>
                    <input type="text" name="className" class="form-control" id="className" placeholder="BSIT3A" value="<?php echo htmlentities($result1->ClassName); ?>">
                </div>

                <div class="form-group">
                    <label for="year">Year Level</label>
                    <input type="number" minVal="1" maxVal="5" name="year" class="form-control" id="year" placeholder="3" value="<?php echo htmlentities($result1->Year); ?>">
                </div>

                <div class="form-group">
                    <label for="section">Section</label>
                    <input type="text" minlength="1" maxlength="1" name="section" class="form-control" id="section" placeholder="A" value="<?php echo htmlentities($result1->Section); ?>">
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="1" <?php if ($result1->Status == 1) echo "selected"; ?>>Active</option>
                        <option value="0" <?php if ($result1->Status == 0) echo "selected"; ?>>Inactive</option>
                    </select>
                </div>

                <button type="submit" name="editClass" class="btn btn-primary">Submit</button>

            </form>
        <?php
        } else {
            $error = "Cannot find class with id=$id";
        ?>
            <div class="alert alert-danger left-icon-alert" role="alert">
                <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
            </div>
        <?php
        }
        ?>



    </div>


</div>
<!-- closing div for .wrapper -->
<script>
    document.getElementById("classes").setAttribute("class", "active")
    document.getElementById("classesSubmenu").classList.toggle("show");
</script>

<?php include "footer.php";
?>