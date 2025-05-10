<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

$class_id = isset($_GET['classid']) ? htmlspecialchars($_GET['classid']) : "";

function fetchStudentsWithFees($dbh, $class_id)
{
    if (empty($class_id)) return [];

    $sql = "
        SELECT 
            s.StuID AS sid, 
            s.StudentName, 
            c.fees AS totalfees, 
            IFNULL(c.fees - f.remainingfees, 0) AS paidfees,
            CASE 
                WHEN IFNULL(f.paidfees, 0) = 0 THEN c.fees
                ELSE (f.totalfees - f.paidfees)
            END AS remainingfees
        FROM tblstudent s
        JOIN tblclass c ON s.StudentClass = c.ID
        LEFT JOIN feespayment f ON s.StuID = f.stuID
        WHERE s.StudentClass = :class_id
    ";

    $query = $dbh->prepare($sql);
    $query->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_OBJ);
}

$studentList = (!empty($class_id)) ? fetchStudentsWithFees($dbh, $class_id) : [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edutrack Pro || Manage Fees Payment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/style.css" />
    <style>
        .card-fees {
            background: #f9f9f9;
            border-left: 4px solid #4b49ac;
            margin-bottom: 15px;
        }

        .card-fees .card-body {
            padding: 15px;
        }

        .card-fees .btn {
            margin-top: 10px;
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
                        <h3 class="page-title">Manage Fees Payment</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Manage Fees Payment</li>
                            </ol>
                        </nav>
                    </div>

                    <form action="" method="get">
                        <label for="classid">Select Class</label>
                        <div class="row mb-3">
                            <div class="col-md-6 col-12">
                                <select name="classid" id="classid" class="form-control" required>
                                    <option value="">Select Class</option>
                                    <?php
                                    $query = $dbh->prepare("SELECT * FROM tblclass");
                                    $query->execute();
                                    foreach ($query->fetchAll(PDO::FETCH_OBJ) as $row) {
                                        echo '<option value="' . $row->ID . '"' . ($class_id == $row->ID ? 'selected' : '') . '>' . $row->ClassName . ' ' . $row->Section . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 col-12 mt-2 mt-md-0">
                                <button type="submit" class="btn btn-primary w-100">Show Students</button>
                            </div>
                        </div>
                    </form>

                    <?php if (!empty($studentList)) { ?>

                        <!-- Desktop Table -->
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-bordered">
                                <thead class="bg-primary text-light">
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                        <th>Total Fees</th>
                                        <th>Paid Fees</th>
                                        <th>Remaining Fees</th>
                                        <th>Payment History</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($studentList as $row) { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row->sid) ?></td>
                                            <td><?= htmlspecialchars($row->StudentName) ?></td>
                                            <td><?= htmlspecialchars($row->totalfees) ?></td>
                                            <td><?= htmlspecialchars($row->paidfees) ?></td>
                                            <td><?= htmlspecialchars($row->remainingfees) ?></td>
                                            <td>
                                                <a href="view-fees-history.php?sid=<?= urlencode($row->sid) ?>" class="btn btn-info btn-xs" target="_blank">Payment History</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Cards -->
                        <div class="d-block d-md-none">
                            <?php foreach ($studentList as $row) { ?>
                                <div class="card card-fees shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary"><strong>Student Name:</strong> <?= htmlspecialchars($row->StudentName) ?></h5>
                                        <p><strong>ID:</strong> <?= htmlspecialchars($row->sid) ?></p>
                                        <p><strong>Total Fees:</strong> ₹<?= htmlspecialchars($row->totalfees) ?></p>
                                        <p><strong>Paid Fees:</strong> ₹<?= htmlspecialchars($row->paidfees) ?></p>
                                        <p><strong>Remaining Fees:</strong> ₹<?= htmlspecialchars($row->remainingfees) ?></p>
                                        <a href="view-fees-history.php?sid=<?= urlencode($row->sid) ?>" class="btn btn-sm btn-info w-100" target="_blank">View Payment History</a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                    <?php } else { ?>
                        <div class="alert alert-warning text-center mt-4">No students found for the selected class.</div>
                    <?php } ?>

                </div>
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
