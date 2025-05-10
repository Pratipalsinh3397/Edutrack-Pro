<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$class_id = isset($_REQUEST['classid']) ? htmlspecialchars($_REQUEST['classid']) : "";
$class_date = isset($_REQUEST['class_date']) ? htmlspecialchars($_REQUEST['class_date']) : "";

function fetchStudents($dbh, $class_id)
{
    if (empty($class_id)) return [];
    $sql = "SELECT ID as sid, StudentName FROM tblstudent WHERE StudentClass = :class_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_OBJ);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }

    if (empty($_POST['status'])) {
        $_SESSION['flashdata'] = ["type" => "warning", "msg" => "No attendance status selected for any student."];
        header("Location: add-attandance.php?classid=$class_id&class_date=$class_date");
        exit();
    }

    $class_id = $_POST['classid'];
    $class_date = $_POST['class_date'];
    $student_ids = $_POST['student_id'];
    $statuses = $_POST['status'];

    foreach ($student_ids as $sid) {
        $stat = isset($statuses[$sid]) ? $statuses[$sid] : 3;

        if ($stat == 3) {
            $stmt = $dbh->prepare("SELECT StudentName, StudentEmail, UserName FROM tblstudent WHERE ID = ?");
            $stmt->execute([$sid]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($student) {
                $stuname = $student['StudentName'];
                $stuemail = $student['StudentEmail'];
                $uname = $student['UserName'];

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'typrojectphd@gmail.com';
                    $mail->Password = 'qsktvvyjhhntufez';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = 465;

                    $mail->setFrom('typrojectphd@gmail.com', 'Attendance Notice');
                    $mail->addAddress($stuemail, $stuname);

                    $mail->isHTML(true);
                    $mail->Subject = 'Attendance Notice - You Were Absent';
                    $mail->Body = 'Dear ' . $stuname . ',<br><br>'
                        . 'You were marked <b>Absent</b> on <b>' . date("d-m-Y", strtotime($class_date)) . '</b> for your class.<br><br>'
                        . 'If this is a mistake, please contact your teacher immediately.<br><br>'
                        . 'Regards,<br>Edutrack Pro';

                    $mail->send();
                } catch (Exception $e) {
                    error_log("Mail Error ({$stuemail}): " . $mail->ErrorInfo);
                }
            }
        }

        $check = $dbh->prepare("SELECT id FROM attendance_tbl WHERE student_id = ? AND class_date = ?");
        $check->execute([$sid, $class_date]);

        if ($check->rowCount() > 0) {
            $att_id = $check->fetch(PDO::FETCH_ASSOC)['id'];
            $update = $dbh->prepare("UPDATE attendance_tbl SET status = ? WHERE id = ?");
            $update->execute([$stat, $att_id]);
        } else {
            $insert = $dbh->prepare("INSERT INTO attendance_tbl (student_id, class_date, status) VALUES (?, ?, ?)");
            $insert->execute([$sid, $class_date, $stat]);
        }
    }

    $_SESSION['flashdata'] = ["type" => "success", "msg" => "Attendance saved successfully."];
    header("Location: add-attandance.php?classid=$class_id&class_date=$class_date");
    exit();
}

$studentList = (!empty($class_id)) ? fetchStudents($dbh, $class_id) : [];

