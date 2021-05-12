<?php

session_start();
include "includes/config.php";

if (strlen($_SESSION['alogin']) == '') {
    header("Location: index.php");
}

// The admin is logged in and has access to the page

if (!isset($_GET['studid'])) {
    header("Location: dashoard.php");
}

if (isset($_POST['editStudent'])) {
    $studId = $_POST['studId'];
    $studName = $_POST['studName'];
    $studEmail = $_POST['studEmail'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $classId = $_POST['classId'];
    $status = $_POST['status'];
    $id = $_POST['id'];

    $sql = "UPDATE tblstudents SET StudentId=:studid, StudentName=:studname, StudentEmail=:studemail, Gender=:gender, Birthdate=:birthdate, ClassId=:classid, status=:status WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':studid', $studId, PDO::PARAM_STR);
    $query->bindParam(':studname', $studName, PDO::PARAM_STR);
    $query->bindParam(':studemail', $studEmail, PDO::PARAM_STR);
    $query->bindParam(':gender', $gender, PDO::PARAM_STR);
    $query->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
    $query->bindParam(':classid', $classId, PDO::PARAM_STR);
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

        <h2>Edit Student</h2>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-primary">
                    <i class="fas fa-home"></i>
                    <a href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Students</li>
                <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
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

        <!-- GET PROFESSOR INFO -->
        <?php
        $id = $_GET['studid'];
        $sql1 = "SELECT * FROM tblstudents WHERE id=:id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':id', $id, PDO::PARAM_STR);
        $query1->execute();
        if ($query1->rowCount() > 0) {
            $result1 = $query1->fetch(PDO::FETCH_OBJ); ?>

            <form method="POST">
                <input type="hidden" name="id" value="<?php echo htmlentities($result1->id) ?>">

                <div class="form-group">
                    <label for="studId">Student ID</label>
                    <input type="text" name="studId" class="form-control" id="studId" placeholder="18-06084" value="<?php echo htmlentities($result1->StudentId); ?>">
                </div>

                <div class="form-group">
                    <label for="studName">Name</label>
                    <input type="text" name="studName" class="form-control" id="studName" placeholder="John Doe" value="<?php echo htmlentities($result1->StudentName); ?>">
                </div>

                <div class="form-group">
                    <label for="studEmail">Email</label>
                    <input type="email" name="studEmail" class="form-control" id="studEmail" placeholder="johndoe@gmail.com" value="<?php echo htmlentities($result1->StudentEmail); ?>">
                </div>

                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select class="form-control" id="gender" name="gender">
                        <option value="Male" <?php if ($result1->Gender == "Male") echo "selected"; ?>>Male</option>
                        <option value="Female" <?php if ($result1->Gender == "Female") echo "selected"; ?>>Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="birthdate">Birthdate</label>
                    <input type="date" name="birthdate" class="form-control" id="birthdate" value="<?php echo htmlentities($result1->Birthdate); ?>">
                </div>

                <div class="form-group">
                    <label for="classId">Class Name</label>
                    <select class="form-control" id="classId" name="classId">
                        <?php
                        $sql2 = "SELECT * FROM tblclasses";
                        $query2 = $dbh->prepare($sql2);
                        $query2->execute();
                        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
                        if ($query2->rowCount() > 0) {
                            foreach ($results2 as $result2) {
                        ?>
                                <option value="<?php echo htmlentities($result2->id); ?>" <?php if ($result1->ClassId == $result2->id) echo "selected"; ?>><?php echo htmlentities($result2->ClassName); ?></option>
                        <?php
                            }
                        } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="1" <?php if ($result1->Status == 1) echo "selected"; ?>>Active</option>
                        <option value="0" <?php if ($result1->Status == 0) echo "selected"; ?>>Inactive</option>
                    </select>
                </div>

                <button type="submit" name="editStudent" class="btn btn-primary">Submit</button>

            </form>
        <?php
        } else {
            $error = "Cannot find student with id=$id";
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
    document.getElementById("students").setAttribute("class", "active")
    document.getElementById("studSubmenu").classList.toggle("show");
</script>

<?php include "footer.php";
?>