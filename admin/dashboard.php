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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edutrack Pro || Dashboard</title>

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="vendors/chartist/chartist.min.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Responsive Card Wrapper */
        .report-inner-cards-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: left;
        }

        .report-inner-card {
            background: #fff;
            border-radius: 10px;
            flex: 1 1 250px;
            max-width: 40 0px;
            min-height: 150px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease-in-out;
        }

        .report-inner-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .inner-card-text {
            flex: 1;
        }

        .report-title {
            /* font-size: 14px; */
            color: #555;
        }

        .report-count {
            /* font-size: 12px; */
            color: #007bff;
            text-decoration: none;
        }

        .inner-card-icon {
            /* font-size: 30px; */
            color: #fff;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Responsive Media Queries */
        @media(max-width: 992px) {
            .report-inner-card {
                flex: 1 1 45%;
                max-width: 45%;
            }
        }

        @media(max-width: 768px) {
            .report-inner-card {
                flex: 1 1 100%;
                max-width: 100%;
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
                                        $entities = [
                                            ['tblclass', 'Total Class', 'manage-class.php', 'icon-grid menu-icon', 'bg-success'],
                                            ['tblstudent', 'Total Students', 'manage-students.php', 'icon-graduation menu-icon', 'bg-danger'],
                                            ['tblteacher', 'Total Teachers', 'manage-teacher.php', 'icon-user-following menu-icon', 'bg-info'],
                                            ['tblnotice', 'Total Class Notice', 'manage-notice.php', 'icon-bell', 'bg-warning'],
                                            ['tblpublicnotice', 'Total Public Notice', 'manage-public-notice.php', 'icon-globe menu-icon', 'bg-primary'],
                                        ];

                                        foreach ($entities as $entity) {
                                            $sql = "SELECT COUNT(*) FROM {$entity[0]}";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $total = $query->fetchColumn();

                                            echo "
                                            <div class='report-inner-card'>
                                                <div class='inner-card-text'>
                                                    <span class='report-title'>{$entity[1]}</span>
                                                    <h4>$total</h4>
                                                    <a href='{$entity[2]}' class='report-count'>View</a>
                                                </div>
                                                <div class='inner-card-icon {$entity[4]}'>
                                                    <i class='{$entity[3]}'></i>
                                                </div>
                                            </div>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
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
