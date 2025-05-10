<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $nottitle = $_POST['nottitle'];
        $classid = $_POST['classid'];
        $notmsg = $_POST['notmsg'];

        $newntfile = NULL;

        if (!empty($_FILES["ntfile"]["name"])) {
            $ntfile = $_FILES["ntfile"]["name"];
            $extension = substr($ntfile, strlen($ntfile) - 4, strlen($ntfile));
            $allowed_extensions = array(".pdf", ".docx", ".doc", ".PDF");

            if (in_array($extension, $allowed_extensions)) {
                $newntfile = md5($ntfile) . $extension;
                move_uploaded_file($_FILES["ntfile"]["tmp_name"], "uploadednt/" . $newntfile);
            } else {
                echo "<script>alert('Invalid format. Only PDF / DOC format allowed');</script>";
            }
        }

        $sql = "INSERT INTO tblnotice (NoticeTitle, ClassId, NoticeMsg, noticeFile) VALUES (:nottitle, :classid, :notmsg, :newntfile)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':nottitle', $nottitle, PDO::PARAM_STR);
        $query->bindParam(':classid', $classid, PDO::PARAM_STR);
        $query->bindParam(':notmsg', $notmsg, PDO::PARAM_STR);
        $query->bindParam(':newntfile', $newntfile, PDO::PARAM_STR);
        $query->execute();

        $LastInsertId = $dbh->lastInsertId();
        if ($LastInsertId > 0) {
            echo '<script>alert("Notice has been added.")</script>';
            echo "<script>window.location.href ='add-notice.php'</script>";
        } else {
            echo '<script>alert("Something Went Wrong. Please try again")</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edutrack Pro || Add Notice</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Styles -->
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
                        <h3 class="page-title">Add Notice</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Notice</li>
                            </ol>
                        </nav>
                    </div>

                    <!-- Responsive Row -->
                    <div class="row justify-content-center">
                        <div class="col-12 col-md col-lg grid-margin stretch-card">
                            <div class="card">
                                <!-- CARD BODY START - DO NOT EDIT -->
                                <div class="card-body">
                                    <h4 class="card-title" style="text-align: center;">Add Notice</h4>
                                    <form class="forms-sample" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="exampleInputName1">Notice Title</label>
                                            <input type="text" name="nottitle" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail3">Notice For</label>
                                            <select name="classid" class="form-control" required>
                                                <option value="">Select Class</option>
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
                                        <div class="form-group">
                                            <label for="exampleInputName1">Notice Message</label>
                                            <textarea name="notmsg" class="form-control" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputName1">Notice File (Optional, doc or pdf only)</label>
                                            <input class="form-control" type="file" name="ntfile" accept=".doc, .docx, .pdf">
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3 btn-block w-100" name="submit">Add</button>
                                    </form>
                                </div>
                                <!-- CARD BODY END -->
                            </div>
                        </div>
                    </div>

                </div>
                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="vendors/select2/select2.min.js"></script>
    <script src="vendors/typeahead.js/typeahead.bundle.min.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
    <script src="js/typeahead.js"></script>
    <script src="js/select2.js"></script>
</body>

</html>
