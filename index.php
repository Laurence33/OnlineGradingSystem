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
<div class="container-fluid">
    <div class="container-fluid text-center">
        <br>
        <h1>Online Grading System</h1>
    </div>
    <br>
    <div class="container-fluid">
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="col-6 align-self-center">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Login</h5>
                                <form method="POST">
                                    <div class="form-group col-md-6">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" aria-describedby="unameHelp">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="login">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>