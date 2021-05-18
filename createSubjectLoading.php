<?php
session_start();
include "includes/config.php";

if (strlen($_SESSION['alogin']) == '') {
    header("Location: index.php");
}

// The admin is logged in and can access the page

if (isset($_POST['createSubjectLoading'])) {
    $advisingId = $_POST['advisingId'];
    $profId = $_POST['profId'];
    $status = 1;

    $sql = "INSERT INTO  tblsubjectloading(AdvisingId,ProfessorId,Status) VALUES(:advisingid,:profid,:status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':advisingid', $advisingId, PDO::PARAM_STR);
    $query->bindParam(':profid', $profId, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if ($lastInsertId) {
        $msg = "Subject Loading Created successfully";
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

        <h2>Create Subject Loading</h2>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-primary">
                    <i class="fas fa-home"></i>
                    <a href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Classes</li>
                <li class="breadcrumb-item active" aria-current="page">Create Subject Loading</li>
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
                <label for="advisingId">Class Code</label>
                <select class="form-control" id="advisingId" name="advisingId">
                    <?php
                    $sql = "SELECT * FROM tblsubjectadvising";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    $cnt = 1;
                    if ($query->rowCount() > 0) {
                        foreach ($results as $result) {
                            if (!$result->Status) continue;
                            $subjectSql = "SELECT * FROM tblsubjects WHERE id=:subjectid";
                            $subjectQuery = $dbh->prepare($subjectSql);
                            $subjectQuery->bindParam(':subjectid', $result->SubjectId);
                            $subjectQuery->execute();
                            $subject = $subjectQuery->fetch(PDO::FETCH_OBJ);
                    ?>
                            <option value="<?php echo htmlentities($result->id); ?>"><?php echo htmlentities($result->ClassCode) . ' ' . htmlentities($subject->SubjectName); ?></option>
                    <?php $cnt += 1;
                        }
                    } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="profId">Professor</label>
                <select class="form-control" id="profId" name="profId">
                    <?php
                    $sql1 = "SELECT * FROM tblprofessors";
                    $query1 = $dbh->prepare($sql1);
                    $query1->execute();
                    $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
                    $cnt1 = 1;
                    if ($query->rowCount() > 0) {
                        foreach ($results1 as $result1) {
                            if (!$result1->Status) continue;
                    ?>
                            <option value="<?php echo htmlentities($result1->id); ?>"><?php echo htmlentities($result1->ProfessorName); ?></option>
                    <?php $cnt1 += 1;
                        }
                    } ?>
                </select>
            </div>

            <button type="submit" name="createSubjectLoading" class="btn btn-primary">Submit</button>

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