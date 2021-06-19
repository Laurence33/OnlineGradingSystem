<?php
// Count number of tasks
$countSQL = "SELECT * FROM tblcomponenttasks WHERE AdvisingId=:advisingid AND Quarter=:quarter";
$countQuery = $dbh->prepare($countSQL);
$countQuery->bindParam(':advisingid', $advisingId);
$countQuery->bindParam(':quarter', $quarter);
$countQuery->execute();
$countResults = $countQuery->fetchAll(PDO::FETCH_OBJ);
$writtenWork = 0;
$performanceTask = 0;
$quarterlyAssessment = 0;
foreach ($countResults as $countResult) {
    if ($countResult->Component == "Written Work") $writtenWork += 1;
    else if ($countResult->Component == "Performance Task") $performanceTask += 1;
    else if ($countResult->Component == "Quarterly Assessment") $quarterlyAssessment += 1;
}
?>
<table class="table table-sm table-light">
    <thead class="thead-light">
        <tr class="table-secondary">
            <th scope="col">#</th>
            <th scope="col">Learner's Name</th>
            <th scope="col" <?php if ($writtenWork > 1) {
                                $col = 3 + $writtenWork;
                                echo "colspan='$col'";
                            } else echo "colspan='4'"; ?>>Written Work</th>
            <th scope="col" <?php if ($performanceTask > 1) {
                                $col = 3 + $performanceTask;
                                echo "colspan='$col'";
                            } else echo "colspan='4'"; ?>>Performance Task</th>
            <th scope="col" <?php if ($quarterlyAssessment > 1) {
                                $col = 3 + $quarterlyAssessment;
                                echo "colspan='$col'";
                            } else echo "colspan='3'"; ?>>Quarterly Assessment</th>
            <th>Initial Grade</th>
            <th>Quarterly Grade</th>
        </tr>
        <!-- Task Numbers -->
        <tr class="table-secondary" style="line-height: 8px;min-height: 8px;height:8px;">
            <th></th>
            <th></th>
            <?php
            if ($writtenWork > 1) {
                for ($i = 1; $i <= $writtenWork; $i++) {
                    echo "<th scope='col'>$i</th>";
                }
            } else {
                echo "<th scope='col'>1</th>";
            }
            echo "<th>Total</th><th>PS</th><th>WS</th>";
            if ($performanceTask > 1) {
                for ($i = 1; $i <= $performanceTask; $i++) {
                    echo "<th scope='col'>$i</th>";
                }
            } else {
                echo "<th scope='col'>1</th>";
            }
            echo "<th>Total</th><th>PS</th><th>WS</th>";

            // Quarterly Assessment
            echo "<th scope='col'>1</th>";
            echo "<th>PS</th><th>WS</th>";

            ?>
            <th></th>
            <th></th>
        </tr>
        <!-- Highest Scores -->
        <tr class="table-secondary" style="line-height: 8px;min-height: 8px;height: 8px;">
            <th colspan="2" scope="row" class="text-center">Highest Possible Score</th>
            <?php

            if ($loadSubType == 1) {
                $WWweight = .25;
                $PTweight = .50;
                $QAweight = .25;
            } else {
                if ($classTrack == "Academic Track") {
                    if ($loadSubType == 2) { //Work Immersion/Research etc.
                        $WWweight = .35;
                        $PTweight = .40;
                        $QAweight = .25;
                    } else { // Other subjects
                        $WWweight = .25;
                        $PTweight = .45;
                        $QAweight = .30;
                    }
                } else { //TVL Track
                    $WWweight = .20;
                    $PTweight = .60;
                    $QAweight = .20;
                }
            }
            $taskCount = 0;
            $WWtotal = 0;
            foreach ($countResults as $countResultScore) {
                if ($countResultScore->Component == "Written Work") {
                    echo "<th>$countResultScore->HighestScore</th>";
                    $taskCount += 1;
                    $WWtotal += $countResultScore->HighestScore;
                }
            }
            if ($taskCount >= 1) {
                $WS = $WWweight * 100;
                echo "<th>$WWtotal</th><th>100.00</th><th>$WS%</th>";
            } else echo "<th>N/A</th><th>N/A</th><th>N/A</th><th>N/A</th>";
            $taskCount = 0;
            $PTtotal = 0;
            foreach ($countResults as $countResultScore) {
                if ($countResultScore->Component == "Performance Task") {
                    echo "<th>$countResultScore->HighestScore</th>";
                    $taskCount += 1;
                    $PTtotal += $countResultScore->HighestScore;
                }
            }
            if ($taskCount >= 1) {
                $WS = $PTweight * 100;
                echo "<th>$PTtotal</th><th>100.00</th><th>$WS%</th>";
            } else echo "<th>N/A</th><th>N/A</th><th>N/A</th><th>N/A</th>";
            $flag = false;
            $QAtotal = 0;
            foreach ($countResults as $countResultScore) {
                if ($countResultScore->Component == "Quarterly Assessment") {
                    $QAtotal += $countResultScore->HighestScore;
                    echo "<th>$countResultScore->HighestScore</th>";
                    $flag = true;
                }
            }
            if ($flag) {
                $WS = $QAweight * 100;
                echo "<th>100.00</th><th>$WS%</th>";
            } else echo "<th>N/A</th><th>N/A</th><th>N/A</th>";
            ?>
            <th></th>
            <th></th>
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
            foreach ($results as $student) { // Student Results
                $studentId = $student->id;
        ?>
                <tr>
                    <th scope="row"><?php echo $cnt ?></th>
                    <td><?php echo htmlentities($student->StudentName); ?></td>
                    <?php
                    $WWScoreFlag = false;
                    $WWScoreTotal = 0;
                    foreach ($countResults as $task) {
                        if ($task->Component == "Written Work") {
                            $resultSQL = "SELECT * FROM  tblresults WHERE StudentId=:studid AND TaskId=:taskid";
                            $resultQuery = $dbh->prepare($resultSQL);
                            $resultQuery->bindParam(':studid', $student->id, PDO::PARAM_STR);
                            $resultQuery->bindParam(':taskid', $task->id, PDO::PARAM_STR);
                            $resultQuery->execute();
                            $resultRes = $resultQuery->fetch(PDO::FETCH_OBJ);

                            if ($resultRes->Score) {
                                $WWScoreFlag = true;
                                $WWScoreTotal += $resultRes->Score;
                                echo "<td class='pointer text-success' onclick='editResult(`$resultRes->id`, `$resultRes->Score`, `$student->StudentName`, `$task->Component`, `$task->TaskNumber`, `$task->HighestScore`)' data-toggle='modal' data-target='#declareResultModal'><em>$resultRes->Score</em></td>";
                            } else {
                                echo "<td class='pointer text-success' onclick='declareResult(`$task->id`, `$student->id`, `$student->StudentName`, `$task->Component`, `$task->TaskNumber`, `$task->HighestScore`);' data-toggle='modal' data-target='#declareResultModal'><em>N/A</em></td>";
                            } ?>
                    <?php
                        }
                    }
                    if ($writtenWork == 0) {
                        echo "<td>N/A</td>";
                    }
                    ?>
                    <!-- Total WW Score -->
                    <td><?php if ($WWScoreFlag) echo $WWScoreTotal;
                        else echo 'N/A' ?></td>
                    <!-- WW PS-->
                    <td><?php if ($WWScoreFlag) {
                            $wwPS = ($WWScoreTotal / $WWtotal) * 100;
                            echo number_format((float)$wwPS, 2, '.', '');
                        } else echo 'N/A' ?></td>
                    <!-- WW WS -->
                    <td><?php if ($WWScoreFlag) {
                            $wwWS = $wwPS * $WWweight;
                            echo number_format((float)$wwWS, 2, '.', '');
                        } else echo 'N/A' ?></td>
                    <?php
                    $PTscoreFlag = false;
                    $PTScoreTotal = 0;
                    foreach ($countResults as $task) {
                        if ($task->Component == "Performance Task") {
                            $resultSQL = "SELECT * FROM  tblresults WHERE StudentId=:studid AND TaskId=:taskid";
                            $resultQuery = $dbh->prepare($resultSQL);
                            $resultQuery->bindParam(':studid', $student->id, PDO::PARAM_STR);
                            $resultQuery->bindParam(':taskid', $task->id, PDO::PARAM_STR);
                            $resultQuery->execute();
                            $resultRes = $resultQuery->fetch(PDO::FETCH_OBJ);

                            if ($resultRes->Score) {
                                $PTscoreFlag = true;
                                $PTScoreTotal += $resultRes->Score;
                                echo "<td class='pointer text-success' onclick='editResult(`$resultRes->id`, `$resultRes->Score`, `$student->StudentName`, `$task->Component`, `$task->TaskNumber`, `$task->HighestScore`)' data-toggle='modal' data-target='#declareResultModal'><em>$resultRes->Score</em></td>";
                            } else {
                                echo "<td class='pointer text-success' onclick='declareResult(`$task->id`, `$student->id`, `$student->StudentName`, `$task->Component`, `$task->TaskNumber`, `$task->HighestScore`);' data-toggle='modal' data-target='#declareResultModal'><em>N/A</em></td>";
                            } ?>
                    <?php
                        }
                    }
                    if ($performanceTask == 0) {
                        echo "<td>N/A</td>";
                    }
                    ?>
                    <!-- Total PT Score -->
                    <td><?php if ($PTscoreFlag) echo $PTScoreTotal;
                        else echo 'N/A' ?></td>
                    <!-- PW PS-->
                    <td><?php if ($PTscoreFlag) {
                            $ptPS = ($PTScoreTotal / $PTtotal) * 100;
                            echo number_format((float)$ptPS, 2, '.', '');
                        } else echo 'N/A' ?></td>
                    <!-- PT WS -->
                    <td><?php if ($PTscoreFlag) {
                            $ptWS = $ptPS * $PTweight;
                            echo number_format((float)$ptWS, 2, '.', '');
                        } else echo 'N/A' ?></td>
                    <?php
                    $QAscoreFlag = false;
                    $QAScoreTotal = 0;
                    foreach ($countResults as $task) {
                        if ($task->Component == "Quarterly Assessment") {
                            $resultSQL = "SELECT * FROM  tblresults WHERE StudentId=:studid AND TaskId=:taskid";
                            $resultQuery = $dbh->prepare($resultSQL);
                            $resultQuery->bindParam(':studid', $student->id, PDO::PARAM_STR);
                            $resultQuery->bindParam(':taskid', $task->id, PDO::PARAM_STR);
                            $resultQuery->execute();
                            $resultRes = $resultQuery->fetch(PDO::FETCH_OBJ);

                            if ($resultRes->Score) {
                                $QAscoreFlag = true;
                                $QAScoreTotal += $resultRes->Score;
                                echo "<td class='pointer text-success' onclick='editResult(`$resultRes->id`, `$resultRes->Score`, `$student->StudentName`, `$task->Component`, `$task->TaskNumber`, `$task->HighestScore`)' data-toggle='modal' data-target='#declareResultModal'><em>$resultRes->Score</em></td>";
                            } else {
                                echo "<td class='pointer text-success' onclick='declareResult(`$task->id`, `$student->id`, `$student->StudentName`, `$task->Component`, `$task->TaskNumber`, `$task->HighestScore`);' data-toggle='modal' data-target='#declareResultModal'><em>N/A</em></td>";
                            } ?>

                    <?php
                        }
                    }
                    if ($quarterlyAssessment == 0) {
                        echo "<td>N/A</td>";
                    }
                    ?>
                    <!-- QA PS-->
                    <td><?php if ($QAscoreFlag) {
                            $qaPS = ($QAScoreTotal / $QAtotal) * 100;
                            echo number_format((float) $qaPS, 2, '.', '');
                        } else echo 'N/A' ?></td>
                    <!-- QA WS -->
                    <td><?php if ($QAscoreFlag) {
                            $qaWS = $qaPS * $QAweight;
                            echo number_format((float) $qaWS, 2, '.', '');
                        } else echo 'N/A' ?></td>
                    <?php
                    $initialGrade = $wwWS + $ptWS + $qaWS;
                    $initialGrade = number_format((float)$initialGrade, 2, '.', '');
                    if ($WWScoreFlag && $PTscoreFlag && $QAscoreFlag) {
                        echo "<td class='text-center'>$initialGrade</td>";
                    } else echo "<td class='text-center'>N/A</td>";

                    // Check if Final Grade is existing
                    // studId, advisingId, Quarter

                    $gradeQuery = "SELECT * FROM tblgrades WHERE StudentId=:studid AND AdvisingId=:advisingid AND Quarter=:quarter";
                    $gradesQuery = $dbh->prepare($gradeQuery);
                    $gradesQuery->bindParam(":studid", $student->id, PDO::PARAM_STR);
                    $gradesQuery->bindParam(":advisingid", $advisingId, PDO::PARAM_STR);
                    $gradesQuery->bindParam(":quarter", $quarter, PDO::PARAM_STR);
                    $gradesQuery->execute();
                    if ($initialGrade == 100) $result = 100;
                    else if ($initialGrade >= 98.40 && $initialGrade <= 99.99) $result = 99;
                    else if ($initialGrade >= 96.80 && $initialGrade <= 98.39) $result = 98;
                    else if ($initialGrade >= 95.20 && $initialGrade <= 96.79) $result = 97;
                    else if ($initialGrade >= 93.60 && $initialGrade <= 95.19) $result = 96;
                    else if ($initialGrade >= 92.00 && $initialGrade <= 93.59) $result = 95;
                    else if ($initialGrade >= 90.40 && $initialGrade <= 91.99) $result = 94;
                    else if ($initialGrade >= 88.80 && $initialGrade <= 90.39) $result = 93;
                    else if ($initialGrade >= 87.20 && $initialGrade <= 88.79) $result = 94;
                    else if ($initialGrade >= 90.40 && $initialGrade <= 91.99) $result = 92;
                    else if ($initialGrade >= 85.60 && $initialGrade <= 87.19) $result = 91;
                    else if ($initialGrade >= 84.00 && $initialGrade <= 85.59) $result = 90;
                    else if ($initialGrade >= 82.40 && $initialGrade <= 83.99) $result = 89;
                    else if ($initialGrade >= 80.80 && $initialGrade <= 82.39) $result = 88;
                    else if ($initialGrade >= 79.20 && $initialGrade <= 80.79) $result = 87;
                    else if ($initialGrade >= 77.60 && $initialGrade <= 79.19) $result = 86;
                    else if ($initialGrade >= 76.00 && $initialGrade <= 77.59) $result = 85;
                    else if ($initialGrade >= 74.40 && $initialGrade <= 75.99) $result = 84;
                    else if ($initialGrade >= 72.80 && $initialGrade <= 74.39) $result = 83;
                    else if ($initialGrade >= 71.20 && $initialGrade <= 72.79) $result = 82;
                    else if ($initialGrade >= 69.60 && $initialGrade <= 71.19) $result = 81;
                    else if ($initialGrade >= 68.00 && $initialGrade <= 69.59) $result = 80;
                    else if ($initialGrade >= 66.40 && $initialGrade <= 67.99) $result = 79;
                    else if ($initialGrade >= 64.80 && $initialGrade <= 66.39) $result = 78;
                    else if ($initialGrade >= 63.20 && $initialGrade <= 64.79) $result = 77;
                    else if ($initialGrade >= 61.60 && $initialGrade <= 63.19) $result = 76;
                    else if ($initialGrade >= 60.00 && $initialGrade <= 61.59) $result = 75;
                    else if ($initialGrade >= 56.00 && $initialGrade <= 59.99) $result = 74;
                    else if ($initialGrade >= 52.00 && $initialGrade <= 55.99) $result = 73;
                    else if ($initialGrade >= 48.00 && $initialGrade <= 51.99) $result = 72;
                    else if ($initialGrade >= 44.00 && $initialGrade <= 47.99) $result = 71;
                    else if ($initialGrade >= 40.00 && $initialGrade <= 43.99) $result = 70;
                    else if ($initialGrade >= 36.00 && $initialGrade <= 39.99) $result = 69;
                    else if ($initialGrade >= 32.00 && $initialGrade <= 35.99) $result = 68;
                    else if ($initialGrade >= 28.00 && $initialGrade <= 31.99) $result = 67;
                    else if ($initialGrade >= 24.00 && $initialGrade <= 27.99) $result = 66;
                    else if ($initialGrade >= 20.00 && $initialGrade <= 23.99) $result = 65;
                    else if ($initialGrade >= 16.00 && $initialGrade <= 19.99) $result = 64;
                    else if ($initialGrade >= 12.00 && $initialGrade <= 15.99) $result = 63;
                    else if ($initialGrade >=  8.00 && $initialGrade <= 11.99) $result = 62;
                    else if ($initialGrade >=  4.00 && $initialGrade <=  7.99) $result = 61;
                    else if ($initialGrade >=  0.00 && $initialGrade <=  3.99) $result = 60;
                    if ($gradesQuery->rowCount() == 1) {
                        $grade = $gradesQuery->fetch(PDO::FETCH_OBJ);
                        if ($grade->Result != $result)
                            echo "<td class='text-center'><form method='POST'><input type='hidden' name='gradeId' value='$grade->id'></input><input type='hidden' name='result' value='$result'></input>$result &nbsp;<button class='fabutton pointer' type='submit' name='repostGrade' data-bs-toggle='tooltip' data-bs-placement='top' title='Post updated grade'>  <i class='fas fa-sync'></i></button></form>";
                        else echo  "<td class='text-center'>$grade->Result</td>";
                    } else {
                        if ($WWScoreFlag && $PTscoreFlag && $QAscoreFlag)
                            echo "<form method='POST'><input type='hidden' name='studentId' value='$student->id'></input><input type='hidden' name='advisingId' value='$advisingId'></input><input type='hidden' name='quarter' value='$quarter'></input><input type='hidden' name='result' value='$result'></input><td class='text-center pointer' ><button class='fabutton pointer' type='submit' name='postGrade' data-bs-toggle='tooltip' data-bs-placement='top' title='Compute and Post grade'><i class='fas fa-plus'></i></button></td></form>";
                        else echo "<td class='text-center'>N/A</td>";
                    }
                    ?>
                </tr>

        <?php

                $cnt += 1;
            }
        }
        ?>
    </tbody>
    <thead class="thead-light">
        <tr class="table-secondary">
            <th scope="col">#</th>
            <th scope="col">Learner's Name</th>
            <th scope="col" <?php if ($writtenWork > 1) {
                                $col = 3 + $writtenWork;
                                echo "colspan='$col'";
                            } else echo "colspan='4'"; ?>>Written Work</th>
            <th scope="col" <?php if ($performanceTask > 1) {
                                $col = 3 + $performanceTask;
                                echo "colspan='$col'";
                            } else echo "colspan='4'"; ?>>Performance Task</th>
            <th scope="col" <?php if ($quarterlyAssessment > 1) {
                                $col = 3 + $quarterlyAssessment;
                                echo "colspan='$col'";
                            } else echo "colspan='3'"; ?>>Quarterly Assessment</th>
            <th>Initial Grade</th>
            <th>Quarterly Grade</th>
        </tr>
    </thead>
</table>