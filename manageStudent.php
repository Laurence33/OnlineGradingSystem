<?php
session_start();
include "includes/config.php";

if (strlen($_SESSION['alogin']) == '') {
    header("Location: index.php");
}

// The admin is logged in and can access the page


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

        <h2>Manage Student Page</h2>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-primary">
                    <i class="fas fa-home"></i>
                    <a href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Students</li>
                <li class="breadcrumb-item active" aria-current="page">Manage Student</li>
            </ol>
        </nav>

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Student ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Birthdate</th>
                    <th scope="col">Class</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $sql = "SELECT * FROM  tblstudents";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                if ($query->rowCount() > 0) {
                    foreach ($results as $result) {
                ?>
                        <tr>
                            <th scope="row"><?php echo $cnt; ?></th>
                            <td><?php echo htmlentities($result->StudentId); ?></td>
                            <td><?php echo htmlentities($result->StudentName); ?></td>
                            <td><?php echo htmlentities($result->StudentEmail); ?></td>
                            <td><?php echo htmlentities($result->Gender); ?></td>
                            <td><?php echo htmlentities($result->Birthdate); ?></td>
                            <?php
                            $sql1 = "SELECT * FROM  tblclasses WHERE id=:classid";
                            $query1 = $dbh->prepare($sql1);
                            $query1->bindParam(':classid', $result->ClassId, PDO::PARAM_STR);
                            $query1->execute();
                            $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
                            foreach ($results1 as $result1) {
                            ?>
                                <td><?php echo htmlentities($result1->ClassName); ?></td>
                            <?php
                                break;
                            } ?>
                            <td><?php if ($result->Status) echo "Active";
                                else echo "Inactive"; ?></td>
                            <td>
                                <a href="editStudent.php?studid=<?php echo htmlentities($result->id); ?>"><i class="fa fa-edit" title="Edit Record"></i> </a>
                            </td>
                        </tr>
                <?php
                        $cnt += 1;
                    }
                }
                ?>
            </tbody>
            <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Student ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Birthdate</th>
                    <th scope="col">Class</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
        </table>

    </div>


</div>
<!-- closing div for .wrapper -->
<script>
    document.getElementById("students").setAttribute("class", "active")
    document.getElementById("studSubmenu").classList.toggle("show");
</script>

<?php include "footer.php";
?>