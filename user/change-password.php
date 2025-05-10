<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsstuid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $sid = $_SESSION['sturecmsstuid'];
        $cpassword = md5($_POST['currentpassword']);
        $newpassword = md5($_POST['newpassword']);
        $sql = "SELECT StuID FROM tblstudent WHERE StuID=:sid and Password=:cpassword";
        $query = $dbh->prepare($sql);
        $query->bindParam(':sid', $sid, PDO::PARAM_STR);
        $query->bindParam(':cpassword', $cpassword, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            $con = "update tblstudent set Password=:newpassword where StuID=:sid";
            $chngpwd1 = $dbh->prepare($con);
            $chngpwd1->bindParam(':sid', $sid, PDO::PARAM_STR);
            $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
            $chngpwd1->execute();

            echo '<script>alert("Your password successfully changed")</script>';
        } else {
            echo '<script>alert("Your current password is wrong")</script>';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- ✅ Viewport for mobile responsiveness -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edutrack Pro || Student Change Password</title>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        .card {
            border-radius: 1rem;
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1rem;
            }
        }
    </style>
    <script type="text/javascript">
        function checkpass() {
            if (document.changepassword.newpassword.value !== document.changepassword.confirmpassword.value) {
                alert('New Password and Confirm Password field does not match');
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

                    <!-- Responsive Form Starts -->
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title text-center mb-4">Change Password</h4>
                                    <form name="changepassword" method="post" onsubmit="return checkpass();">
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
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block" name="submit">Change</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Responsive Form Ends -->
                </div>
                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
    </div>

    <!-- JavaScript files -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
</body>
</html>

<?php } ?>
