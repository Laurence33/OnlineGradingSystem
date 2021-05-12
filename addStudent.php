<?php
session_start();
include "includes/config.php";

if (strlen($_SESSION['alogin']) == '') {
    header("Location: index.php");
} else {
    if (isset($_POST['addStudent'])) {
        $studName = $_POST['studName'];
        $studEmail = $_POST['studEmail'];
        $studId = $_POST['studId'];
        $gender = $_POST['gender'];
        $birthdate = $_POST['birthdate'];
        $classId = $_POST['classId'];
        $status = 1;

        $sql = "INSERT INTO  tblstudents(StudentName,StudentId,StudentEmail,Gender,Birthdate,ClassId,Status) VALUES(:studname,:studid,:studemail,:gender,:birthdate,:classid,:status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':studname', $studName, PDO::PARAM_STR);
        $query->bindParam(':studid', $studId, PDO::PARAM_STR);
        $query->bindParam(':studemail', $studEmail, PDO::PARAM_STR);
        $query->bindParam(':gender', $gender, PDO::PARAM_STR);
        $query->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
        $query->bindParam(':classid', $classId, PDO::PARAM_STR);
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

        <h2>Add Student Page</h2>

        <!-- BREADCRUMB -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <i class="fas fa-home"></i>
                    <a class="text-primary" href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Students</li>
                <li class="breadcrumb-item active" aria-current="page">Add Student</li>
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
                <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
            </div>
        <?php } ?>
        <!-- END or Success/Error Message -->

        <form method="POST">

            <div class="form-group">
                <label for="studId">Student ID</label>
                <input type="text" name="studId" class="form-control" id="studId" placeholder="18-12345">
            </div>

            <div class="form-group">
                <label for="studName">Student Name</label>
                <input type="text" name="studName" class="form-control" id="studName" placeholder="John Doe">
            </div>

            <div class="form-group">
                <label for="studEmail">Student Email</label>
                <input type="email" name="studEmail" class="form-control" id="studEmail" placeholder="johndoe@gmail.com">
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

            <div class="form-group">
                <label for="classId">Class Name</label>
                <select class="form-control" id="classId" name="classId">
                    <?php
                    $sql = "SELECT * FROM tblclasses";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    $cnt = 1;
                    if ($query->rowCount() > 0) {
                        foreach ($results as $result) {
                    ?>
                            <option value="<?php echo htmlentities($result->id); ?>"><?php echo htmlentities($result->ClassName); ?></option>
                    <?php $cnt += 1;
                        }
                    } ?>
                </select>
            </div>

            <button type="submit" name="addStudent" class="btn btn-primary">Submit</button>

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