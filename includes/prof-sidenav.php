<?php

$profId = $_SESSION['profId'];
?>

<!-- Sidebar  -->
<nav id="sidebar">
    <div class="sidebar-header">
        <br>
        <h4>OGS | Professor</h4>
        <strong>OGS</strong>
    </div>

    <ul class="list-unstyled components">
        <li id="dashboard">
            <a href="dashboard.php">
                <i class="fas fa-chart-line"></i>
                Dashboard
            </a>
        </li>
        <li id="subjects">
            <a href="#SubjectsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-book"></i>
                My Subjects
            </a>
            <ul class="collapse list-unstyled" id="SubjectsSubmenu">
                <?php
                $sql = "SELECT * FROM tblsubjectloading WHERE ProfessorId=:profid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':profid', $profId, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                if ($query->rowCount() > 0) {
                    foreach ($results as $result) {

                        $sql1 = "SELECT * FROM tblsubjectadvising WHERE id=:advisingid";
                        $query1 = $dbh->prepare($sql1);
                        $query1->bindParam(':advisingid', $result->AdvisingId, PDO::PARAM_STR);
                        $query1->execute();
                        $result1 = $query1->fetch(PDO::FETCH_OBJ);
                        $classId = $result1->ClassId;
                        $subjectId = $result1->SubjectId;

                        $sql2 = "SELECT * FROM tblclasses WHERE id=:classid";
                        $query2 = $dbh->prepare($sql2);
                        $query2->bindParam(':classid', $classId, PDO::PARAM_STR);
                        $query2->execute();
                        $result2 = $query2->fetch(PDO::FETCH_OBJ);
                        $className = $result2->ClassName;

                        $sql3 = "SELECT * FROM tblsubjects WHERE id=:subjectid";
                        $query3 = $dbh->prepare($sql3);
                        $query3->bindParam(':subjectid', $subjectId, PDO::PARAM_STR);
                        $query3->execute();
                        $result3 = $query3->fetch(PDO::FETCH_OBJ);
                        $subName = $result3->SubjectName;

                ?>
                        <li>
                            <a href="manageLoad.php?advisingid=<?php echo htmlentities($result->AdvisingId); ?>"> <?php echo htmlentities($className) . "-" . htmlentities($subName) ?></a>
                        </li>
                <?php }
                } ?>
            </ul>
        </li>
        <li id="changePassword">
            <a href="changePassword.php">
                <i class="fas fa-lock"></i>
                Change Password
            </a>
        </li>
    </ul>

    <ul class="list-unstyled CTAs">
        <li>
            <a href="logout.php" class="logout">Logout</a>
        </li>
    </ul>
</nav>