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
                            <th scope="col">1st/3rd Quarter</th>
                            <th scope="col">2nd/4th Quarter</th>
                            <th scope="col">Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        <thead>
                            <tr class="table-secondary">
                                <th colspan="5">First Semester</th>
                            </tr>
                        </thead>
                        <?php
                        $cnt = 1;
                        foreach ($advisings as $advising) {
                            if ($advising->Semester == 2) continue;
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
                            $gradeResults = $gradesQuery->fetchAll(PDO::FETCH_OBJ);
                            foreach ($gradeResults as $result) {

                                $quarter1SQL = "SELECT * FROM tblgrades WHERE StudentId = $student->id AND AdvisingId = $advising->id AND Quarter = 1";
                                $quarter1Query = $dbh->prepare($quarter1SQL);
                                $res = $quarter1Query->execute();
                                if ($res) {
                                    $quarter1Res = $quarter1Query->fetch(PDO::FETCH_OBJ);
                                }
                                $quarter2SQL = "SELECT * FROM tblgrades WHERE StudentId = $student->id AND AdvisingId = $advising->id AND Quarter = 2";
                                $quarter2Query = $dbh->prepare($quarter2SQL);
                                $res = $quarter2Query->execute();
                                if ($res) {
                                    $quarter2Res = $quarter2Query->fetch(PDO::FETCH_OBJ);
                                }

                        ?>
                                <tr>
                                    <th scope="row"><?php echo $cnt; ?></th>
                                    <td><?php echo $subject->SubjectCode . ' ' . $subject->SubjectName; ?></td>
                                    <td><?php if ($quarter1Res->Result) echo $quarter1Res->Result;
                                        else echo 'Not Declared';  ?></td>
                                    <td><?php if ($quarter2Res->Result) echo $quarter2Res->Result;
                                        else echo 'Not Declared';  ?></td>
                                    <td><?php if ($result->Result) echo $result->Result;
                                        else echo "Not Declared"; ?></td>
                                </tr>
                        <?php
                                $cnt += 1;
                            }
                        }
                        ?>
                        <thead>
                            <tr class="table-secondary">
                                <th colspan="5">Second Semester</th>
                            </tr>
                        </thead>
                        <?php
                        $cnt = 1;
                        foreach ($advisings as $advising) {
                            if ($advising->Semester == 1) continue;
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
                            $gradeResults = $gradesQuery->fetchAll(PDO::FETCH_OBJ);
                            foreach ($gradeResults as $result) {

                                $quarter1SQL = "SELECT * FROM tblgrades WHERE StudentId = $student->id AND AdvisingId = $advising->id AND Quarter = 3";
                                $quarter1Query = $dbh->prepare($quarter1SQL);
                                $res = $quarter1Query->execute();
                                if ($res) {
                                    $quarter1Res = $quarter1Query->fetch(PDO::FETCH_OBJ);
                                }
                                $quarter2SQL = "SELECT * FROM tblgrades WHERE StudentId = $student->id AND AdvisingId = $advising->id AND Quarter = 4";
                                $quarter2Query = $dbh->prepare($quarter2SQL);
                                $res = $quarter2Query->execute();
                                if ($res) {
                                    $quarter2Res = $quarter2Query->fetch(PDO::FETCH_OBJ);
                                }

                        ?>
                                <tr>
                                    <th scope="row"><?php echo $cnt; ?></th>
                                    <td><?php echo $subject->SubjectCode . ' ' . $subject->SubjectName; ?></td>
                                    <td><?php if ($quarter1Res->Result) echo $quarter1Res->Result;
                                        else echo 'Not Declared';  ?></td>
                                    <td><?php if ($quarter2Res->Result) echo $quarter2Res->Result;
                                        else echo 'Not Declared';  ?></td>
                                    <td><?php if ($result->Result) echo $result->Result;
                                        else echo "Not Declared"; ?></td>
                                </tr>
                        <?php
                                $cnt += 1;
                            }
                        }
                        ?>
                        <!-- <thead>
                            <tr class="table-secondary">
                                <th scope="col">#</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Result</th>
                            </tr>
                        </thead> -->
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