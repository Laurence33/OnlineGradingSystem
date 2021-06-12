<?php

session_start();
include "includes/config.php";

if (strlen($_SESSION['alogin']) == '') {
    header("Location: index.php");
} else {
    if (isset($_POST['addClass'])) {
        $className = $_POST['className'];
        $track = $_POST['track'];
        $strand = $_POST['strand'];
        $level = $_POST['level'];
        $status = 1;

        $sql = "INSERT INTO  tblclasses(ClassName,Track,Strand,Level,Status) VALUES(:classname,:track, :strand,:level,:status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':classname', $className, PDO::PARAM_STR);
        $query->bindParam(':track', $track, PDO::PARAM_STR);
        $query->bindParam(':strand', $strand, PDO::PARAM_STR);
        $query->bindParam(':level', $level, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if ($lastInsertId) {
            $msg = "Class Created successfully";
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

        <h2>Add Class</h2>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-primary">
                    <i class="fas fa-home"></i>
                    <a href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Classes</li>
                <li class="breadcrumb-item active" aria-current="page">Add Class</li>
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
                <label for="className">Class Name</label>
                <input type="text" name="className" class="form-control" id="className" placeholder="Emerald">
            </div>

            <div class="form-group">
                <label for="track">Track</label>
                <select class="form-control" onchange="loadStrands()" name="track" id="track">
                    <option value="Academic Track">Academic Track</option>
                    <option value="Technical-Vocational-Livelihood Track">TVL Track</option>
                </select>
            </div>

            <div class="form-group">
                <label for="strand">Strand</label>
                <select class="form-control" name="strand" id="strand">
                    <option value="Science Technology and Mathematics">STEM</option>
                    <option value="Accountancy, Business, Management">ABM</option>
                    <option value="Humanities and Social Sciences">HUMSS</option>
                </select>
            </div>

            <div class="form-group">
                <label for="level">Grade Level</label>
                <select class="form-control" name="level" id="level">
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
            </div>

            <button type="submit" name="addClass" class="btn btn-primary">Submit</button>

        </form>

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