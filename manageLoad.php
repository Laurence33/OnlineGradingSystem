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
$loadSubjectId = $advising->SubjectId;
$loadClassId = $advising->ClassId;

$sql = "SELECT * FROM tblsubjects WHERE id=:subjectid";
$query = $dbh->prepare($sql);
$query->bindParam(':subjectid', $loadSubjectId, PDO::PARAM_STR);
$query->execute();
$subject = $query->fetch(PDO::FETCH_OBJ);

$sql = "SELECT * FROM tblclasses WHERE id=:classid";
$query = $dbh->prepare($sql);
$query->bindParam(':classid', $loadClassId, PDO::PARAM_STR);
$query->execute();
$class = $query->fetch(PDO::FETCH_OBJ);

if (isset($_POST['declareResult'])) {
    $studentId = $_POST['studentId'];
    $advisingId = $_POST['advisingId'];
    $declareResult = $_POST['result'];

    $sql0 = "INSERT INTO tblgrades(StudentId, AdvisingId, Result) VALUES(:studentid, :advisingid, :result)";
    $query0 = $dbh->prepare($sql0);
    $query0->bindParam(':studentid', $studentId);
    $query0->bindParam(':advisingid', $advisingId);
    $query0->bindParam(':result', $declareResult);
    $query0->execute();
    $lastInsertId0 = $dbh->lastInsertId();
    if ($lastInsertId0) {
        $msg = "Result Submitted successfully";
    } else {
        $error = "Something went wrong. Please try again";
    }
} else if (isset($_POST['editResult'])) {
    $gradeId = $_POST['gradeId'];
    $declareResult = $_POST['result'];

    $sql0 = "UPDATE tblgrades SET Result=:result WHERE id=:id";
    $query0 = $dbh->prepare($sql0);
    $query0->bindParam(':id', $gradeId);
    $query0->bindParam(':result', $declareResult);
    $res = $query0->execute();
    if ($res) {
        $msg = "Result Updated successfully.";
    } else {
        $error = "Something went wrong. Please try again";
    }
}


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

        <h2>Manage Load - <?php echo htmlentities($class->ClassName) . "-" . htmlentities($subject->SubjectName) ?></h2>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-primary">
                    <i class="fas fa-home"></i>
                    <a href="dashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item"><?php echo htmlentities($class->ClassName) . "-" . htmlentities($subject->SubjectName) ?></li>
                <li class="breadcrumb-item active" aria-current="page">Manage Grades</li>
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

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Result</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $sql = "SELECT * FROM  tblstudents WHERE ClassId=:classid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':classid', $loadClassId, PDO::PARAM_STR);
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
                        <tr>
                            <th scope="row"><?php echo $cnt ?></th>
                            <td><?php echo htmlentities($result->StudentName); ?></td>
                            <td><em><?php if ($result1->Result) echo htmlentities($result1->Result);
                                    else echo "Not Declared" ?></em></td>
                            <td>
                                <?php if ($result1->Result) { ?>
                                    <a href="#" data-toggle="modal" data-target="#exampleModal" onclick="editResult(<?php echo  $result1->id; ?>, '<?php echo $result->StudentName; ?>', <?php echo $result1->Result; ?>)">
                                        <i class="fa fa-edit" title="Edit Result"></i>
                                    </a>
                                <?php } else { ?>
                                    <a href="#" data-toggle="modal" data-target="#exampleModal" onclick="declareResult(<?php echo  $result->id; ?>, '<?php echo $result->StudentName; ?>', <?php echo $advisingId; ?>)">
                                        <i class="fa fa-plus-square" title="Declare Result"></i>
                                    </a>
                                <?php } ?>

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
                    <th scope="col">Result</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
        </table>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Declare Result</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST">
                    <div class="modal-body">

                        <input type="hidden" name="studentId">
                        <input type="hidden" name="advisingId">
                        <input type="hidden" name="gradeId">

                        <div class="form-group">
                            <label for="student">Student</label>
                            <input readonly type="text" name="studentName" class="form-control" id="student" placeholder="John Doe">
                        </div>

                        <div class="form-group">
                            <label for="result">Result</label>
                            <input required type="number" minVal="0" maxVal="100" name="result" class="form-control" id="result" placeholder="96">
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="action" name="declareResult" class="btn btn-primary">Declare Mark</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<!-- closing div for .wrapper -->
<script>
    document.getElementById("subjects").setAttribute("class", "active");
    document.getElementById("SubjectsSubmenu").classList.toggle("show");

    function editResult(gradeId, studentName, result) {
        $("input[name=gradeId]").val(gradeId);
        $("input[name=studentName]").val(studentName);
        $("input[name=result]").val(result);
        $("button[id=action]").text('Change Result');
        $("button[id=action]").attr("name", "editResult");
    }

    function declareResult(studentId, studentName, advisingId) {
        $("input[name=studentId]").val(studentId);
        $("input[name=studentName]").val(studentName);
        $("input[name=advisingId]").val(advisingId);
    }
</script>

<?php include "footer.php";
?>