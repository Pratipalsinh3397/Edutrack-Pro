<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $hwtitle = $_POST['homeworkTitle'];
        $classid = $_POST['classid'];
        $hwdescription = $_POST['homeworkdescription'];
        $ldsubmission = $_POST['ldsubmission'];
        $hwid = intval($_GET['hwid']);
        $hwfile = $_FILES["hwfile"]["name"];

        if (!empty($hwfile)) {
            $extension = strtolower(pathinfo($hwfile, PATHINFO_EXTENSION));
            $allowed_extensions = array("pdf", "docx", "doc");

            if (!in_array($extension, $allowed_extensions)) {
                echo "<script>alert('Invalid format. Only PDF, DOC, or DOCX allowed');</script>";
                exit();
            }

            $newhwfile = md5($hwfile) . '.' . $extension;
            move_uploaded_file($_FILES["hwfile"]["tmp_name"], "uploadedhw/" . $newhwfile);

            $sql = "UPDATE tblhomework 
                    SET homeworkTitle=:hwtitle, classId=:classid, homeworkDescription=:hwdescription, 
                        homeworkFile=:newhwfile, lastDateofSubmission=:ldsubmission 
                    WHERE id=:hwid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':newhwfile', $newhwfile, PDO::PARAM_STR);
        } else {
            $sql = "UPDATE tblhomework 
                    SET homeworkTitle=:hwtitle, classId=:classid, homeworkDescription=:hwdescription, 
                        lastDateofSubmission=:ldsubmission 
                    WHERE id=:hwid";
            $query = $dbh->prepare($sql);
        }

        $query->bindParam(':hwtitle', $hwtitle, PDO::PARAM_STR);
        $query->bindParam(':classid', $classid, PDO::PARAM_STR);
        $query->bindParam(':hwdescription', $hwdescription, PDO::PARAM_STR);
        $query->bindParam(':ldsubmission', $ldsubmission, PDO::PARAM_STR);
        $query->bindParam(':hwid', $hwid, PDO::PARAM_STR);
        $query->execute();

        echo '<script>alert("Homework has been Updated.")</script>';
        echo "<script>window.location.href ='manage-homeworks.php'</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edutrack Pro || Edit Homework</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <!-- Layout styles -->
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
                        <h3 class="page-title text-center text-md-left">Edit Homework</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Homework</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row">
                        <!-- Full-width layout on desktop, responsive on mobile -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title text-center text-md-left mb-4">Edit Homework</h4>
                                    <?php
                                    $hwid = intval($_GET['hwid']);
                                    $sql = "SELECT tblclass.ID, tblclass.ClassName, tblclass.Section, tblhomework.homeworkTitle, 
                                                   tblhomework.homeworkFile, tblhomework.postingDate, tblhomework.lastDateofSubmission, 
                                                   tblhomework.id as hwid, homeworkDescription 
                                            FROM tblhomework 
                                            JOIN tblclass ON tblclass.ID = tblhomework.classId 
                                            WHERE tblhomework.id = :hwid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':hwid', $hwid, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($results as $row) { ?>
                                        <form class="forms-sample" method="post" enctype="multipart/form-data">
                                            <div class="form-group mb-3">
                                                <label>Homework Title</label>
                                                <input type="text" name="homeworkTitle" value="<?php echo htmlentities($row->homeworkTitle); ?>" class="form-control" required>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label>Homework For</label>
                                                <select name="classid" class="form-control" required>
                                                    <option value="<?php echo htmlentities($row->ID); ?>">
                                                        <?php echo htmlentities($row->ClassName . " " . $row->Section); ?>
                                                    </option>
                                                    <?php
                                                    $sql2 = "SELECT * FROM tblclass";
                                                    $query2 = $dbh->prepare($sql2);
                                                    $query2->execute();
                                                    $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                                                    foreach ($result2 as $row1) { ?>
                                                        <option value="<?php echo htmlentities($row1->ID); ?>">
                                                            <?php echo htmlentities($row1->ClassName . " " . $row1->Section); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label>Homework Description</label>
                                                <textarea name="homeworkdescription" class="form-control" rows="6" required><?php echo htmlentities($row->homeworkDescription); ?></textarea>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label>File</label>
                                                <input type="file" name="hwfile" class="form-control" accept=".pdf, .doc, .docx">
                                                <?php if ($row->homeworkFile) { ?>
                                                    <small class="d-block mt-2">Current File: 
                                                        <a href="uploadedhw/<?php echo htmlentities($row->homeworkFile); ?>" target="_blank">View File</a>
                                                    </small>
                                                <?php } ?>
                                            </div>

                                            <div class="form-group mb-4">
                                                <label>Last Date of Submission</label>
                                                <input type="date" name="ldsubmission" value="<?php echo htmlentities($row->lastDateofSubmission); ?>" class="form-control" required>
                                            </div>

                                            <button type="submit" class="btn btn-primary w-100" name="submit">Update</button>
                                        </form>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
    </div>

    <!-- plugins:js -->
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
