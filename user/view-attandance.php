<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (!isset($_SESSION['sturecmsuid'])) {
    header('location:logout.php');
    exit();
}

$student_id = $_SESSION['sturecmsuid'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $month = isset($_POST['month']) ? intval($_POST['month']) : date('m');
    $year = isset($_POST['year']) ? intval($_POST['year']) : date('Y');
} else {
    $month = date('m');
    $year = date('Y');
}

function fetchStudentAttendance($dbh, $student_id, $month, $year)
{
    $sql = "SELECT class_date, status FROM attendance_tbl 
            WHERE student_id = :student_id 
            AND MONTH(class_date) = :month 
            AND YEAR(class_date) = :year
            ORDER BY class_date ASC";

    $query = $dbh->prepare($sql);
    $query->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $query->bindParam(':month', $month, PDO::PARAM_INT);
    $query->bindParam(':year', $year, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

$attendanceReport = fetchStudentAttendance($dbh, $student_id, $month, $year);

$attendanceData = [];
$dates = [];
$totalP = $totalL = $totalA = $totalH = 0;

foreach ($attendanceReport as $row) {
    $date = date('j', strtotime($row['class_date']));
    $dates[$row['class_date']] = $date;
    $attendanceData[$row['class_date']] = $row['status'];

    switch ($row['status']) {
        case 1: $totalP++; break;
        case 2: $totalL++; break;
        case 3: $totalA++; break;
        case 4: $totalH++; break;
    }
}

ksort($dates);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edutrack Pro || View Attendance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Stylesheets -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <style>
        body {
            background-color: #f4f7f6;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        form label {
            font-weight: 500;
        }

        form select,
        form input,
        form button {
            padding: 8px 10px;
            font-size: 14px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            margin-top: 15px;
        }

        table th,
        table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .present {
            color: green;
            font-weight: bold;
        }

        .late {
            color: orange;
            font-weight: bold;
        }

        .absent {
            color: red;
            font-weight: bold;
        }

        .holiday {
            color: blue;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            form {
                flex-direction: column;
                align-items: stretch;
            }

            form select,
            form input,
            form button {
                width: 100%;
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
                        <h3 class="page-title">View Attendance</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">View Attendance</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="container">
                        <h3>My Attendance Report</h3>

                        <form method="post">
                            <label>Month:</label>
                            <select name="month" class="form-select">
                                <?php for ($m = 1; $m <= 12; $m++) { ?>
                                    <option value="<?php echo $m; ?>" <?php echo ($m == $month) ? 'selected' : ''; ?>>
                                        <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <label>Year:</label>
                            <input type="number" name="year" value="<?php echo $year; ?>" class="form-control" required>
                            <button type="submit" class="btn btn-primary">Show Report</button>
                        </form>

                        <?php if (!empty($attendanceReport)) { ?>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                    <?php foreach ($dates as $date => $day) { ?>
                                        <tr>
                                            <td><?php echo $day; ?></td>
                                            <td>
                                                <?php
                                                $status = isset($attendanceData[$date]) ? $attendanceData[$date] : '';
                                                switch ($status) {
                                                    case 1: echo '<span class="present">Present</span>'; break;
                                                    case 2: echo '<span class="late">Late</span>'; break;
                                                    case 3: echo '<span class="absent">Absent</span>'; break;
                                                    case 4: echo '<span class="holiday">Holiday</span>'; break;
                                                    default: echo '-';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <th>Total</th>
                                        <td>
                                            <span class="present">P: <?php echo $totalP; ?></span> |
                                            <span class="late">L: <?php echo $totalL; ?></span> |
                                            <span class="absent">A: <?php echo $totalA; ?></span> |
                                            <span class="holiday">H: <?php echo $totalH; ?></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-warning text-center mt-3">
                                No attendance records found for the selected month and year.
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- ✅ jQuery -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="vendors/select2/select2.min.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/misc.js"></script>

    <!-- ✅ Select2 Init -->
    <script>
        $(document).ready(function () {
            $('select.form-select').select2({
                theme: 'bootstrap',
                width: '100%'
            });
        });
    </script>
</body>
</html>
