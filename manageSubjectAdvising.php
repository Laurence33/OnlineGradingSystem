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

        <h2>Manage Subject Advising</h2>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-primary">
                    <i class="fas fa-home"></i>
                    <a href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Subjects</li>
                <li class="breadcrumb-item active" aria-current="page">Manage Subject Advising</li>
            </ol>
        </nav>

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Class Code</th>
                    <th scope="col">Class Name</th>
                    <th scope="col">Subject Name</th>
                    <th scope="col">Subject Type</th>
                    <th scope="col">Semester</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tblsubjectadvising";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                if ($query->rowCount() > 0) {
                    foreach ($results as $result) {
                        $subType = $result->SubjectType;
                        $sql1 = "SELECT * FROM  tblsubjects WHERE id=:subid";
                        $query1 = $dbh->prepare($sql1);
                        $query1->bindParam(':subid', $result->SubjectId, PDO::PARAM_STR);
                        $query1->execute();
                        $result1 = $query1->fetch(PDO::FETCH_OBJ);
                        $subName = $result1->SubjectName;

                        $sql2 = "SELECT * FROM  tblclasses WHERE id=:classid";
                        $query2 = $dbh->prepare($sql2);
                        $query2->bindParam(':classid', $result->ClassId, PDO::PARAM_STR);
                        $query2->execute();
                        $result2 = $query2->fetch(PDO::FETCH_OBJ);
                        $className = $result2->ClassName;
                ?>
                        <tr>
                            <th scope="row"><?php echo $cnt; ?></th>
                            <td><?php echo htmlentities($result->ClassCode); ?></td>
                            <td><?php echo htmlentities($className); ?></td>
                            <td><?php echo htmlentities($subName); ?></td>
                            <td><?php
                                if ($subType == 1) {
                                    echo 'Core Subject';
                                } else if ($subType == 2) {
                                    echo 'Work Immersion/Research/Business Enterprise Simulation(TVL only)/Exhibit/Performance';
                                } else if ($subType == 3) {
                                    echo 'Other Subject';
                                }
                                ?></td>
                            <td><?php echo htmlentities($result->Semester); ?></td>
                            <td><?php if ($result->Status) echo "Active";
                                else echo "Inactive"; ?></td>
                            <td>
                                <a href="editSubjectAdvising.php?advisingid=<?php echo htmlentities($result->id); ?>"><i class="fa fa-edit" title="Edit Record"></i> </a>
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
                    <th scope="col">Class Name</th>
                    <th scope="col">Subject Name</th>
                    <th scope="col">Subject Type</th>
                    <th scope="col">Semester</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
        </table>

    </div>


</div>
<!-- closing div for .wrapper -->
<script>
    document.getElementById("subjects").setAttribute("class", "active");
    document.getElementById("SubjectsSubmenu").classList.toggle("show");
</script>

<?php include "footer.php";
?>