<?php

session_start();
include "includes/config.php";

if (strlen($_SESSION['alogin']) == '') {
    header("Location: index.php");
}

// The admin is logged in and has access to the page

if (!isset($_GET['profid'])) {
    header("Location: dashoard.php");
}

if (isset($_POST['editProfessor'])) {
    $profName = $_POST['profName'];
    $profEmail = $_POST['profEmail'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $status = $_POST['status'];
    $id = $_POST['id'];

    $sql = "UPDATE tblprofessors SET ProfessorName=:profname, ProfessorEmail=:profemail, Gender=:gender, Birthdate=:birthdate, status=:status WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':profname', $profName, PDO::PARAM_STR);
    $query->bindParam(':profemail', $profEmail, PDO::PARAM_STR);
    $query->bindParam(':gender', $gender, PDO::PARAM_STR);
    $query->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
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

        <h2>Edit Professor</h2>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-primary">
                    <i class="fas fa-home"></i>
                    <a href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Professors</li>
                <li class="breadcrumb-item active" aria-current="page">Edit Professor</li>
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
        $id = $_GET['profid'];
        $sql1 = "SELECT * FROM tblprofessors WHERE id=:id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':id', $id, PDO::PARAM_STR);
        $query1->execute();
        if ($query1->rowCount() > 0) {
            $result1 = $query1->fetch(PDO::FETCH_OBJ); ?>

            <form method="POST">
                <input type="hidden" name="id" value="<?php echo htmlentities($result1->id) ?>">
                <div class="form-group">
                    <label for="profName">Name</label>
                    <input type="text" name="profName" class="form-control" id="profName" placeholder="John Michaels" value="<?php echo htmlentities($result1->ProfessorName); ?>">
                </div>

                <div class="form-group">
                    <label for="profEmail">Email</label>
                    <input type="email" name="profEmail" class="form-control" id="profEmail" placeholder="johnmichaels@gmail.com" value="<?php echo htmlentities($result1->ProfessorEmail); ?>">
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
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="1" <?php if ($result1->Status == 1) echo "selected"; ?>>Active</option>
                        <option value="0" <?php if ($result1->Status == 0) echo "selected"; ?>>Inactive</option>
                    </select>
                </div>

                <button type="submit" name="editProfessor" class="btn btn-primary">Submit</button>

            </form>
        <?php
        } else {
            $error = "Cannot find professor with id=$id";
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
    document.getElementById("professors").setAttribute("class", "active")
    document.getElementById("profSubmenu").classList.toggle("show");
</script>

<?php include "footer.php";
?>