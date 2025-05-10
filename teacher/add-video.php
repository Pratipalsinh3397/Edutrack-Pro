<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
date_default_timezone_set('Asia/Kolkata');

// ✅ Delete expired videos and their files
$currentTime = date('Y-m-d H:i:s');

// Step 1: Fetch expired videos and delete files
$expiredVideos = $dbh->prepare("SELECT video_path FROM tblvideo WHERE end_time < :currentTime");
$expiredVideos->bindParam(':currentTime', $currentTime, PDO::PARAM_STR);
$expiredVideos->execute();
$videos = $expiredVideos->fetchAll(PDO::FETCH_OBJ);

foreach ($videos as $v) {
    $filePath = $v->video_path;
    if (file_exists($filePath)) {
        unlink($filePath); // Delete the physical video file
    }
}

// Step 2: Delete expired videos from DB
$deleteExpired = "DELETE FROM tblvideo WHERE end_time < :currentTime";
$deleteQuery = $dbh->prepare($deleteExpired);
$deleteQuery->bindParam(':currentTime', $currentTime, PDO::PARAM_STR);
$deleteQuery->execute();

// ✅ Session Check
if (strlen($_SESSION['sturecmsaid'] == 0)) {
    header('location:logout.php');
} else {
    // ✅ Get Teacher ID from session to store with video data
    $teacherid = $_SESSION['sturecmsaid'];

    if (isset($_POST['submit'])) {
        $videoName = $_FILES['video']['name'];
        $temp = $_FILES['video']['tmp_name'];
        $folder = "uploadedmt/" . $videoName;

        $startTime = $_POST['start_time'];
        $endTime = $_POST['end_time'];
        $videoTitle = $_POST['videoTitle'];
        $classid = $_POST['classid'];

        move_uploaded_file($temp, $folder);

        // ✅ Updated SQL with teacher_id
        $sql = "INSERT INTO tblvideo(teacherId, class_id, videotitle, video_name, video_path, start_time, end_time) 
                VALUES(:teacher_id, :classid, :videoTitle, :video_name, :video_path, :start_time, :end_time)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':teacher_id', $teacherid, PDO::PARAM_INT);
        $query->bindParam(':classid', $classid, PDO::PARAM_INT);
        $query->bindParam(':videoTitle', $videoTitle, PDO::PARAM_STR);
        $query->bindParam(':video_name', $videoName, PDO::PARAM_STR);
        $query->bindParam(':video_path', $folder, PDO::PARAM_STR);
        $query->bindParam(':start_time', $startTime, PDO::PARAM_STR);
        $query->bindParam(':end_time', $endTime, PDO::PARAM_STR);
        $query->execute();

        $LastInsertId = $dbh->lastInsertId();
        if ($LastInsertId > 0) {
            echo "<script>alert('Video Uploaded Successfully');</script>";
            echo "<script>window.location.href ='add-video.php'</script>";
        } else {
            echo '<script>alert("Something Went Wrong. Please try again")</script>';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edutrack Pro || Add Video</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <style>
        @media (max-width: 576px) {
            .card-body {
                padding: 1rem !important;
            }

            form.container {
                padding: 1rem !important;
                margin: 0 !important;
                border: none !important;
                box-shadow: none !important;
            }

            .page-title,
            .card-title {
                font-size: 1.2rem;
                text-align: center;
            }

            .btn {
                width: 100% !important;
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
                        <h3 class="page-title">Add Video</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Video</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card w-100">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Add Video</h4>
                                    <form method="post" enctype="multipart/form-data" class="container mt-4 p-4 border rounded shadow-sm bg-light">
                                        <div class="form-group mb-3">
                                            <label>Material For</label>
                                            <select name="classid" class="form-control" required>
                                                <option value="">Select Class</option>
                                                <?php
                                                $sql2 = "SELECT * from tblclass";
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
                                            <label>Video Title</label>
                                            <input type="text" name="videoTitle" class="form-control" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label>Upload Video</label>
                                            <input class="form-control" type="file" name="video" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label>Start Time</label>
                                            <input class="form-control" type="datetime-local" name="start_time" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label>End Time</label>
                                            <input class="form-control" type="datetime-local" name="end_time" required>
                                        </div>

                                        <button type="submit" name="submit" class="btn btn-primary">Upload Video</button>
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
<?php } ?>
