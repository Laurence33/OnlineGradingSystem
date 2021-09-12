<?php
session_start();
include "includes/config.php";

// Professor Login submitted
if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM tbllogin WHERE UserName=:uname and Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uname', $username, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    if ($result) { // credentials found
        if ($result->Role == 1) {
            $_SESSION['alogin'] = 'admin';
            $_SESSION['Role'] = 1;
        } else if ($result->Role == 2) {
            // check if the professor is active, do not allow login of inactive professor
            $profSql = "SELECT * FROM tblprofessors WHERE id=:profid";
            $profQuery = $dbh->prepare($profSql);
            $profQuery->bindParam(':profid', $result->UserId, PDO::PARAM_STR);
            $profQuery->execute();
            $professor = $profQuery->fetch(PDO::FETCH_OBJ);
            if ($professor->Status == 1) {
                $_SESSION['plogin'] = $result->UserName;
                $_SESSION['profId'] = $result->UserId;
                $_SESSION['Role'] = 2;
                echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
            } else {
                echo "<script>alert('Your account is disabled, please contact the administrator.');</script>";
            }
        } else if ($result->Role == 3) { // Student Login
            // check if the student is active, do not allow login of inactive student
            $studSql = "SELECT * FROM tblstudents WHERE id=:studid";
            $studQuery = $dbh->prepare($studSql);
            $studQuery->bindParam(':studid', $result->UserId, PDO::PARAM_STR);
            $studQuery->execute();
            $student = $studQuery->fetch(PDO::FETCH_OBJ);
            if ($student->Status == 1) {
                $_SESSION['slogin'] = $result->UserName;
                $_SESSION['studentId'] = $result->UserId;
                $_SESSION['Role'] = 3;
                echo "<script type='text/javascript'> document.location = 'viewResult.php'; </script>";
            } else {
                echo "<script>alert('Your account is disabled, please contact the school administrator.');</script>";
            }
        }
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}
if (isset($_SESSION['alogin']) or isset($_SESSION['plogin'])) {
    header("Location: dashboard.php");
}
include "header.php";
?>
<div class="container-fluid" style="background:url('./assets/img/bg.jpg'); background-size: 100vw 100vh; height: 100vh;">

    <div class="row d-flex align-items-center justify-content-center " style="height: 100vh;">
        <div class="col-6 align-self-center">
            <div class="card align-middle" style="height: 70vh; width:50vw">
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <img class="col-sm-3" src="./assets/img/school-logo.png" height="150px" alt="International School of Asia and The Pacific">
                    </div>
                    <h4 class="text-center">Online Grading System for SHS Department</h4>
                    <br><br>
                    <h5 class="card-title">Login</h5>
                    <form method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" aria-describedby="unameHelp">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="d-flex flex-column-reverse">
                            <button type="submit" class="btn btn-primary" name="login">Submit</button>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>