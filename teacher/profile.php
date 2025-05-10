<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $teacherid = $_SESSION['sturecmsaid'];
        $tname = $_POST['teachername'];
        $mobno = $_POST['mobilenumber'];
        $email = $_POST['email'];

        $sql = "UPDATE tblteacher SET TeacherName=:teachername, MobileNumber=:mobilenumber, Email=:email WHERE ID=:tid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':teachername', $tname, PDO::PARAM_STR);
        $query->bindParam(':mobilenumber', $mobno, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':tid', $teacherid, PDO::PARAM_STR);
        $query->execute();

        echo '<script>alert("Your profile has been updated")</script>';
        echo "<script>window.location.href='profile.php'</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edutrack Pro || Teacher Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS Files -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div class="container-scroller">
    <?php include_once('includes/header.php'); ?>
    <div class="container-fluid page-body-wrapper">
        <?php include_once('includes/sidebar.php'); ?>
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
                    <h3 class="page-title">Teacher Profile</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </nav>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-center mb-4">Your Profile</h4>

                                <form class="forms-sample" method="post">
                                    <?php
                                    $teacherid = $_SESSION['sturecmsaid'];
                                    $sql = "SELECT * FROM tblteacher WHERE ID = :tid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':tid', $teacherid, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $row) {
                                    ?>
                                    <div class="form-group mb-3">
                                        <label>Teacher Name</label>
                                        <input type="text" name="teachername" value="<?php echo htmlentities($row->TeacherName); ?>" class="form-control" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Username</label>
                                        <input type="text" value="<?php echo htmlentities($row->UserName); ?>" class="form-control" readonly>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Mobile Number</label>
                                        <input type="text" name="mobilenumber" value="<?php echo htmlentities($row->MobileNumber); ?>" class="form-control" maxlength="10" pattern="[0-9]+" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Email</label>
                                        <input type="email" name="email" value="<?php echo htmlentities($row->Email); ?>" class="form-control" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Teacher ID</label>
                                        <input type="text" value="<?php echo htmlentities($row->TeacherID); ?>" class="form-control" readonly>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Class</label>
                                        <input type="text" value="<?php echo htmlentities($row->TeacherClass); ?>" class="form-control" readonly>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Gender</label>
                                        <input type="text" value="<?php echo htmlentities($row->Gender); ?>" class="form-control" readonly>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Registration Date</label>
                                        <input type="text" value="<?php echo htmlentities($row->TeacherRegdate); ?>" class="form-control" readonly>
                                    </div>
                                    <?php }} ?>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary px-4 mt-3" name="submit">Update</button>
                                    </div>
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

<!-- JS Files -->
<script src="vendors/js/vendor.bundle.base.js"></script>
<script src="js/off-canvas.js"></script>
<script src="js/misc.js"></script>
</body>
</html>
<?php } ?>