$existing_attendance = [];
if (!empty($studentList) && !empty($class_date)) {
    $ids = array_map(fn($stu) => $stu->sid, $studentList);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql = "SELECT student_id, status FROM attendance_tbl WHERE class_date = ? AND student_id IN ($placeholders)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array_merge([$class_date], $ids));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $existing_attendance[$row['student_id']] = $row['status'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edutrack Pro || Add Attendance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <link rel="stylesheet" href="vendors/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="vendors/chartist/chartist.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container-scroller">
        <?php include_once('includes/header.php'); ?>
        <div class="container-fluid page-body-wrapper">
            <?php include_once('includes/sidebar.php'); ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title"> Add Attandance </h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Attandance</li>
                            </ol>
                        </nav>
                    </div>

                    <?php if (isset($_SESSION['flashdata'])) { ?>
                        <div class="alert alert-<?php echo $_SESSION['flashdata']['type']; ?>">
                            <?php echo $_SESSION['flashdata']['msg'];
                            unset($_SESSION['flashdata']); ?>
                        </div>
                    <?php } ?>

                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form action="" method="get">
                                        <div class="row mb-3">
                                            <div class="col-md-6 col-sm-12 mb-2">
                                                <label for="classid">Select Class</label>
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
                                            <div class="col-md-6 col-sm-12 mb-2">
                                                <label for="class_date">Date</label>
                                                <input type="date" name="class_date" id="class_date" value="<?php echo $class_date; ?>" class="form-control" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary mb-3 btn-block w-100">Show Students</button>
                                    </form>

                                    <?php if (!empty($studentList)) { ?>
                                        <form method="post">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <input type="hidden" name="classid" value="<?php echo $class_id; ?>">
                                            <input type="hidden" name="class_date" value="<?php echo $class_date; ?>">

                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead class="bg-primary text-light">
                                                        <tr>
                                                            <th>Student Name</th>
                                                            <th>Present <input type="checkbox" class="checkAll" data-status="1"></th>
                                                            <th>Late <input type="checkbox" class="checkAll" data-status="2"></th>
                                                            <th>Absent <input type="checkbox" class="checkAll" data-status="3"></th>
                                                            <th>Holiday <input type="checkbox" class="checkAll" data-status="4"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($studentList as $row) { ?>
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="student_id[]" value="<?= $row->sid ?>">
                                                                    <?= htmlspecialchars($row->StudentName) ?>
                                                                </td>
                                                                <td><input class="status_check" type="checkbox" name="status[<?= $row->sid ?>]" value="1" <?= (isset($existing_attendance[$row->sid]) && $existing_attendance[$row->sid] == 1) ? 'checked' : '' ?>></td>
                                                                <td><input class="status_check" type="checkbox" name="status[<?= $row->sid ?>]" value="2" <?= (isset($existing_attendance[$row->sid]) && $existing_attendance[$row->sid] == 2) ? 'checked' : '' ?>></td>
                                                                <td><input class="status_check" type="checkbox" name="status[<?= $row->sid ?>]" value="3" <?= (isset($existing_attendance[$row->sid]) && $existing_attendance[$row->sid] == 3) ? 'checked' : '' ?>></td>
                                                                <td><input class="status_check" type="checkbox" name="status[<?= $row->sid ?>]" value="4" <?= (isset($existing_attendance[$row->sid]) && $existing_attendance[$row->sid] == 4) ? 'checked' : '' ?>></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <button class="btn btn-success mt-3 btn-block w-100" type="submit">Save Attendance</button>
                                        </form>
                                    <?php } else { ?>
                                        <div class="alert alert-warning text-center mt-3">No students found for the selected class and date.</div>
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

    <script>
        $(document).ready(function() {
            $('.status_check').on('change', function() {
                let row = $(this).closest('tr');
                row.find('.status_check').prop('checked', false);
                $(this).prop('checked', true);
                updateCheckAll();
            });

            $('.checkAll').on('change', function() {
                let statusValue = $(this).data('status');
                let isChecked = $(this).prop('checked');

                $('.checkAll').not(this).prop('checked', false);
                $('.status_check').prop('checked', false);
                if (isChecked) {
                    $('.status_check[value="' + statusValue + '"]').each(function() {
                        let row = $(this).closest('tr');
                        row.find('.status_check').prop('checked', false);
                        $(this).prop('checked', true);
                    });
                }
                updateCheckAll();
            });

            function updateCheckAll() {
                $('.checkAll').each(function() {
                    let statusValue = $(this).data('status');
                    let allChecked = $('.status_check[value="' + statusValue + '"]').length === $('.status_check[value="' + statusValue + '"]:checked').length;
                    $(this).prop('checked', allChecked);
                });
            }
        });
    </script>

    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="vendors/select2/select2.min.js"></script>
    <script src="vendors/typeahead.js/typeahead.bundle.min.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
    <script src="js/typeahead.js"></script>
    <script src="js/select2.js"></script>
</body>

</html>
