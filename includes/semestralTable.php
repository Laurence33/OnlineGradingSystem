<table class="table table-sm table-light">
    <thead class="thead-light">
        <tr class="table-secondary">
            <th scope="col">#</th>
            <th scope="col">Learner's Name</th>
            <?php if ($semester == 1) { ?>
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
                        if ($semester == 1) {
                            if ($learnerGrade->Quarter == 1) {
                                echo "<td  >$learnerGrade->Result</td>";
                                $semGradeCount++;
                                $semesterGrade += $learnerGrade->Result;
                            } else if ($learnerGrade->Quarter == 2) {
                                echo "<td  >$learnerGrade->Result</td>";
                                $semGradeCount++;
                                $semesterGrade += $learnerGrade->Result;
                            }
                        } else if ($semester == 2) {
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
                        $semesterGrade /= 2;
                        echo "<td  >$semesterGrade</td>";
                        if ($semesterGrade >= 75) {
                            echo "<td><b>PASSED</b></td>";
                        } else echo "<td><b>FAILED</b></td>";
                    } else {
                        echo "<td  >N/A</td>";
                        echo "<td  >N/A</td>";
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
            <?php if ($semester == 1) { ?>
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