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
    $track = $_POST['track'];
    $strand = $_POST['strand'];
    $level = $_POST['level'];
    $status = $_POST['status'];
    $id = $_POST['id'];

    $sql = "UPDATE tblclasses SET ClassName=:classname, Track=:track, Strand=:strand, Level=:level, Status=:status WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':classname', $className, PDO::PARAM_STR);
    $query->bindParam(':track', $track, PDO::PARAM_STR);
    $query->bindParam(':strand', $strand, PDO::PARAM_STR);
    $query->bindParam(':level', $level, PDO::PARAM_STR);
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
                    <label for="track">Track</label>
                    <select class="form-control" onchange="loadStrands()" name="track" id="track">
                        <option value="Academic Track" <?php if ($result1->Track == "Academic Track") echo 'selected'; ?>>Academic Track</option>
                        <option value="Technical-Vocational-Livelihood Track" <?php if ($result1->Track == "Technical-Vocational-Livelihood Track") echo 'selected'; ?>>TVL Track</option>
                    </select>
                </div>
                <?php if ($result1->Track == "Academic Track") { ?>
                    <div class="form-group">
                        <label for="strand">Strand</label>
                        <select class="form-control" name="strand" id="strand">
                            <option value="Science Technology and Mathematics" <?php if ($result1->Strand == "Science Technology and Mathematics") echo 'selected'; ?>>STEM</option>
                            <option value="Accountancy, Business, Management" <?php if ($result1->Strand == "Accountancy, Business, Management") echo 'selected'; ?>>ABM</option>
                            <option value="Humanities and Social Sciences" <?php if ($result1->Strand == "Humanities and Social Sciences") echo 'selected'; ?>>HUMSS</option>
                        </select>
                    </div>
                <?php
                } else {
                ?>
                    <div class="form-group">
                        <label for="strand">Strand</label>
                        <select class="form-control" name="strand" id="strand">
                            <option value="Bread and Pastry" <?php if ($result1->Strand == "Bread and Pastry") echo 'selected'; ?>>Bread and Pastry</option>
                        </select>
                    </div>
                <?php
                }
                ?>

                <div class="form-group">
                    <label for="level">Grade Level</label>
                    <select class="form-control" name="level" id="level">
                        <option value="11" <?php if ($result1->Level == 11) echo 'selected' ?>>11</option>
                        <option value="12" <?php if ($result1->Level == 12) echo 'selected' ?>>12</option>
                    </select>
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
    function loadStrands() {
        const track = document.getElementById('track');
        const strand = document.getElementById('strand');
        if (track.value == "Academic Track") {
            strand.innerHTML = "";
            var option = document.createElement("option");
            option.value = "Science Technology and Mathematics";
            option.text = "STEM";
            strand.add(option);
            option = document.createElement("option");
            option.value = "Accountancy, Business, Management";
            option.text = "ABM";
            strand.add(option);
            option = document.createElement("option");
            option.value = "Humanities and Social Sciences";
            option.text = "HUMSS";
            strand.add(option);
        } else if (track.value == "Technical-Vocational-Livelihood Track") {
            strand.innerHTML = "";
            var option = document.createElement("option");
            option.value = "Bread and Pastry";
            option.text = "Bread and Pastry";
            strand.add(option);
        }
    }
    document.getElementById("classes").setAttribute("class", "active")
    document.getElementById("classesSubmenu").classList.toggle("show");
</script>

<?php include "footer.php";
?>