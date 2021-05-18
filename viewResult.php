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

    $gradesSql = "SELECT * FROM tblgrades WHERE StudentId = :studid";
    $gradesQuery = $dbh->prepare($gradesSql);
    $gradesQuery->bindParam(':studid', $student->id);
    $gradesQuery->execute();
    $grades = $gradesQuery->fetchAll(PDO::FETCH_OBJ);

    $classSql = "SELECT * FROM tblclasses WHERE id=:classid";
    $classQuery = $dbh->prepare($classSql);
    $classQuery->bindParam(':classid', $student->ClassId);
    $classQuery->execute();
    $class = $classQuery->fetch(PDO::FETCH_OBJ);
?>

    <div class="wrapper">
        <!-- Page Content  -->
        <div id="content" class="container">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container text-center">

                    <h2> Online Grading System</h2>

                </div>
            </nav>

            <table class="table">
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
                    foreach ($grades as $result) {
                        $advisingId = $result->AdvisingId;
                        $advisingSql = "SELECT * FROM tblsubjectadvising WHERE id=:advisingid";
                        $advisingQuery = $dbh->prepare($advisingSql);
                        $advisingQuery->bindParam(':advisingid', $advisingId);
                        $advisingQuery->execute();
                        $advising = $advisingQuery->fetch(PDO::FETCH_OBJ);
                        $subjectId = $advising->SubjectId;

                        $subjectSql = "SELECT * FROM tblsubjects WHERE id=:subjectid";
                        $subjectQuery = $dbh->prepare($subjectSql);
                        $subjectQuery->bindParam('subjectid', $subjectId);
                        $subjectQuery->execute();
                        $subject = $subjectQuery->fetch(PDO::FETCH_OBJ);
                    ?>
                        <tr>
                            <th scope="row"><?php echo $cnt; ?></th>
                            <td><?php echo $subject->SubjectCode . ' ' . $subject->SubjectName; ?></td>
                            <td><?php echo $result->Result; ?></td>
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