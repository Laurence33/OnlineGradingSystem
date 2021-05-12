<?php
session_start();
include "includes/config.php";

if (strlen($_SESSION['alogin']) == '') {
    header("Location: index.php");
}

// The admin is logged in and has access to the page

if (!isset($_GET['advisingid'])) {
    header("Location: dashoard.php");
}

if (isset($_POST['editSubjectAdvising'])) {
    $classCode = $_POST['classCode'];
    $classId = $_POST['classId'];
    $subjectId = $_POST['subjectId'];
    $status = $_POST['status'];
    $id = $_POST['id'];

    $sql = "UPDATE tblsubjectAdvising SET ClassCode=:classcode, ClassId=:classid, SubjectId=:subjectid, Status=:status WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':classcode', $classCode, PDO::PARAM_STR);
    $query->bindParam(':classid', $classId, PDO::PARAM_STR);
    $query->bindParam(':subjectid', $subjectId, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $res = $query->execute();
    if ($res) {
        $msg = "Data has been updated successfully";
    } else {
        $error = "The update is not successful, please try again.";
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

        <h2>Edit Subject Advising</h2>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-primary">
                    <i class="fas fa-home"></i>
                    <a href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Subjects</li>
                <li class="breadcrumb-item active" aria-current="page">Edit Subject Advising</li>
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

        <!-- GET ADVISING INFO -->
        <?php
        $id = $_GET['advisingid'];
        $sql1 = "SELECT * FROM tblsubjectadvising WHERE id=:id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':id', $id, PDO::PARAM_STR);
        $query1->execute();
        if ($query1->rowCount() > 0) {
            $result1 = $query1->fetch(PDO::FETCH_OBJ); ?>

            <form method="POST">
                <input type="hidden" name="id" value="<?php echo htmlentities($result1->id) ?>">
                <div class="form-group">
                    <label for="classCode">Class Code</label>
                    <input type="text" name="classCode" class="form-control" id="classCode" placeholder="IT3A21" value="<?php echo htmlentities($result1->ClassCode); ?>">
                </div>

                <div class="form-group">
                    <label for="classId">Class Name</label>
                    <select class="form-control" id="classId" name="classId">
                        <?php
                        $sql2 = "SELECT * FROM tblclasses";
                        $query2 = $dbh->prepare($sql2);
                        $query2->execute();
                        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
                        $cnt2 = 1;
                        if ($query2->rowCount() > 0) {
                            foreach ($results2 as $result2) {
                        ?>
                                <option value="<?php echo htmlentities($result2->id); ?>" <?php if ($result1->ClassId == $result2->id) echo "selected"; ?>><?php echo htmlentities($result2->ClassName); ?></option>
                        <?php $cnt2 += 1;
                            }
                        } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="subjectId">Subject</label>
                    <select class="form-control" id="subjectId" name="subjectId">
                        <?php
                        $sql3 = "SELECT * FROM tblsubjects";
                        $query3 = $dbh->prepare($sql3);
                        $query3->execute();
                        $results3 = $query3->fetchAll(PDO::FETCH_OBJ);
                        $cnt3 = 1;
                        if ($query3->rowCount() > 0) {
                            foreach ($results3 as $result3) {
                        ?>
                                <option value="<?php echo htmlentities($result3->id); ?>" <?php if ($result1->SubjectId == $result3->id) echo "selected"; ?>><?php echo htmlentities($result3->SubjectName); ?></option>
                        <?php $cnt3 += 1;
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

                <button type="submit" name="editSubjectAdvising" class="btn btn-primary">Submit</button>

            </form>
        <?php
        } else {
            $error = "Cannot find subject advising with id=$id";
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
    document.getElementById("subjects").setAttribute("class", "active")
    document.getElementById("SubjectsSubmenu").classList.toggle("show");
</script>

<?php include "footer.php";
?>