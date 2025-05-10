<?php
session_start();
include('includes/dbconnection.php');
error_reporting(0);

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $adminid = $_SESSION['sturecmsaid'];
        $cpassword = md5($_POST['currentpassword']);
        $newpassword = md5($_POST['newpassword']);

        $sql = "SELECT ID FROM tbladmin WHERE ID=:adminid and Password=:cpassword";
        $query = $dbh->prepare($sql);
        $query->bindParam(':adminid', $adminid, PDO::PARAM_STR);
        $query->bindParam(':cpassword', $cpassword, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            $con = "UPDATE tbladmin SET Password=:newpassword WHERE ID=:adminid";
            $chngpwd1 = $dbh->prepare($con);
            $chngpwd1->bindParam(':adminid', $adminid, PDO::PARAM_STR);
            $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
            $chngpwd1->execute();
            echo '<script>alert("Your password has been successfully changed")</script>';
        } else {
            echo '<script>alert("Your current password is incorrect")</script>';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edutrack Pro || Change Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap and Vendor CSS -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" />

    <!-- Client-side validation script -->
    <script type="text/javascript">
        function checkpass() {
            if (document.changepassword.newpassword.value !== document.changepassword.confirmpassword.value) {
                alert('New Password and Confirm Password fields do not match');
                document.changepassword.confirmpassword.focus();
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <div class="container-scroller">
        <?php include_once('includes/header.php'); ?>

        <div class="container-fluid page-body-wrapper">
            <?php include_once('includes/sidebar.php'); ?>

            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title">Change Password</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Change Password</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card w-100">
                                <div class="card-body">
                                    <h4 class="card-title text-center">Change Your Password</h4>

                                    <form class="forms-sample" name="changepassword" method="post" onsubmit="return checkpass();">
                                        <div class="form-group">
                                            <label for="currentpassword">Current Password</label>
                                            <input type="password" name="currentpassword" id="currentpassword" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="newpassword">New Password</label>
                                            <input type="password" name="newpassword" id="newpassword" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="confirmpassword">Confirm Password</label>
                                            <input type="password" name="confirmpassword" id="confirmpassword" class="form-control" required>
                                        </div>

                                        <button type="submit" name="submit" class="btn btn-primary btn-block">Change Password</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap and Vendor JS -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="vendors/select2/select2.min.js"></script>
    <script src="vendors/typeahead.js/typeahead.bundle.min.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
    <script src="js/typeahead.js"></script>
    <script src="js/select2.js"></script>
</body>

</html>
<?php } ?>
