<?php
session_start();
error_reporting(0);
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

        <h2>Manage Subject Loading</h2>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-primary">
                    <i class="fas fa-home"></i>
                    <a href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Subjects</li>
                <li class="breadcrumb-item active" aria-current="page">Manage Subject Loading</li>
            </ol>
        </nav>

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Class Code</th>
                    <th scope="col">Professor Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tblsubjectloading";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                if ($query->rowCount() > 0) {
                    foreach ($results as $result) {
                        $sql1 = "SELECT * FROM  tblsubjectadvising WHERE id=:subadvisingid";
                        $query1 = $dbh->prepare($sql1);
                        $query1->bindParam(':subadvisingid', $result->AdvisingId, PDO::PARAM_STR);
                        $query1->execute();
                        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
                        foreach ($results1 as $result1) {
                            $classCode = $result1->ClassCode;
                            break;
                        }

                        $sql2 = "SELECT * FROM  tblprofessors WHERE id=:profid";
                        $query2 = $dbh->prepare($sql2);
                        $query2->bindParam(':profid', $result->ProfessorId, PDO::PARAM_STR);
                        $query2->execute();
                        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
                        foreach ($results2 as $result2) {
                            $profName = $result2->ProfessorName;
                            break;
                        }

                ?>
                        <tr>
                            <th scope="row"><?php echo $cnt; ?></th>
                            <td><?php echo htmlentities($classCode); ?></td>f
                            <td><?php echo htmlentities($profName); ?></td>
                            <td><?php if ($result->Status) echo "Active";
                                else echo "Inactive"; ?></td>
                            <td>
                                <a href="editSubjectLoading.php?loadingid=<?php echo htmlentities($result->id); ?>"><i class="fa fa-edit" title="Edit Record"></i> </a>
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
                    <th scope="col">Class Code</th>
                    <th scope="col">Professor Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
        </table>

    </div>


</div>
<!-- closing div for .wrapper -->
<script>
    document.getElementById("subjects").setAttribute("class", "active")
    document.getElementById("SubjectsSubmenu").classList.toggle("show");
</script>

<?php include "footer.php";
?>