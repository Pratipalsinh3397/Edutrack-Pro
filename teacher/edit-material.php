<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $mttitle = $_POST['materialTitle'];
        $classid = $_POST['classid'];
        $mtdescription = $_POST['materialdescription'];
        $mtid = intval($_GET['mtid']);
        $mtfile = $_FILES["mtfile"]["name"];

        if (!empty($mtfile)) {
            $extension = strtolower(pathinfo($mtfile, PATHINFO_EXTENSION));
            $allowed_extensions = array("pdf", "docx", "doc");

            if (!in_array($extension, $allowed_extensions)) {
                echo "<script>alert('Invalid format. Only PDF, DOC, or DOCX allowed');</script>";
                exit();
            }

            $newmtfile = md5($mtfile) . '.' . $extension;
            move_uploaded_file($_FILES["mtfile"]["tmp_name"], "uploadedmt/" . $newmtfile);

            $sql = "UPDATE tblmaterial 
                    SET materialTitle=:mttitle, classId=:classid, materialDescription=:mtdescription, 
                        materialFile=:newmtfile 
                    WHERE id=:mtid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':newmtfile', $newmtfile, PDO::PARAM_STR);
        } else {
            $sql = "UPDATE tblmaterial 
                    SET materialTitle=:mttitle, classId=:classid, materialDescription=:mtdescription 
                    WHERE id=:mtid";
            $query = $dbh->prepare($sql);
        }

        $query->bindParam(':mttitle', $mttitle, PDO::PARAM_STR);
        $query->bindParam(':classid', $classid, PDO::PARAM_STR);
        $query->bindParam(':mtdescription', $mtdescription, PDO::PARAM_STR);
        $query->bindParam(':mtid', $mtid, PDO::PARAM_STR);
        $query->execute();

        echo '<script>alert("Material has been updated successfully.")</script>';
        echo "<script>window.location.href ='manage-material.php'</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edutrack Pro || Edit Material</title>
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
                        <h3 class="page-title text-center text-md-left">Edit Material</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Material</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title text-center text-md-left mb-4">Edit Material</h4>

                                    <?php
                                    $mtid = intval($_GET['mtid']);
                                    $sql = "SELECT tblclass.ID, tblclass.ClassName, tblclass.Section, 
                                            tblmaterial.materialTitle, tblmaterial.materialFile, 
                                            tblmaterial.postingDate, tblmaterial.id as mtid, materialDescription 
                                            FROM tblmaterial 
                                            JOIN tblclass ON tblclass.ID = tblmaterial.classId 
                                            WHERE tblmaterial.id=:mtid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':mtid', $mtid, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($results as $row) { ?>

                                        <form class="forms-sample" method="post" enctype="multipart/form-data">
                                            <div class="form-group mb-3">
                                                <label>Material Title</label>
                                                <input type="text" name="materialTitle"
                                                    value="<?php echo htmlentities($row->materialTitle); ?>"
                                                    class="form-control" required>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label>Material For</label>
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
                                                <label>Material Description</label>
                                                <textarea name="materialdescription" class="form-control" required rows="6"><?php echo htmlentities($row->materialDescription); ?></textarea>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label>File</label>
                                                <input type="file" name="mtfile" class="form-control" accept=".pdf, .doc, .docx">
                                                <?php if ($row->materialFile) { ?>
                                                    <small class="d-block mt-2">Current File:
                                                        <a href="uploadedmt/<?php echo htmlentities($row->materialFile); ?>" target="_blank">
                                                            View File
                                                        </a>
                                                    </small>
                                                <?php } ?>
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
