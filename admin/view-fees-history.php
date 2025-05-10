<?php
session_start();
include('includes/dbconnection.php');

if (!isset($_GET['sid']) || empty($_GET['sid'])) {
    echo "Invalid Student ID";
    exit();
}

$sid = $_GET['sid'];

// Fetch student info
$sql = "SELECT s.StudentName, s.StuID, c.ClassName, c.Section 
        FROM tblstudent s 
        JOIN tblclass c ON s.StudentClass = c.ID 
        WHERE s.StuID = :sid";
$query = $dbh->prepare($sql);
$query->bindParam(':sid', $sid, PDO::PARAM_STR);
$query->execute();
$student = $query->fetch(PDO::FETCH_OBJ);

// Fetch payment history
$historySql = "SELECT * FROM feespaymenthistory WHERE stuID = :sid ORDER BY ID DESC";
$historyQuery = $dbh->prepare($historySql);
$historyQuery->bindParam(':sid', $sid, PDO::PARAM_STR);
$historyQuery->execute();
$paymentHistory = $historyQuery->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Payment History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <style>
        .mobile-card {
            display: none;
        }
        .desktop-table {
            display: block;
        }

        @media (max-width: 768px) {
            .desktop-table {
                display: none;
            }
            .mobile-card {
                display: block;
            }
            .mobile-card .card {
                border: 1px solid #ddd;
                /* border-radius: 10px; */
                margin-bottom: 15px;
                padding: 15px;
                box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            }
            .mobile-card .card h5 {
                font-size: 18px;
                margin-bottom: 10px;
            }
        }
        .border-left-primary {
        border-left: 4px solid #007bff !important;
        background: #fff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
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
                        <h3 class="page-title">Payment History for <?= htmlentities($student->StudentName) ?></h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Payment History</li>
                            </ol>
                        </nav>
                    </div>

                    <!-- Desktop Table View -->
                    <div class="desktop-table">
                        <?php if (count($paymentHistory) > 0) { ?>
                            <table class="table table-bordered">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Paid Fees</th>
                                        <th>Remaining Fees</th>
                                        <th>Remarks</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($paymentHistory as $row) { ?>
                                        <tr>
                                            <td><?= htmlentities($row->paymentID) ?></td>
                                            <td>₹<?= htmlentities($row->paidfees) ?></td>
                                            <td>₹<?= htmlentities($row->remainingfees) ?></td>
                                            <td><?= htmlentities($row->remark) ?></td>
                                            <td><?= htmlentities($row->paymentDate) ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } else { ?>
                            <div class="alert alert-info">No payment history found.</div>
                        <?php } ?>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="mobile-card">
                        <?php if (count($paymentHistory) > 0) {
                            foreach ($paymentHistory as $row) { ?>
                                <div class="card mb-3 shadow-sm border border-left-primary">
                                    <h5 class="card-title text-primary">Payment ID: <?= htmlentities($row->paymentID) ?></h5>
                                    <p><strong>Paid Fees:</strong> ₹<?= htmlentities($row->paidfees) ?></p>
                                    <p><strong>Remaining Fees:</strong> ₹<?= htmlentities($row->remainingfees) ?></p>
                                    <p><strong>Remarks:</strong> <?= htmlentities($row->remark) ?></p>
                                    <p><strong>Date:</strong> <?= htmlentities($row->paymentDate) ?></p>
                                </div>
                        <?php } } else { ?>
                            <div class="alert alert-info">No payment history found.</div>
                        <?php } ?>
                    </div>

                </div>
                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="vendors/select2/select2.min.js"></script>
    <script src="vendors/typeahead.js/typeahead.bundle.min.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
</body>
</html>
