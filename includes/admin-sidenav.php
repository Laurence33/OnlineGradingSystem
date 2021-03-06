<!-- Sidebar  -->
<nav id="sidebar">
    <ul class="list-unstyled CTAs" id="logo" style="padding-bottom:0;">
        <li>
            <img class="col-md-12 col-sm-2" src="./assets/img/school-logo.png" height="180px" alt="International School of Asia and the Pacific">

        </li>
    </ul>
    <div class="sidebar-header" id="header" style="padding-top: 0; padding-bottom:0;">
        <br>
        <h4 class="text-center">OGS | Admin</h4>
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
                Subjects
            </a>
            <ul class="collapse list-unstyled" id="SubjectsSubmenu">
                <li>
                    <a href="addSubject.php">Add Subject</a>
                </li>
                <li>
                    <a href="manageSubject.php">Manage Subject</a>
                </li>
                <li>
                    <a href="createSubjectAdvising.php">Create Subject Advising</a>
                </li>
                <li>
                    <a href="manageSubjectAdvising.php">Manage Subject Advising</a>
                </li>
                <li>
                    <a href="createSubjectLoading.php">Create Subject Loading</a>
                </li>
                <li>
                    <a href="manageSubjectLoading.php">Manage Subject Loading</a>
                </li>
            </ul>
        </li>
        <li id="classes">
            <a href="#classesSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-users-cog"></i>
                Classes
            </a>
            <ul class="collapse list-unstyled" id="classesSubmenu">
                <li>
                    <a href="addClass.php">Add Class</a>
                </li>
                <li>
                    <a href="manageClass.php">Manage Class</a>
                </li>
            </ul>
        </li>
        <li id="professors">
            <a href="#profSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-user-tie"></i>
                Professors
            </a>
            <ul class="collapse list-unstyled" id="profSubmenu">
                <li>
                    <a href="addProfessor.php">Add Professor</a>
                </li>
                <li>
                    <a href="manageProfessor.php">Manage Professor</a>
                </li>
                <li>
                    <a href="createProfessorCredential.php">Create Credential</a>
                </li>
                <li>
                    <a href="resetProfessorCredentials.php">Reset Password</a>
                </li>
            </ul>
        </li>
        <li id="students">
            <a href="#studSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-user"></i>
                Students
            </a>
            <ul class="collapse list-unstyled" id="studSubmenu">
                <li>
                    <a href="addStudent.php">Add Student</a>
                </li>
                <li>
                    <a href="manageStudent.php">Manage Student</a>
                </li>
                <li>
                    <a href="createStudentCredential.php">Create Credential</a>
                </li>
                <li>
                    <a href="resetStudentCredentials.php">Reset Password</a>
                </li>
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