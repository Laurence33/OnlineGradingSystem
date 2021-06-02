<div class="container d-flex flex-row justify-content-center">
    <div class="card" style="width: 14rem;margin:10px;">
        <!-- <img class="card-img-top" src=".../100px180/" alt="Card image cap"> -->
        <?php
        $sql1 = "SELECT id from tblstudents";
        $query1 = $dbh->prepare($sql1);
        $query1->execute();
    
        $totalstudents = $query1->rowCount();
        ?>
        <div class="card-body text-center">
            <h5 class="card-title">Students Enrolled</h5>
            <p class="card-text text-center"><?php echo htmlentities($totalstudents); ?></p>
            <a href="manageStudent.php" class="card-link text-primary">Manage</a>
        </div>
    </div>

    <div class="card" style="width: 14rem;margin:10px;">
        <!-- <img class="card-img-top" src=".../100px180/" alt="Card image cap"> -->
        <?php
        $sql2 = "SELECT id from tblprofessors ";
        $query2 = $dbh->prepare($sql2);
        $query2->execute();
        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
        $totalprofessors = $query2->rowCount();
        ?>
        <div class="card-body text-center">
            <h5 class="card-title">Professors</h5>
            <p class="card-text text-center"><?php echo htmlentities($totalprofessors); ?></p>
            <a href="manageProfessor.php" class="card-link text-primary">Manage</a>
        </div>
    </div>

    <div class="card" style="width: 14rem;margin:10px;">
        <!-- <img class="card-img-top" src=".../100px180/" alt="Card image cap"> -->
        <?php
        $sql2 = "SELECT id from tblclasses ";
        $query2 = $dbh->prepare($sql2);
        $query2->execute();
        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
        $totalclasses = $query2->rowCount();
        ?>
        <div class="card-body text-center">
            <h5 class="card-title">Classes</h5>
            <p class="card-text text-center"><?php echo htmlentities($totalclasses); ?></p>
            <a href="manageClass.php" class="card-link text-primary">Manage</a>
        </div>
    </div>
</div>

<div class="container d-flex flex-row justify-content-center">

    <div class="card" style="width: 14rem;margin:10px;">
        <!-- <img class="card-img-top" src=".../100px180/" alt="Card image cap"> -->
        <?php
        $sql2 = "SELECT id from tblsubjects ";
        $query2 = $dbh->prepare($sql2);
        $query2->execute();
        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
        $totalsubjects = $query2->rowCount();
        ?>
        <div class="card-body text-center">
            <h5 class="card-title">Subjects Listed</h5>
            <p class="card-text text-center"><?php echo htmlentities($totalsubjects); ?></p>
            <a href="manageSubject.php" class="card-link text-primary">Manage</a>
        </div>
    </div>

    <div class="card" style="width: 14rem;margin:10px;">
        <!-- <img class="card-img-top" src=".../100px180/" alt="Card image cap"> -->
        <?php
        $sql2 = "SELECT id from tblsubjectadvising ";
        $query2 = $dbh->prepare($sql2);
        $query2->execute();
        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
        $totaladvising = $query2->rowCount();
        ?>
        <div class="card-body text-center">
            <h5 class="card-title">Subject Advising</h5>
            <p class="card-text text-center"><?php echo htmlentities($totaladvising); ?></p>
            <a href="manageSubjectAdvising.php" class="card-link text-primary">Manage</a>
        </div>
    </div>

    <div class="card" style="width: 14rem;margin:10px;">
        <!-- <img class="card-img-top" src=".../100px180/" alt="Card image cap"> -->
        <?php
        $sql2 = "SELECT id from tblsubjectloading ";
        $query2 = $dbh->prepare($sql2);
        $query2->execute();
        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
        $totalloading = $query2->rowCount();
        ?>
        <div class="card-body text-center">
            <h5 class="card-title">Subject Loading</h5>
            <p class="card-text text-center"><?php echo htmlentities($totalloading); ?></p>
            <a href="manageSubjectLoading.php" class="card-link text-primary">Manage</a>
        </div>
    </div>
</div>