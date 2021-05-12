<?php
session_start();
include "includes/config.php";

if (strlen($_SESSION['alogin']) == '') {
    header("Location: index.php");
}

// The admin is logged in and has access to the page

if (!isset($_GET['loadingid'])) {
    header("Location: dashoard.php");
}

if (isset($_POST['editSubjectLoading'])) {
    $advisingId = $_POST['advisingId'];
    $profId = $_POST['profId'];
    $status = $_POST['status'];
    $id = $_POST['id'];

    $sql = "UPDATE tblsubjectloading SET AdvisingId=:advisingid, ProfessorId=:profid, Status=:status WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':advisingid', $advisingId, PDO::PARAM_STR);
    $query->bindParam(':profid', $profId, PDO::PARAM_STR);
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

        <h2>Edit Subject Loading</h2>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-primary">
                    <i class="fas fa-home"></i>
                    <a href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Subjects</li>
                <li class="breadcrumb-item active" aria-current="page">Edit Subject Loading</li>
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
        $id = $_GET['loadingid'];
        $sql1 = "SELECT * FROM tblsubjectloading WHERE id=:id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':id', $id, PDO::PARAM_STR);
        $query1->execute();
        if ($query1->rowCount() > 0) {
            $result1 = $query1->fetch(PDO::FETCH_OBJ); ?>

            <form method="POST">
                <input type="hidden" name="id" value="<?php echo htmlentities($result1->id) ?>">

                <div class="form-group">
                    <label for="advisingId">Class Code</label>
                    <select class="form-control" id="advisingId" name="advisingId">
                        <?php
                        $sql2 = "SELECT * FROM tblsubjectadvising";
                        $query2 = $dbh->prepare($sql2);
                        $query2->execute();
                        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
                        $cnt2 = 1;
                        if ($query2->rowCount() > 0) {
                            foreach ($results2 as $result2) {
                        ?>
                                <option value="<?php echo htmlentities($result2->id); ?>" <?php if ($result1->AdvisingId == $result2->id) echo "selected"; ?>><?php echo htmlentities($result2->ClassCode); ?></option>
                        <?php $cnt2 += 1;
                            }
                        } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="profId">Professor</label>
                    <select class="form-control" id="profId" name="profId">
                        <?php
                        $sql3 = "SELECT * FROM tblprofessors";
                        $query3 = $dbh->prepare($sql3);
                        $query3->execute();
                        $results3 = $query3->fetchAll(PDO::FETCH_OBJ);
                        $cnt3 = 1;
                        if ($query3->rowCount() > 0) {
                            foreach ($results3 as $result3) {
                        ?>
                                <option value="<?php echo htmlentities($result3->id); ?>" <?php if ($result1->ProfessorId == $result3->id) echo "selected"; ?>><?php echo htmlentities($result3->ProfessorName); ?></option>
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

                <button type="submit" name="editSubjectLoading" class="btn btn-primary">Submit</button>

            </form>
        <?php
        } else {
            $error = "Cannot find subject loading with id=$id";
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