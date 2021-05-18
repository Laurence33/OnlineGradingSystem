<?php
session_start();
include "includes/config.php";

if (strlen($_SESSION['alogin']) == '') {
    header("Location: index.php");
}

// The admin is logged in and can access the page

if (isset($_POST['createSubjectAdvising'])) {
    $classCode = $_POST['classCode'];
    $classId = $_POST['classId'];
    $subjectId = $_POST['subjectId'];
    $status = 1;

    $sql = "INSERT INTO  tblsubjectadvising(ClassCode,ClassId,SubjectId,Status) VALUES(:classcode,:classid,:subid,:status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':classcode', $classCode, PDO::PARAM_STR);
    $query->bindParam(':classid', $classId, PDO::PARAM_STR);
    $query->bindParam(':subid', $subjectId, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->execute();

    $lastInsertId = $dbh->lastInsertId();
    if ($lastInsertId) {
        $msg = "Subject Advising Created successfully";
    } else {
        $error = "Something went wrong. Please try again";
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

        <h2>Create Subject Advising</h2>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-primary">
                    <i class="fas fa-home"></i>
                    <a href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Classes</li>
                <li class="breadcrumb-item active" aria-current="page">Create Subject Advising</li>
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
                <label for="classCode">Class Code</label>
                <input type="text" name="classCode" class="form-control" id="classCode" placeholder="IT3A21">
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

            <div class="form-group">
                <label for="subjectId">Subject</label>
                <select class="form-control" id="subjectId" name="subjectId">
                    <?php
                    $sql1 = "SELECT * FROM tblsubjects";
                    $query1 = $dbh->prepare($sql1);
                    $query1->execute();
                    $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
                    $cnt1 = 1;
                    if ($query->rowCount() > 0) {
                        foreach ($results1 as $result1) {
                    ?>
                            <option value="<?php echo htmlentities($result1->id); ?>"><?php echo htmlentities($result1->SubjectCode) . ' ' . htmlentities($result1->SubjectName); ?></option>
                    <?php $cnt1 += 1;
                        }
                    } ?>
                </select>
            </div>

            <button type="submit" name="createSubjectAdvising" class="btn btn-primary">Submit</button>

        </form>

    </div>


</div>
<!-- closing div for .wrapper -->
<script>
    document.getElementById("subjects").setAttribute("class", "active")
    document.getElementById("SubjectsSubmenu").classList.toggle("show");
</script>

<?php include "footer.php";
?>