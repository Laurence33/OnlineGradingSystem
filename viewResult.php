<?php
include 'header.php';

include 'includes/config.php';

if (isset($_POST['viewResults'])) {
    $studentId = $_POST['studentId'];
    $birthdate = $_POST['birthdate'];

    $studentSql = "SELECT * FROM tblstudents WHERE StudentId = :studid";
    $studentQuery = $dbh->prepare($studentSql);
    $studentQuery->bindParam(':studid', $studentId);
    $studentQuery->execute();
    $student = $studentQuery->fetch(PDO::FETCH_OBJ);

    $classSql = "SELECT * FROM tblclasses WHERE id=:classid";
    $classQuery = $dbh->prepare($classSql);
    $classQuery->bindParam(':classid', $student->ClassId);
    $classQuery->execute();
    $class = $classQuery->fetch(PDO::FETCH_OBJ);

    $advisingSql = "SELECT * FROM tblsubjectadvising WHERE ClassId=:classid";
    $advisingQuery = $dbh->prepare($advisingSql);
    $advisingQuery->bindParam(':classid', $class->id);
    $advisingQuery->execute();
    $advisings = $advisingQuery->fetchAll(PDO::FETCH_OBJ);
?>

    <div class="wrapper">
        <!-- Page Content  -->
        <div id="content" class="container">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container text-center">

                    <h2> Online Grading System</h2>

                </div>
            </nav>
            <div class="container text-center">
                <h3>Student Results</h3>
            </div>
            <table class="table table-sm">
                <tbody>
                    <tr class="row">
                        <th scope="row" class="col-1">Name: </th>
                        <td class="col-11"><?php echo htmlentities($student->StudentName); ?></td>
                    </tr>
                    <tr class="row">
                        <th scope="row" class="col-1">Class: </th>
                        <td class="col-11"><?php echo htmlentities($class->ClassName); ?></td>
                    </tr>
                </tbody>
            </table>
            <br>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Result</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $cnt = 1;
                    foreach ($advisings as $advising) {
                        $subjectSql = "SELECT * FROM tblsubjects WHERE id=:subjectid";
                        $subjectQuery = $dbh->prepare($subjectSql);
                        $subjectQuery->bindParam('subjectid', $advising->SubjectId);
                        $subjectQuery->execute();
                        $subject = $subjectQuery->fetch(PDO::FETCH_OBJ);

                        $gradesSql = "SELECT * FROM tblgrades WHERE StudentId = :studid AND AdvisingId = :advisingid";
                        $gradesQuery = $dbh->prepare($gradesSql);
                        $gradesQuery->bindParam(':studid', $student->id);
                        $gradesQuery->bindParam(':advisingid', $advising->id);
                        $res = $gradesQuery->execute();
                        $result = $gradesQuery->fetch(PDO::FETCH_OBJ);
                    ?>
                        <tr>
                            <th scope="row"><?php echo $cnt; ?></th>
                            <td><?php echo $subject->SubjectCode . ' ' . $subject->SubjectName; ?></td>
                            <td><?php if ($result->Result) echo $result->Result;
                                else echo "Not Declared"; ?></td>
                        </tr>
                    <?php $cnt += 1;
                    } ?>
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Result</th>
                        </tr>
                    </thead>
                </tbody>
            </table>

        </div>
        <!-- Closing div for .content -->
    </div>
    <!-- closing div for .wrapper -->

<?php
}

include 'footer.php'; ?>