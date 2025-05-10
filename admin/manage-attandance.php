<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

// CSRF Token for security
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function fetchClasses($dbh)
{
    $sql2 = "SELECT * FROM tblclass";
    $query = $dbh->prepare($sql2);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function fetchAttendanceReport($dbh, $month, $year, $class_id = null)
{
    $sql = "SELECT attendance_tbl.student_id, tblstudent.StudentName, attendance_tbl.class_date, attendance_tbl.status
            FROM attendance_tbl 
            JOIN tblstudent ON attendance_tbl.student_id = tblstudent.ID
            WHERE MONTH(attendance_tbl.class_date) = :month AND YEAR(attendance_tbl.class_date) = :year AND tblstudent.StudentClass = :class_id
            ORDER BY tblstudent.StudentName ASC, attendance_tbl.class_date ASC";

    $query = $dbh->prepare($sql);
    $query->bindParam(':month', $month, PDO::PARAM_INT);
    $query->bindParam(':year', $year, PDO::PARAM_INT);
    if ($class_id) {
        $query->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    }
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $month = isset($_POST['month']) ? intval($_POST['month']) : date('m');
    $year = isset($_POST['year']) ? intval($_POST['year']) : date('Y');
    $class_id = isset($_POST['class_id']) ? intval($_POST['class_id']) : null;
    $attendanceReport = fetchAttendanceReport($dbh, $month, $year, $class_id);
} else {
    $month = date('m');
    $year = date('Y');
    $class_id = null;
    $attendanceReport = [];
}

$students = [];
$dates = [];
foreach ($attendanceReport as $row) {
    $students[$row['student_id']] = $row['StudentName'];
    $dates[$row['class_date']] = date('j', strtotime($row['class_date']));
}
ksort($dates);

$attendanceData = [];
foreach ($attendanceReport as $row) {
    $attendanceData[$row['student_id']][$row['class_date']] = $row['status'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edutrack Pro || Monthly Attendance Report</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/style.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f4f7f6;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
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

        @media (max-width: 576px) {

            th,
            td {
                font-size: 0.75rem;
            }

            .form-label {
                font-size: 0.9rem;
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
                        <h3 class="page-title">Manage Attendance</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Manage Attendance</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="container">
                        <h3>Monthly Attendance Report</h3>

                        <form method="post" class="row g-3 align-items-end mb-4">
                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="form-label">Month</label>
                                <select name="month" class="form-control" required>
                                    <?php for ($m = 1; $m <= 12; $m++) { ?>
                                        <option value="<?php echo $m; ?>" <?php echo ($m == $month) ? 'selected' : ''; ?>>
                                            <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="form-label">Year</label>
                                <input type="number" name="year" value="<?php echo $year; ?>" class="form-control" required>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label">Select Class</label>
                                <select name="class_id" id="classid" class="form-control" required>
                                    <option value="">Select Class</option>
                                    <?php
                                    $classes = fetchClasses($dbh);
                                    foreach ($classes as $row1) {
                                    ?>
                                        <option value="<?php echo $row1['ID']; ?>" <?php echo ($class_id == $row1['ID']) ? 'selected' : ''; ?>>
                                            <?php echo $row1['ClassName'] . " " . $row1['Section']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-12 col-md-2 mt-2 mt-md-4">
                                <button type="submit" class="btn btn-primary w-100">Show Report</button>
                            </div>
                        </form>

                        <?php if (!empty($attendanceReport)) { ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th>Students</th>
                                            <?php foreach ($dates as $day) { ?>
                                                <th><?php echo $day; ?></th>
                                            <?php } ?>
                                            <th>Total P</th>
                                            <th>Total L</th>
                                            <th>Total A</th>
                                            <th>Total H</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($students as $id => $name) { ?>
                                            <tr>
                                                <td><strong><?php echo $name; ?></strong></td>
                                                <?php
                                                $tp = $tl = $ta = $th = 0;
                                                foreach ($dates as $date => $day) {
                                                    $status = isset($attendanceData[$id][$date]) ? $attendanceData[$id][$date] : '';
                                                    switch ($status) {
                                                        case 1:
                                                            echo '<td class="present">P</td>';
                                                            $tp++;
                                                            break;
                                                        case 2:
                                                            echo '<td class="late">L</td>';
                                                            $tl++;
                                                            break;
                                                        case 3:
                                                            echo '<td class="absent">A</td>';
                                                            $ta++;
                                                            break;
                                                        case 4:
                                                            echo '<td class="holiday">H</td>';
                                                            $th++;
                                                            break;
                                                        default:
                                                            echo '<td></td>';
                                                            break;
                                                    }
                                                }
                                                ?>
                                                <td><?php echo $tp; ?></td>
                                                <td><?php echo $tl; ?></td>
                                                <td><?php echo $ta; ?></td>
                                                <td><?php echo $th; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-warning text-center mt-3">No attendance records found for the selected month and year.</div>
                        <?php } ?>
                    </div>
                </div>
                <?php include_once('includes/footer.php'); ?>
        
            </div>

        </div>
    </div>
    <!-- Bootstrap JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.b/undle.min.js"></script> -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="./vendors/chart.js/Chart.min.js"></script>
  <script src="./vendors/moment/moment.min.js"></script>
  <script src="./vendors/daterangepicker/daterangepicker.js"></script>
  <script src="./vendors/chartist/chartist.min.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/misc.js"></script>
  <script src="./js/dashboard.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>