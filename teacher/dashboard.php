<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edutrack Pro || Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="vendors/chartist/chartist.min.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
        .report-inner-cards-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        .report-inner-card {
            background: #fff;
            border-radius: 10px;
            flex: 1 1 calc(50% - 20px);
            min-height: 150px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease-in-out;
            padding: 20px;
        }

        .report-inner-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .inner-card-text {
            flex: 1;
        }

        .report-title {
            color: #555;
        }

        .report-count {
            color: #007bff;
            text-decoration: none;
        }

        .inner-card-icon {
            color: #fff;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        @media(max-width: 992px) {
            .report-inner-card {
                flex: 1 1 45%;
            }
        }

        @media(max-width: 768px) {
            .report-inner-card {
                flex: 1 1 100%;
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
                    <div class="row">
                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-sm-flex align-items-baseline report-summary-header mb-3">
                                        <h5 class="font-weight-semibold">Report Summary</h5>
                                        <span class="ml-auto text-muted">Updated Report</span>
                                        <button class="btn btn-icons border-0 p-2"><i class="icon-refresh"></i></button>
                                    </div>

                                    <div class="report-inner-cards-wrapper">
                                        <?php
                                        $teacherId = $_SESSION['sturecmsaid'];

                                        // Homework Count for logged-in teacher
                                        $query = $dbh->prepare("SELECT COUNT(*) FROM tblhomework WHERE teacherId = :tid");
                                        $query->bindParam(':tid', $teacherId, PDO::PARAM_STR);
                                        $query->execute();
                                        $homeworkCount = $query->fetchColumn();

                                        // Material Count for logged-in teacher
                                        $query = $dbh->prepare("SELECT COUNT(*) FROM tblmaterial WHERE teacherId = :tid");
                                        $query->bindParam(':tid', $teacherId, PDO::PARAM_STR);
                                        $query->execute();
                                        $materialCount = $query->fetchColumn();
                                        ?>

                                        <div class="report-inner-card">
                                            <div class="inner-card-text">
                                                <span class="report-title">Total Homeworks</span>
                                                <h4><?php echo htmlentities($homeworkCount); ?></h4>
                                                <a href="manage-homeworks.php" class="report-count">View Homework</a>
                                            </div>
                                            <div class="inner-card-icon bg-success">
                                                <i class="icon-doc"></i>
                                            </div>
                                        </div>

                                        <div class="report-inner-card">
                                            <div class="inner-card-text">
                                                <span class="report-title">Total Material</span>
                                                <h4><?php echo htmlentities($materialCount); ?></h4>
                                                <a href="manage-material.php" class="report-count">View Material</a>
                                            </div>
                                            <div class="inner-card-icon bg-primary">
                                                <i class="icon-doc"></i>
                                            </div>
                                        </div>

                                    </div> <!-- /.report-inner-cards-wrapper -->
                                </div> <!-- /.card-body -->
                            </div> <!-- /.card -->
                        </div>
                    </div>
                </div>

                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
    </div>

    <!-- Vendor JS -->
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
<?php } ?>
