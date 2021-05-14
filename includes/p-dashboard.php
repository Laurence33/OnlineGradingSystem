<div class="container d-flex flex-row justify-content-center">

    <div class="card" style="width: 14rem;margin:10px;">
        <!-- <img class="card-img-top" src=".../100px180/" alt="Card image cap"> -->
        <?php
        $sql2 = "SELECT * from tblsubjectloading WHERE ProfessorId=:profid";
        $query2 = $dbh->prepare($sql2);
        $query2->bindParam(':profid', $_SESSION['profId'], PDO::PARAM_STR);
        $query2->execute();
        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
        $totalloading = $query2->rowCount();
        ?>
        <div class="card-body text-center">
            <h5 class="card-title">My Subject Load</h5>
            <p class="card-text text-center"><?php echo htmlentities($totalloading); ?></p>
        </div>
    </div>

    <div class="card" style="width: 14rem;margin:10px;">
        <!-- <img class="card-img-top" src=".../100px180/" alt="Card image cap"> -->
        <?php

        $studCount = 0;

        $sql2 = "SELECT * from tblsubjectloading WHERE ProfessorId=:profid";
        $query2 = $dbh->prepare($sql2);
        $query2->bindParam(':profid', $_SESSION['profId'], PDO::PARAM_STR);
        $query2->execute();
        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);

        foreach ($results2 as $loading) {
            $sql3 = "SELECT * from tblsubjectadvising WHERE id=:advisingid";
            $query3 = $dbh->prepare($sql3);
            $query3->bindParam(':advisingid', $loading->AdvisingId, PDO::PARAM_STR);
            $query3->execute();
            $results3 = $query3->fetchAll(PDO::FETCH_OBJ);

            foreach ($results3 as $advising) {
                $sql4 = "SELECT * from tblStudents WHERE ClassId=:classid";
                $query4 = $dbh->prepare($sql4);
                $query4->bindParam(':classid', $advising->ClassId, PDO::PARAM_STR);
                $query4->execute();
                $results4 = $query4->fetchAll(PDO::FETCH_OBJ);
                $studCount += $query4->rowCount();
            }
        }

        ?>
        <div class="card-body text-center">
            <h5 class="card-title">My Students</h5>
            <p class="card-text text-center"><?php echo htmlentities($studCount); ?></p>
        </div>
    </div>
</div>