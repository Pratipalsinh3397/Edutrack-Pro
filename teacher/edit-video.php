<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
date_default_timezone_set('Asia/Kolkata');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
} else {
    $vid = intval($_GET['vid']);

    if (isset($_POST['submit'])) {
        $videoTitle = $_POST['videoTitle'];
        $class_id = $_POST['classid'];
        $startTime = $_POST['start_time'];
        $endTime = $_POST['end_time'];

        $videoFile = $_FILES['video']['name'];
        if (!empty($videoFile)) {
            $extension = strtolower(pathinfo($videoFile, PATHINFO_EXTENSION));
            $allowed_extensions = array("mp4", "webm", "ogg");

            if (!in_array($extension, $allowed_extensions)) {
                echo "<script>alert('Invalid format. Only MP4, WEBM, or OGG allowed');</script>";
                exit();
            }

            $newVideoFile = md5($videoFile . time()) . '.' . $extension;
            move_uploaded_file($_FILES["video"]["tmp_name"], "uploadedmt/" . $newVideoFile);

            $sql = "UPDATE tblvideo 
                    SET class_id=:class_id, videotitle=:videoTitle, 
                        video_name=:videoName, video_path=:videoPath, 
                        start_time=:startTime, end_time=:endTime 
                    WHERE id=:vid";
        } else {
            $sql = "UPDATE tblvideo 
                    SET class_id=:class_id, videotitle=:videoTitle, 
                        start_time=:startTime, end_time=:endTime 
                    WHERE id=:vid";
        }

        $query = $dbh->prepare($sql);
        $query->bindParam(':class_id', $class_id, PDO::PARAM_INT);
        $query->bindParam(':videoTitle', $videoTitle, PDO::PARAM_STR);
        $query->bindParam(':startTime', $startTime, PDO::PARAM_STR);
        $query->bindParam(':endTime', $endTime, PDO::PARAM_STR);
        $query->bindParam(':vid', $vid, PDO::PARAM_INT);

        if (!empty($videoFile)) {
            $query->bindParam(':videoName', $newVideoFile, PDO::PARAM_STR);
            $videoPath = "uploadedmt/" . $newVideoFile;
            $query->bindParam(':videoPath', $videoPath, PDO::PARAM_STR);
        }

        $query->execute();
        echo "<script>alert('Video has been updated successfully.');</script>";
        echo "<script>window.location.href='manage-video.php'</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edutrack Pro || Edit Video</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <style>
        @media (max-width: 767px) {
            .card-body {
                padding: 1rem;
            }
            .page-title {
                font-size: 1.2rem;
            }
            .btn {
                font-size: 1rem;
                padding: 0.6rem;
            }
            label, .form-control {
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        <?php include_once('includes/header.php'); ?>
        <div class="container-fluid page-body-wrapper">
            <?php include_once('includes/sidebar.php'); ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title">Edit Video</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Video</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card w-100">
                                <div class="card-body">
                                    <h4 class="card-title text-center">Edit Video</h4>
                                    <?php
                                    $sql = "SELECT tblvideo.*, tblclass.ClassName, tblclass.Section 
                                            FROM tblvideo 
                                            JOIN tblclass ON tblvideo.class_id = tblclass.ID 
                                            WHERE tblvideo.id=:vid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':vid', $vid, PDO::PARAM_INT);
                                    $query->execute();
                                    $result = $query->fetch(PDO::FETCH_OBJ);

                                    if ($result) {
                                    ?>
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label>Video Title</label>
                                                <input type="text" name="videoTitle" value="<?php echo htmlentities($result->videotitle); ?>" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Class & Section</label>
                                                <select name="classid" class="form-control" required>
                                                    <option value="<?php echo htmlentities($result->class_id); ?>">
                                                        <?php echo htmlentities($result->ClassName . " " . $result->Section); ?>
                                                    </option>
                                                    <?php
                                                    $sql2 = "SELECT * FROM tblclass";
                                                    $query2 = $dbh->prepare($sql2);
                                                    $query2->execute();
                                                    $classes = $query2->fetchAll(PDO::FETCH_OBJ);
                                                    foreach ($classes as $cls) {
                                                        if ($cls->ID != $result->class_id) {
                                                    ?>
                                                            <option value="<?php echo htmlentities($cls->ID); ?>">
                                                                <?php echo htmlentities($cls->ClassName . " " . $cls->Section); ?>
                                                            </option>
                                                    <?php }} ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Replace Video (optional)</label>
                                                <input type="file" name="video" class="form-control" accept="video/*">
                                                <?php if (!empty($result->video_path)) { ?>
                                                    <p class="mt-2">Current Video:</p>
                                                    <video width="100%" controls>
                                                        <source src="<?php echo htmlentities($result->video_path); ?>" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                <?php } ?>
                                            </div>

                                            <div class="form-group">
                                                <label>Start Time</label>
                                                <input type="datetime-local" name="start_time" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($result->start_time)); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>End Time</label>
                                                <input type="datetime-local" name="end_time" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($result->end_time)); ?>" required>
                                            </div>

                                            <button type="submit" name="submit" class="btn btn-primary w-100">Update Video</button>
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