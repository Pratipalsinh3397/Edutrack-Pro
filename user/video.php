<?php
session_start();
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsstuid'] == 0)) {
    header('location:logout.php');
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Edutrack Pro || Video Material</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
        <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
        <link rel="stylesheet" href="vendors/select2/select2.min.css">
        <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css" />
        <style>
            .video-card {
                border: 1px solid #dee2e6;
                border-radius: 10px;
                padding: 15px;
                margin-bottom: 15px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            }

            .video-card h5 {
                margin-bottom: 10px;
                font-size: 18px;
            }

            .video-info {
                margin-bottom: 8px;
            }

            .video-label {
                font-weight: 600;
                color: #555;
            }

            @media (min-width: 768px) {
                .video-card {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 20px;
                }

                .video-details {
                    flex: 1;
                }

                .video-action {
                    margin-left: 20px;
                    white-space: nowrap;
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
                            <h3 class="page-title">View Video Material</h3>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">View Video Material</li>
                                </ol>
                            </nav>
                        </div>

                        <div class="row">
                            <div class="col-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <?php
                                        $stuclass = $_SESSION['stuclass'];
                                        $sql = "SELECT tblclass.ID, tblclass.ClassName, tblclass.Section, tblvideo.start_time, tblvideo.end_time, tblvideo.videotitle, tblvideo.id as vid, tblteacher.TeacherName 
                                                FROM tblvideo 
                                                JOIN tblclass ON tblclass.ID = tblvideo.class_Id  
                                                JOIN tblteacher ON tblteacher.ID = tblvideo.teacherId
                                                WHERE tblvideo.class_Id = :stuclass";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':stuclass', $stuclass, PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;

                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) {
                                        ?>
                                                <div class="video-card">
                                                    <div class="video-details">
                                                        <h5><?php echo htmlentities($cnt) . '. ' . htmlentities($row->videotitle); ?></h5>
                                                        <div class="video-info"><span class="video-label">Posting Date:</span> <?php echo htmlentities($row->start_time); ?></div>
                                                        <div class="video-info"><span class="video-label">End Date:</span> <?php echo htmlentities($row->end_time); ?></div>
                                                        <div class="video-info"><span class="video-label">Uploaded By:</span> <?php echo htmlentities($row->TeacherName); ?></div>
                                                    </div>
                                                    <div class="video-action">
                                                        <a href="view-video.php?vid=<?php echo htmlentities($row->vid); ?>" target="_blank" class="btn btn-primary btn-sm">Watch Video</a>
                                                    </div>
                                                </div>
                                        <?php $cnt++;
                                            }
                                        } else { ?>
                                            <div class="alert alert-warning" role="alert">No video found.</div>
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
