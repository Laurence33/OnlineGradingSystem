<?php
session_start();
include "includes/config.php";

if (strlen($_SESSION['plogin']) == '') {
    header("Location: index.php");
}

// The professor is logged in and can access the page
$semester = 1;
$quarter = $_GET['quarter'];
$advisingId = $_GET['advisingid'];
$sql = "SELECT * FROM tblsubjectadvising WHERE id=:id";
$query = $dbh->prepare($sql);
$query->bindParam(':id', $advisingId, PDO::PARAM_STR);
$query->execute();
$advising = $query->fetch(PDO::FETCH_OBJ);
$loadSubjectId = $advising->SubjectId;
$loadClassId = $advising->ClassId;
$loadSubType = $advising->SubjectType;
$semester = $advising->Semester;
if ($semester == 2 && $quarter == 1) $quarter = 3;

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
$classTrack = $class->Track;

if (isset($_POST['addTask'])) {
    $quarter = $_POST['quarter'];
    $component = $_POST['component'];
    $taskNumber = $_POST['taskNumber'];
    $highestScore = $_POST['highestScore'];
    $advisingIdRec = $_POST['advisingId'];

    $sql0 = "INSERT INTO tblcomponenttasks(Quarter, Component, TaskNumber, HighestScore, AdvisingId) VALUES(:quarter, :component, :tasknumber, :highestscore, :advisingid)";
    $query0 = $dbh->prepare($sql0);
    $query0->bindParam(':quarter', $quarter);
    $query0->bindParam(':component', $component);
    $query0->bindParam(':tasknumber', $taskNumber);
    $query0->bindParam(':highestscore', $highestScore);
    $query0->bindParam(':advisingid', $advisingId);
    $query0->execute();
    $lastInsertId0 = $dbh->lastInsertId();
    if ($lastInsertId0) {
        $msg = "Task created successfully";
    } else {
        $error = "Something went wrong. Please try again";
    }
} else if (isset($_POST['declareResult'])) {

    $studentId = $_POST['studentId'];
    $taskId = $_POST['taskId'];
    $studentScore = $_POST['studentScore'];

    $sql0 = "INSERT INTO tblresults(TaskId, StudentId, Score) VALUES(:taskid, :studentid, :studscore)";
    $query0 = $dbh->prepare($sql0);
    $query0->bindParam(':taskid', $taskId);
    $query0->bindParam(':studentid', $studentId);
    $query0->bindParam(':studscore', $studentScore);
    $query0->execute();
    $lastInsertId0 = $dbh->lastInsertId();
    if ($lastInsertId0) {
        $msg = "Result Submitted successfully";
    } else {
        $error = "Something went wrong. Please try again";
    }
} else if (isset($_POST['editResult'])) {
    $resultId = $_POST['resultId'];
    $studentScore = $_POST['studentScore'];

    $sql0 = "UPDATE tblresults SET Score=:studscore WHERE id=:id";
    $query0 = $dbh->prepare($sql0);
    $query0->bindParam(':id', $resultId);
    $query0->bindParam(':studscore', $studentScore);
    $res = $query0->execute();
    if ($res) {
        $msg = "Result Updated successfully.";
    } else {
        $error = "Something went wrong. Please try again";
    }
} else if (isset($_POST['postGrade'])) {
    $pAdvisingId = $_POST['advisingId'];
    $studentId = $_POST['studentId'];
    $quarter = $_POST['quarter'];
    $result = $_POST['result'];

    $postGradeSQL = "INSERT INTO tblgrades(AdvisingId, StudentId, Quarter, Result) VALUES(:advisingid, :studentid, :quarter, :result)";
    $postGradeQuery = $dbh->prepare($postGradeSQL);
    $postGradeQuery->bindParam(':advisingid', $pAdvisingId, PDO::PARAM_STR);
    $postGradeQuery->bindParam(':studentid', $studentId, PDO::PARAM_STR);
    $postGradeQuery->bindParam(':quarter', $quarter, PDO::PARAM_STR);
    $postGradeQuery->bindParam(':result', $result, PDO::PARAM_STR);
    $postGradeQuery->execute();
    $pLastInsertId = $dbh->lastInsertId();
    if ($pLastInsertId) {
        $msg = "Grade calculated and posted.";
    } else {
        $error = "Something went wrong. Please try again";
    }
} else if (isset($_POST['repostGrade'])) {
    $gradeId = $_POST['gradeId'];
    $result = $_POST['result'];

    $repostSQL = "UPDATE tblgrades SET Result=:result WHERE id=:gradeid";
    $repostQuery = $dbh->prepare($repostSQL);
    $repostQuery->bindParam(':gradeid', $gradeId, PDO::PARAM_STR);
    $repostQuery->bindParam(':result', $result, PDO::PARAM_STR);
    $repostRes = $repostQuery->execute();
    if ($repostRes) {
        $msg = "Grade recalculated and posted";
    } else {
        $error = "Something went wrong, please try again.";
    }
} else if (isset($_POST['postFinalGrade'])) {
    $finalGrade = $_POST['finalGrade'];
    $studentId = $_POST['studentId'];
    $advisingId = $_POST['advisingId'];

    $sql = "INSERT INTO tblgrades(StudentId, AdvisingId, Quarter, Result) VALUES(:studentid, :advisingid, 5, :result)";
    $query = $dbh->prepare($sql);
    $query->bindParam(":studentid", $studentId, PDO::PARAM_STR);
    $query->bindParam(":advisingid", $advisingId, PDO::PARAM_STR);
    $query->bindParam(":result", $finalGrade, PDO::PARAM_STR);
    $query->execute();
    $success = $dbh->lastInsertId();
    if ($success) {
        $msg = "Final Grade Posted";
    } else {
        $error = "Something went wrong, please try again.";
    }
} else if (isset($_POST['repostFinalGrade'])) {
    $gradeId = $_POST['gradeId'];
    $finalGrade = $_POST['finalGrade'];

    $sql = "UPDATE tblgrades SET Result=:finalgrade WHERE id=:gradeid && Quarter=5";
    $query = $dbh->prepare($sql);
    $query->bindParam(":finalgrade", $finalGrade, PDO::PARAM_STR);
    $query->bindParam(":gradeid", $gradeId, PDO::PARAM_STR);
    $res = $query->execute();
    if ($res) {
        $msg = "Updated final grade posted.";
    } else {
        $error = "Something went wrong, please try again";
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
                <li class="breadcrumb-item">Manage Load</li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlentities($class->ClassName) . "-" . htmlentities($subject->SubjectName) ?></li>
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
        <p class="text-dark"><b>Grade and Section:</b> <?php echo $class->Level . "-" . $class->ClassName ?></p>
        <p class="text-dark"><b>Subject and Type:</b> <?php echo $subject->SubjectName . "(";
                                                        if ($advising->SubjectType == 1) echo  "Core Subject)";
                                                        else if ($advising->SubjectType == 2) echo "Specialization";
                                                        else if ($advising->SubjectType == 3) echo "Minor Subject"; ?></p>
        <p class="text-dark"><b>Track:</b> <?php echo $class->Track ?></p>
        <!-- Add Task Button -->
        <div class="container-fluid">
            <div class="row ">
                <div class="col d-flex flex-row-reverse">
                    <button type="button" class="btn btn-primary margin" data-toggle="modal" data-target="#addTaskModal">Add Task</button>
                </div>
            </div>
        </div>
        <br>
        <!-- Quarter/Semester  Navigation-->
        <div class="d-flex justify-content-between">
            <nav aria-label="Quarter">
                <ul class="pagination" color="secondary">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Quarter:</a>
                    </li>
                    <?php
                    if ($advising->Semester == 1) { ?>
                        <li class="page-item <?php if ($quarter == 1) echo 'active'; ?>"><a class="page-link" href="manageLoad.php?advisingid=<?php echo $advisingId; ?>&quarter=1">1</a></li>
                        <li class="page-item <?php if ($quarter == 2) echo 'active'; ?>" aria-current="page">
                            <a class="page-link" href="manageLoad.php?advisingid=<?php echo $advisingId; ?>&quarter=2">2</a>
                        </li>
                    <?php } else { ?>
                        <li class="page-item <?php if ($quarter == 3) echo 'active' ?>"><a class="page-link" href="manageLoad.php?advisingid=<?php echo $advisingId; ?>&quarter=3">3</a></li>
                        <li class="page-item <?php if ($quarter == 4) echo 'active' ?>">
                            <a class="page-link" href="manageLoad.php?advisingid=<?php echo $advisingId; ?>&quarter=4">4</a>
                        </li>
                    <?php } ?>
                    <li class="page-item <?php if ($quarter == 'final') echo 'active' ?>"><a class="page-link" href="manageLoad.php?advisingid=<?php echo $advisingId ?>&quarter=final">Semestral Grades</a></li>
                </ul>
            </nav>
        </div>
        <?php
        if ($quarter >= 1 && $quarter <= 4) {
            include "./includes/quarterTable.php";
        } else if ($quarter == 'final') {
            include "./includes/semestralTable.php";
        }
        ?>

    </div>

    <!-- Declare Result Modal -->
    <div class="modal fade" id="declareResultModal" tabindex="-1" role="dialog" aria-labelledby="declareResultModal" aria-hidden="true">
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
                        <input type="hidden" name="taskId">
                        <input type="hidden" name="resultId">

                        <div class="form-group">
                            <label for="student">Student</label>
                            <input readonly type="text" name="studentName" class="form-control" id="student">
                        </div>

                        <div class="form-group">
                            <label for="component">Component</label>
                            <input readonly type="text" name="component" class="form-control" id="component">
                        </div>

                        <div class="form-group">
                            <label for="taskNumber">Task Number</label>
                            <input readonly type="number" name="taskNumber" class="form-control" id="taskNumber">
                        </div>
                        <div class="form-group">
                            <label for="highestScore">Highest Possible Score</label>
                            <input readonly type="number" name="highestScore" class="form-control" id="highestScore">
                        </div>
                        <div class="form-group">
                            <label for="studentScore">Student Score</label>
                            <input type="number" min="0" name="studentScore" class="form-control" id="studentScore">
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="resultAction" name="declareResult" class="btn btn-primary">Declare Mark</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">Add Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST">
                    <div class="modal-body">

                        <input type="hidden" name="advisingId" value="<?php echo $advisingId ?>">

                        <div class="form-group">
                            <label for="quarterSelect">Quarter</label>
                            <input type="text" class="form-control" readonly name="quarter" id="quarter" value="<?php echo $quarter; ?>">
                        </div>

                        <div class="form-group">
                            <label for="componentSelect">Component</label>
                            <select class="form-control" id="componentSelect" name="component">
                                <option value="Written Work">Written Work</option>
                                <option value="Performance Task">Performance Task</option>
                                <option value="Quarterly Assessment">Quarterly Assessment</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="taskNumber">Task Number</label>
                            <input type="number" min="1" max="10" name="taskNumber" class="form-control" id="taskNumber">
                        </div>
                        <div class="form-group">
                            <label for="highestScore">Highest Possible Score</label>
                            <input type="number" min="1" max="9999" name="highestScore" class="form-control" id="highestScore">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="action" name="addTask" class="btn btn-primary">Add Task</button>
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

    function editResult(resultId, studentScore, studentName, taskComponent, taskNumber, taskHighestScore) {
        $("input[name=resultId]").val(resultId);
        $("input[name=studentScore]").val(studentScore);
        $("input[name=studentName]").val(studentName);
        $("input[name=component]").val(taskComponent);
        $("input[name=taskNumber]").val(taskNumber);
        $("input[name=highestScore]").val(taskHighestScore);
        $("button[id=resultAction]").text('Change Result');
        $("button[id=resultAction]").attr("name", "editResult");
        $("input[name=studentScore]").attr("max", taskHighestScore);
    }

    function declareResult(taskId, studentId, studentName, taskComponent, taskNumber, taskHighestScore) {
        console.log("Called");
        $("input[name=taskId]").val(taskId);
        $("input[name=studentId]").val(studentId);
        $("input[name=studentName]").val(studentName);
        $("input[name=component]").val(taskComponent);
        $("input[name=taskNumber]").val(taskNumber);
        $("input[name=highestScore]").val(taskHighestScore);
        $("input[name=studentScore]").attr("max", taskHighestScore);
    }
</script>

<?php include "footer.php";
?>