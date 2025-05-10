<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

if (isset($_GET['delid'])) {
    $rid = intval($_GET['delid']);
    $sql = "DELETE FROM tblvideo WHERE id = :rid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':rid', $rid, PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Video deleted');</script>";
    echo "<script>window.location.href = 'manage-video.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edutrack Pro || Manage Video</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="vendors/chartist/chartist.min.css">
    <link rel="stylesheet" href="css/style.css">

    <!-- Custom Styles -->
    <style>
        .table-container {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #eee;
            margin-bottom: 30px;
        }

        .table th, .table td {
            vertical-align: middle !important;
        }

        .btn-purple {
            background-color: #8e44ad;
            color: #fff;
        }

        .btn-purple:hover {
            background-color: #732d91;
            color: #fff;
        }

        .btn-pink {
            background-color: #ff3e6c;
            color: #fff;
        }

        .btn-pink:hover {
            background-color: #e7275a;
            color: #fff;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .table-responsive {
                display: none;
            }

            .video-card {
                border: 1px solid #ddd;
                border-radius: 10px;
                padding: 15px;
                margin-bottom: 15px;
                background: #fff;
                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            }

            .video-card h5 {
                font-size: 17px;
                margin-bottom: 10px;
                color: #333;
            }

            .video-card p {
                font-size: 14px;
                margin: 5px 0;
                color: #555;
            }

            .video-card .btn {
                margin-top: 10px;
                margin-right: 10px;
            }
        }

        @media (min-width: 769px) {
            .video-cards-mobile {
                display: none;
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
                        <h3 class="page-title">Manage Video</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Manage Video</li>
                            </ol>
                        </nav>
                    </div>

                    <?php
                    // Modify SQL query to show videos related to the logged-in teacher
                    $teacherId = $_SESSION['sturecmsaid'];
                    $sql = "SELECT tblclass.ClassName, tblclass.Section, tblvideo.videotitle, tblvideo.start_time, tblvideo.end_time, tblvideo.postingDate, tblvideo.id 
                            FROM tblvideo 
                            JOIN tblclass ON tblclass.ID = tblvideo.class_id 
                            WHERE tblvideo.teacherId = :teacherId 
                            ORDER BY tblvideo.id DESC";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':teacherId', $teacherId, PDO::PARAM_STR);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    $cnt = 1;
                    ?>

                    <!-- Desktop Table View -->
                    <div class="table-container">
                        <b>All Videos</b>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>S.No</th>
                                        <th>Title</th>
                                        <th>Class</th>
                                        <th>Section</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Posted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($query->rowCount() > 0) {
                                        foreach ($results as $row) { ?>
                                            <tr>
                                                <td><?php echo $cnt++; ?></td>
                                                <td><?php echo htmlentities($row->videotitle); ?></td>
                                                <td><?php echo htmlentities($row->ClassName); ?></td>
                                                <td><?php echo htmlentities($row->Section); ?></td>
                                                <td><?php echo htmlentities($row->start_time); ?></td>
                                                <td><?php echo htmlentities($row->end_time); ?></td>
                                                <td><?php echo htmlentities($row->postingDate); ?></td>
                                                <td>
                                                    <a href="edit-video.php?vid=<?php echo $row->id; ?>" class="btn btn-purple btn-sm">Edit</a>
                                                    <a href="manage-video.php?delid=<?php echo $row->id; ?>" onclick="return confirm('Do you really want to delete this video?');" class="btn btn-pink btn-sm">Delete</a>
                                                </td>
                                            </tr>
                                    <?php }
                                    } else { ?>
                                        <tr><td colspan="8" class="text-danger text-center">No Record Found</td></tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="video-cards-mobile">
                        <?php
                        $cnt = 1;
                        if (count($results) > 0) {
                            foreach ($results as $row) { ?>
                                <div class="video-card">
                                    <h5><?php echo $cnt++ . '. ' . htmlentities($row->videotitle); ?></h5>
                                    <p><strong>Class:</strong> <?php echo htmlentities($row->ClassName); ?></p>
                                    <p><strong>Section:</strong> <?php echo htmlentities($row->Section); ?></p>
                                    <p><strong>Start:</strong> <?php echo htmlentities($row->start_time); ?></p>
                                    <p><strong>End:</strong> <?php echo htmlentities($row->end_time); ?></p>
                                    <p><strong>Posted:</strong> <?php echo htmlentities($row->postingDate); ?></p>
                                    <a href="edit-video.php?vid=<?php echo $row->id; ?>" class="btn btn-purple btn-sm">Edit</a>
                                    <a href="manage-video.php?delid=<?php echo $row->id; ?>" onclick="return confirm('Do you really want to delete this video?');" class="btn btn-pink btn-sm">Delete</a>
                                </div>
                        <?php }
                        } else { ?>
                            <div class="video-card">
                                <p class="text-danger">No Record Found</p>
                            </div>
                        <?php } ?>
                    </div>

                </div>
                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="vendors/chart.js/Chart.min.js"></script>
    <script src="vendors/moment/moment.min.js"></script>
    <script src="vendors/daterangepicker/daterangepicker.js"></script>
    <script src="vendors/chartist/chartist.min.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
    <script src="js/dashboard.js"></script>
</body>
</html>
