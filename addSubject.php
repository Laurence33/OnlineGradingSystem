<?php
session_start();
include "includes/config.php";

if (strlen($_SESSION['alogin']) == '') {
    header("Location: index.php");
} else {
    if (isset($_POST['addSubject'])) {
        $subCode = $_POST['subCode'];
        $subName = $_POST['subName'];
        $status = 1;

        $sql = "INSERT INTO  tblsubjects(SubjectName,SubjectCode, Status) VALUES(:subjectname,:subjectcode, :status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':subjectname', $subName, PDO::PARAM_STR);
        $query->bindParam(':subjectcode', $subCode, PDO::PARAM_STR);
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

        <h2>Add Subject Page</h2>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-primary">
                    <i class="fas fa-home"></i>
                    <a href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Subjects</li>
                <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
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
                <label for="subjectCode">Subject Code</label>
                <input type="text" name="subCode" class="form-control" id="subjectCode" placeholder="IT2077">
            </div>

            <div class="form-group">
                <label for="subjectName">Subject Name</label>
                <input type="text" name="subName" class="form-control" id="subjectName" placeholder="Web Development">
            </div>

            <button type="submit" name="addSubject" class="btn btn-primary">Submit</button>

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