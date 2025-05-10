<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Simulate logged-in teacher for testing only (REMOVE in production)
if (!isset($_SESSION['sturecmsaid'])) {
    $_SESSION['sturecmsaid'] = 1; // Replace with a valid teacher ID from your DB for testing
}

if (!isset($_SESSION['sturecmsaid']) || strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

if (isset($_POST['submit'])) {
    $hwtitle = $_POST['homeworkTitle'];
    $classid = $_POST['classid'];
    $hwdescription = $_POST['homeworkdescription'];
    $ldsubmission = $_POST['ldsubmission'];
    $hwfile = $_FILES["hwfile"]["name"];
    $extension = strtolower(pathinfo($hwfile, PATHINFO_EXTENSION));
    $allowed_extensions = array("pdf", "doc", "docx");

    if (!in_array($extension, $allowed_extensions)) {
        echo "<script>alert('Invalid file format. Only PDF, DOC, and DOCX are allowed.');</script>";
    } else {
        $newhwfile = md5($hwfile . time()) . "." . $extension;
        move_uploaded_file($_FILES["hwfile"]["tmp_name"], "uploadedhw/" . $newhwfile);

        $sql = "INSERT INTO tblhomework(homeworkTitle, classId, homeworkDescription, homeworkfile, lastDateofSubmission, teacherId) 
                VALUES (:hwtitle, :classid, :hwdescription, :newhwfile, :ldsubmission, :teacherid)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':hwtitle', $hwtitle, PDO::PARAM_STR);
        $query->bindParam(':classid', $classid, PDO::PARAM_STR);
        $query->bindParam(':hwdescription', $hwdescription, PDO::PARAM_STR);
        $query->bindParam(':newhwfile', $newhwfile, PDO::PARAM_STR);
        $query->bindParam(':ldsubmission', $ldsubmission, PDO::PARAM_STR);
        $query->bindParam(':teacherid', $_SESSION['sturecmsaid'], PDO::PARAM_STR);
        $query->execute();

        $LastInsertId = $dbh->lastInsertId();
        if ($LastInsertId > 0) {
            echo '<script>alert("Homework has been added.")</script>';
            echo "<script>window.location.href ='manage-homeworks.php'</script>";
        } else {
            echo '<script>alert("Something Went Wrong. Please try again.")</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edutrack Pro || Add Homework</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <h3 class="page-title">Add Homework</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Homework</li>
                        </ol>
                    </nav>
                </div>

                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-center">Add Homework</h4>
                                <form class="forms-sample" method="post" enctype="multipart/form-data">
                                    <div class="row">

                                        <div class="col-12 mb-3">
                                            <label for="homeworkTitle">Homework Title</label>
                                            <input type="text" name="homeworkTitle" class="form-control" required>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label for="classid">Homework For</label>
                                            <select name="classid" class="form-control" required>
                                                <option value="">Select Class</option>
                                                <?php
                                                $sql2 = "SELECT * FROM tblclass";
                                                $query2 = $dbh->prepare($sql2);
                                                $query2->execute();
                                                $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                                                foreach ($result2 as $row1) {
                                                    echo '<option value="' . htmlentities($row1->ID) . '">' . htmlentities($row1->ClassName) . ' ' . htmlentities($row1->Section) . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label for="homeworkdescription">Homework Description</label>
                                            <textarea name="homeworkdescription" class="form-control" rows="6" required></textarea>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label for="hwfile">Homework File (doc or pdf only)</label>
                                            <input type="file" name="hwfile" class="form-control" accept=".doc,.docx,.pdf" required>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label for="ldsubmission">Last Date of Submission</label>
                                            <input type="date" name="ldsubmission" class="form-control" required>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary w-100" name="submit">Add</button>
                                        </div>

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

<script src="vendors/js/vendor.bundle.base.js"></script>
<script src="vendors/select2/select2.min.js"></script>
<script src="vendors/typeahead.js/typeahead.bundle.min.js"></script>
<script src="js/off-canvas.js"></script>
<script src="js/misc.js"></script>
<script src="js/typeahead.js"></script>
<script src="js/select2.js"></script>
</body>
</html>
