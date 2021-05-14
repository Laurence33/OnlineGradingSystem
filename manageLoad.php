<?php
session_start();
include "includes/config.php";

if (strlen($_SESSION['plogin']) == '') {
    header("Location: index.php");
}

// The professor is logged in and can access the page

$advisingId = $_GET['advisingid'];
$sql = "SELECT * FROM tblsubjectadvising WHERE id=:id";
$query = $dbh->prepare($sql);
$query->bindParam(':id', $advisingId, PDO::PARAM_STR);
$query->execute();
$advising = $query->fetch(PDO::FETCH_OBJ);
$subjectId = $advising->SubjectId;
$classId = $advising->ClassId;

$sql = "SELECT * FROM tblsubjects WHERE id=:subjectid";
$query = $dbh->prepare($sql);
$query->bindParam(':subjectid', $subjectId, PDO::PARAM_STR);
$query->execute();
$subject = $query->fetch(PDO::FETCH_OBJ);

$sql = "SELECT * FROM tblclasses WHERE id=:classid";
$query = $dbh->prepare($sql);
$query->bindParam(':classid', $classId, PDO::PARAM_STR);
$query->execute();
$class = $query->fetch(PDO::FETCH_OBJ);


include "header.php";
?>

<div class="wrapper">
    <?php include "includes/prof-sidenav.php"; ?>

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

        <h2>Manage Load - <?php echo htmlentities($class->ClassName)."-".htmlentities($subject->SubjectName) ?></h2>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-primary">
                    <i class="fas fa-home"></i>
                    <a href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item"><?php echo htmlentities($class->ClassName)."-".htmlentities($subject->SubjectName) ?></li>
                <li class="breadcrumb-item active" aria-current="page">Manage Grades</li>
            </ol>
        </nav>

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Remark</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $sql = "SELECT * FROM  tblstudents WHERE ClassId=:classid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':classid', $classId, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                if ($query->rowCount() > 0) {
                    foreach ($results as $result) {
                        $studentId = $result->id;
                        $sql1 = "SELECT * FROM  tblgrades WHERE StudentId=:studid AND AdvisingId=:advisingid";
                        $query1 = $dbh->prepare($sql1);
                        $query1->bindParam(':studid', $result->id, PDO::PARAM_STR);
                        $query1->bindParam(':advisingid', $advisingId, PDO::PARAM_STR);
                        $query1->execute();
                        $result1 = $query1->fetch(PDO::FETCH_OBJ);
                ?>
                        <tr data-toggle="modal" data-target="#declareRemark" class="pointer"
            onclick="getStudentDetails(<?php echo $result['id']?>, '<?php echo $result['StudentName']?>');">
                            <th scope="row"><?php echo $cnt ?></th>
                            <td><?php echo htmlentities($result->StudentName); ?></td>
                            <td><em><?php if($result1->Remark) echo htmlentities($result1->Remark); else echo "Not Declared" ?></em></td>
                            <td>
                                <a href="editProfessor.php?profid=<?php echo htmlentities($result->id); ?>"><i class="fa fa-edit" title="Declare Remark"></i> </a>
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
                    <th scope="col">Remark</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
        </table>

    </div>

    <!-- Declare Remark Modal -->
<form method="POST" id="edit-form">
    <div class="modal fade" id="declareRemark" tabindex="-1" role="dialog" aria-labelledby="Declare Remark" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Declare Remark</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="id">
            <div class="row">
                <div class="input-group mb-3">
                    <div class="col col-md-5">
                    <input type="text" class="form-control" placeholder="John Doe" name="studentName">
                    </div>
                    <div class="col col-md-5">
                    <input type="number" min="0" max="100" class="form-control" placeholder="98" name="remark">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="edit-submit-button" name="declareRemark" class="btn btn-primary">Declare</button>
            </div>
        </div>
    </div>
    </div>
</form>

<script>

    function getStudentDetails(id, studentname) {
        $('input[name=id]').val(id);
        $("#hiddenid").val(id);
        $("#editSelectStatus").empty();
    }
    
    </script>


</div>
<!-- closing div for .wrapper -->
<script>
    document.getElementById("professors").setAttribute("class", "active")
    document.getElementById("profSubmenu").classList.toggle("show");
</script>

<?php include "footer.php";
?>