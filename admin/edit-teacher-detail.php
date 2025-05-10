<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $thname = $_POST['thname'];
        $themail = $_POST['themail'];
        $thclass = $_POST['thclass'];
        $gender = $_POST['gender'];
        $thid = $_POST['thid'];
        $connum = $_POST['connum'];
        $uname = $_POST['uname'];
        $password = md5($_POST['password']);
        $eid = $_GET['editid'];

        $sql = "UPDATE tblteacher SET 
                    TeacherName=:thname,
                    Email=:themail,
                    TeacherClass=:thclass,
                    Gender=:gender,
                    TeacherID=:thid,
                    MobileNumber=:connum,
                    UserName=:uname,
                    Password=:password 
                WHERE ID=:eid";

        $query = $dbh->prepare($sql);
        $query->bindParam(':thname', $thname, PDO::PARAM_STR);
        $query->bindParam(':themail', $themail, PDO::PARAM_STR);
        $query->bindParam(':thclass', $thclass, PDO::PARAM_STR);
        $query->bindParam(':gender', $gender, PDO::PARAM_STR);
        $query->bindParam(':thid', $thid, PDO::PARAM_STR);
        $query->bindParam(':connum', $connum, PDO::PARAM_STR);
        $query->bindParam(':uname', $uname, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
        $query->execute();

        echo '<script>alert("Teacher has been updated")</script>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edutrack Pro || Update Teachers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap core and plugins -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <div class="container-scroller">
        <?php include_once('includes/header.php'); ?>
        <div class="container-fluid page-body-wrapper">
            <?php include_once('includes/sidebar.php'); ?>

            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title">Update Teachers</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Update Teachers Details</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card w-100">
                                <div class="card-body">
                                    <h4 class="card-title text-center">Update Teachers Details</h4>
                                    <hr />
                                    <form method="post" enctype="multipart/form-data">
                                        <?php
                                        $eid = $_GET['editid'];
                                        $sql = "SELECT 
                                                    tblteacher.TeacherName,
                                                    tblteacher.id as sid,
                                                    tblteacher.Email,
                                                    tblteacher.TeacherClass,
                                                    tblteacher.Gender,
                                                    tblteacher.TeacherID,
                                                    tblteacher.MobileNumber,
                                                    tblteacher.UserName,
                                                    tblteacher.Password,
                                                    tblteacher.Image,
                                                    tblclass.ClassName,
                                                    tblclass.Section 
                                                FROM tblteacher 
                                                JOIN tblclass ON tblclass.ID = tblteacher.TeacherClass 
                                                WHERE tblteacher.ID = :eid";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) {
                                        ?>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                                                <h4>Teacher Details</h4>
                                                <hr />
                                                <div class="form-group mb-3">
                                                    <label>Teacher Name</label>
                                                    <input type="text" name="thname" class="form-control" value="<?php echo htmlentities($row->TeacherName); ?>" required>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label>Teacher Email</label>
                                                    <input type="email" name="themail" class="form-control" value="<?php echo htmlentities($row->Email); ?>" required>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label>Teacher Class</label>
                                                    <select name="thclass" class="form-control" required>
                                                        <option value="<?php echo htmlentities($row->TeacherClass); ?>">
                                                            <?php echo htmlentities($row->ClassName); ?> <?php echo htmlentities($row->Section); ?>
                                                        </option>
                                                        <?php
                                                        $sql2 = "SELECT * FROM tblclass";
                                                        $query2 = $dbh->prepare($sql2);
                                                        $query2->execute();
                                                        $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                                                        foreach ($result2 as $row1) {
                                                        ?>
                                                        <option value="<?php echo htmlentities($row1->ID); ?>">
                                                            <?php echo htmlentities($row1->ClassName); ?> <?php echo htmlentities($row1->Section); ?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label>Teacher Photo</label><br>
                                                    <img src="images/<?php echo $row->Image; ?>" class="img-fluid rounded mb-2" style="max-width: 100px;" alt="Teacher Image">
                                                    <br><a href="changeteacherimage.php?editid=<?php echo $row->sid; ?>">Edit Image</a>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                                                <br>
                                                <hr />
                                                <div class="form-group mb-3">
                                                    <label>Gender</label>
                                                    <select name="gender" class="form-control" required>
                                                        <option value="<?php echo htmlentities($row->Gender); ?>"><?php echo htmlentities($row->Gender); ?></option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label>Teacher ID</label>
                                                    <input type="text" name="thid" class="form-control" value="<?php echo htmlentities($row->TeacherID); ?>" required>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label>Contact Number</label>
                                                    <input type="text" name="connum" class="form-control" maxlength="10" pattern="[0-9]+" value="<?php echo htmlentities($row->MobileNumber); ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <h4>Login Details</h4>
                                        <div class="form-group mb-3">
                                            <label>User Name</label>
                                            <input type="text" name="uname" class="form-control" value="<?php echo htmlentities($row->UserName); ?>" required>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label>Password</label>
                                            <input type="password" name="password" class="form-control" value="<?php echo htmlentities($row->Password); ?>" required>
                                        </div>

                                        <button type="submit" name="submit" class="btn btn-primary mt-3 btn-block w-100">Update</button>

                                        <?php } } ?>
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

    <!-- Bootstrap and scripts -->
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
