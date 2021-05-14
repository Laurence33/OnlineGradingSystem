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

        <h2>Manage Professor Page</h2>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-primary">
                    <i class="fas fa-home"></i>
                    <a href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">Professors</li>
                <li class="breadcrumb-item active" aria-current="page">Manage Professors</li>
            </ol>
        </nav>

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Birthdate</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $sql = "SELECT * FROM  tblProfessors";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                if ($query->rowCount() > 0) {
                    foreach ($results as $result) {
                ?>
                        <tr>
                            <th scope="row"><?php echo $cnt ?></th>
                            <td><?php echo htmlentities($result->ProfessorName); ?></td>
                            <td><?php echo htmlentities($result->ProfessorEmail); ?></td>
                            <td><?php echo htmlentities($result->Gender); ?></td>
                            <td><?php echo htmlentities($result->Birthdate); ?></td>
                            <td><?php if ($result->Status) echo "Active";
                                else echo "Inactive"; ?></td>
                            <td>
                                <a href="editProfessor.php?profid=<?php echo htmlentities($result->id); ?>"><i class="fa fa-edit" title="Edit Record"></i> </a>
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
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Birthdate</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
        </table>

    </div>


</div>
<!-- closing div for .wrapper -->
<script>
    document.getElementById("professors").setAttribute("class", "active")
    document.getElementById("profSubmenu").classList.toggle("show");
</script>

<?php include "footer.php";
?>