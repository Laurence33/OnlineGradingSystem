<?php
include "includes/config.php";

session_start();
if (strlen($_SESSION['alogin']) == '' and strlen($_SESSION['plogin']) == '') {
    header("Location: index.php");
}
// The admin/professor is logged in and has access to the page

include "header.php";
?>

<div class="wrapper">
    <?php
    if ($_SESSION['alogin']) {
        include "includes/admin-sidenav.php";
    } else {
        include "includes/prof-sidenav.php";
    }

    ?>

    <!-- Page Content  -->
    <div id="content">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">

                <button class="btn btn-dark" type="button" id="sidebarCollapse" class="btn btn-info">
                    <i class="fas fa-align-justify"></i>
                    <span>Menu</span>
                </button>

                <h2>&nbsp; Online Grading System</h2>


            </div>
        </nav>

        <div class="container">
            <?php
            if ($_SESSION['alogin']) {
                include "includes/a-dashboard.php";
            } else {
                include "includes/p-dashboard.php";
            }
            ?>
        </div>
    </div>
    <!-- Closing div for .content -->
</div>
<!-- closing div for .wrapper -->
<script>
    document.getElementById("dashboard").setAttribute("class", "active")
</script>

<?php include "footer.php";
?>