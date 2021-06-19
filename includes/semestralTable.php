<table class="table table-sm table-light">
    <thead class="thead-light">
        <tr class="table-secondary">
            <th scope="col">#</th>
            <th scope="col">Learner's Name</th>
            <?php if ($advising->Semester == 1) { ?>
                <th>First Quarter</th>
                <th>Second Quarter</th>
                <th>First Semester Grade</th>
            <?php
            } else { ?>
                <th>Third Quarter</th>
                <th>Fourth Quarter</th>
                <th>Second Semester Grade</th>
            <?php
            } ?>
            <th>Remark</th>

        </tr>
        <?php
        //Get Learners
        $learnersSQL = "SELECT * FROM tblstudents WHERE ClassId=:advisingid";
        $learnersQuery = $dbh->prepare($learnersSQL);
        $learnersQuery->bindParam(':advisingid', $advising->ClassId);
        $learnersQuery->execute();
        $learners = $learnersQuery->fetchAll(PDO::FETCH_OBJ);
        $learnerCount = 1;
        if ($learnersQuery->rowCount() > 0) {
            foreach ($learners as $learner) {
                //get Grades for the learner
                $learnerGradeSQL = "SELECT * from tblgrades WHERE StudentId=:studentid AND AdvisingId=:advisingid";
                $learnerGradeQuery = $dbh->prepare($learnerGradeSQL);
                $learnerGradeQuery->bindParam(':advisingid', $advising->id);
                $learnerGradeQuery->bindParam(':studentid', $learner->id);
                $learnerGradeQuery->execute();
                $learnerGrades = $learnerGradeQuery->fetchAll(PDO::FETCH_OBJ);
        ?>
                <tr>
                    <td><?php echo $learnerCount++; ?></td>
                    <td><?php echo $learner->StudentName; ?></td>
                    <?php
                    // print out Grades for the selected Quarter
                    $semGradeCount = 0;
                    $semesterGrade = 0;
                    foreach ($learnerGrades as $learnerGrade) {
                        if ($advising->Semester == 1) {
                            if ($learnerGrade->Quarter == 1) {
                                echo "<td  >$learnerGrade->Result</td>";
                                $semGradeCount++;
                                $semesterGrade += $learnerGrade->Result;
                            } else if ($learnerGrade->Quarter == 2) {
                                echo "<td  >$learnerGrade->Result</td>";
                                $semGradeCount++;
                                $semesterGrade += $learnerGrade->Result;
                            }
                        } else if ($advising->Semester == 2) {
                            if ($learnerGrade->Quarter == 3) {
                                echo "<td  >$learnerGrade->Result</td>";
                                $semGradeCount++;
                                $semesterGrade += $learnerGrade->Result;
                            } else if ($learnerGrade->Quarter == 4) {
                                echo "<td  >$learnerGrade->Result</td>";
                                $semGradeCount++;
                                $semesterGrade += $learnerGrade->Result;
                            }
                        }
                    }
                    if ($semGradeCount != 2) {
                        for ($i = $semGradeCount + 1; $i <= 2; $i++) {
                            echo "<td  >N/A</td>";
                        }
                    }
                    if ($semGradeCount == 2) {
                        $finalGrade = $semesterGrade / 2;
                        $finalGradeQuery = $dbh->prepare("SELECT * FROM tblgrades WHERE StudentId=:studentid AND AdvisingId=:advisingid AND Quarter=5");
                        $finalGradeQuery->bindParam(":studentid", $learner->id, PDO::PARAM_STR);
                        $finalGradeQuery->bindParam(":advisingid", $advising->id, PDO::PARAM_STR);
                        $finalGradeQuery->execute();
                        if ($finalGradeQuery->rowCount() == 1) {
                            $finalGradeRes = $finalGradeQuery->fetch(PDO::FETCH_OBJ);
                            if ($finalGrade != $finalGradeRes->Result) {
                                echo "<form method='POST'><input type='hidden' name='gradeId' value='$finalGradeRes->id'></input><input type='hidden' name='finalGrade' value='$finalGrade'></input><td>$finalGrade &nbsp;<button class='fabutton pointer' type='submit' name='repostFinalGrade' data-bs-toggle='tooltip' data-bs-placement='top' title='Post Updated Final Grade'><i class='fas fa-sync'></i></button></td></form>";
                            } else {
                                echo "<td>$finalGrade</td>";
                            }
                        } else {
                            echo "<form method='POST'><input type='hidden' name='studentId' value='$learner->id'></input><input type='hidden' name='advisingId' value='$advisingId'></input><input type='hidden' name='finalGrade' value='$finalGrade'></input><td > $finalGrade &nbsp;<button class='fabutton pointer' type='submit' name='postFinalGrade' data-bs-toggle='tooltip' data-bs-placement='top' title='Post Final Grade'><i class='fas fa-plus'></i></button></td></form>";
                        }
                        if ($finalGrade >= 75) {
                            echo "<td><b>PASSED</b></td>";
                        } else echo "<td><b>FAILED</b></td>";
                    } else {
                        echo "<td>N/A</td>";
                        echo "<td>N/A</td>";
                    }
                    ?>
                </tr>
        <?php
            }
        }
        ?>
        <tr class="table-secondary">
            <th scope="col">#</th>
            <th scope="col">Learner's Name</th>
            <?php if ($advising->Semester == 1) { ?>
                <th>First Quarter</th>
                <th>Second Quarter</th>
                <th>First Semester Grade</th>
            <?php
            } else { ?>
                <th>Third Quarter</th>
                <th>Fourth Quarter</th>
                <th>Second Semester Grade</th>
            <?php
            } ?>
            <th>Remark</th>
            </>
    </thead>
</table>