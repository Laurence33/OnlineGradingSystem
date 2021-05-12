<?php
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
            <div class="col-7">
                <div class="container">
                    <div class="card">
                        <div class="card-body">
                            <br>
                            <h3 class="card-title">Looking for Student Results?</h3>
                            <br>
                            <div class="alert alert-primary" role="alert">
                                If you want to view your results, input your Roll ID and Birthdate below!
                            </div>
                            <br>
                            <form method="POST">
                                <div class="form-group">
                                    <label for="rollid">Roll ID</label>
                                    <input type="text" class="form-control" id="rollid" name="rollid" aria-describedby="rollidHelp">
                                    <small id="rollidHelp" class="form-text text-muted">Example: 18-0684</small>
                                </div>
                                <div class="form-group">
                                    <label for="birthdate">Birthdate</label>
                                    <input type="date" class="form-control" id="birthdate" name="birthdate">
                                </div>
                                <!-- <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                            <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                        </div> -->
                                <br>
                                <div class="container text-center">
                                    <button type="submit" class="btn btn-success btn-lg">View my Results</button>
                                </div>
                            </form>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="container">
                                <div class="card" style="width: 28rem; margin: 10px;">
                                    <div class="card-body">
                                        <h5 class="card-title">Admin Login</h5>
                                        <form method="POST" action="includes/login.inc.php">
                                            <div class="form-group">
                                                <label for="username">Username</label>
                                                <input type="text" class="form-control" id="username" name="username" aria-describedby="unameHelp">
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" class="form-control" id="password" name="password">
                                            </div>
                                            <!-- <div class="form-group form-check">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                                    </div> -->
                                            <button type="submit" class="btn btn-primary" name="adminlogin">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="container">
                                <div class="card" style="width: 28rem; margin: 10px;">
                                    <div class="card-body">
                                        <h5 class="card-title">Professor Login</h5>
                                        <form method="POST">
                                            <div class="form-group">
                                                <label for="username">Username</label>
                                                <input type="text" class="form-control" id="username" name="username" aria-describedby="unameHelp">
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" class="form-control" id="password" name="password">
                                            </div>
                                            <!-- <div class="form-group form-check">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                        <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                                    </div> -->
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>