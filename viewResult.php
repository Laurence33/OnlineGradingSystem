<?php
include 'header.php';

include 'includes/config.php';
session_start();

if (isset($_SESSION['slogin'])) {
    $studentId = $_SESSION['studentId'];

    $studentSql = "SELECT * FROM tblstudents WHERE id=:studid";
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
    <div class="container-fluid" style="background: url(./assets/img/bg.jpg);">

        <div class="wrapper">
            <!-- Page Content  -->
            <div id="content" class="container" style="background-color: #E9ECEF;">

                <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container text-center">

                        <h2> Online Grading System</h2>

                    </div>
                </nav> -->
                <div class="container text-center">
                    <h2>Student Results</h2>
                </div>
                <br>
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr class="row ">
                            <th scope="row" class="col-2  text-right">Name: </th>
                            <td class="col-4"><?php echo htmlentities($student->StudentName); ?></td>
                            <th scope="row" class="col-2  text-right">Student-ID: </th>
                            <td class="col-4"><?php echo htmlentities($student->StudentId); ?></td>
                        </tr>
                        <tr class="row">
                            <th scope="row" class="col-2  text-right">Track: </th>
                            <td class="col-4"><?php echo htmlentities($class->Track); ?></td>
                            <th scope="row" class="col-2  text-right">Grade: </th>
                            <td class="col-4"><?php echo htmlentities($class->Level); ?></td>
                        </tr>
                        <tr class="row">
                            <th scope="row" class="col-2  text-right">Strand: </th>
                            <td class="col-4"><?php echo htmlentities($class->Strand); ?></td>
                            <th scope="row" class="col-2  text-right">Class: </th>
                            <td class="col-4"><?php echo htmlentities($class->ClassName); ?></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table class="table table-light">
                    <thead>
                        <tr class="table-secondary">
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

                            $gradesSql = "SELECT * FROM tblgrades WHERE StudentId = :studid AND AdvisingId = :advisingid AND Quarter=5";
                            $gradesQuery = $dbh->prepare($gradesSql);
                            $gradesQuery->bindParam(':studid', $student->id);
                            $gradesQuery->bindParam(':advisingid', $advising->id);
                            $res = $gradesQuery->execute();
                            $result = $gradesQuery->fetch(PDO::FETCH_OBJ);
                        ?>
                            <tr class="<?php if ($result->Result && $result->Result < 75) echo 'table-danger'; ?>">
                                <th scope="row"><?php echo $cnt; ?></th>
                                <td><?php echo $subject->SubjectCode . ' ' . $subject->SubjectName; ?></td>
                                <td><?php if ($result->Result) echo $result->Result;
                                    else echo "Not Declared"; ?></td>
                            </tr>
                        <?php $cnt += 1;
                        } ?>
                        <thead>
                            <tr class="table-secondary">
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
    </div>

<?php
}

include 'footer.php'; ?>