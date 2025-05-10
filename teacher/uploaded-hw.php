<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sturecmsaid']) == 0) {
    header('location:logout.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edutrack Pro || Uploaded Homework</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="./vendors/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="./vendors/chartist/chartist.min.css">
    <link rel="stylesheet" href="./css/style.css">

    <style>
        @media (max-width: 768px) {
            .table-responsive {
                display: none;
            }

            .homework-card {
                border: 1px solid #ddd;
                padding: 15px;
                margin-bottom: 15px;
                border-radius: 8px;
                background-color: #f9f9f9;
            }

            .homework-card p {
                margin: 3px 0;
                font-size: 14px;
            }

            .homework-card .btn {
                margin-top: 10px;
            }
        }

        @media (min-width: 769px) {
            .homework-cards-mobile {
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
                        <h3 class="page-title">Uploaded Homework</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Uploaded Homework</li>
                            </ol>
                        </nav>
                    </div>

                    <?php
                    $hwid = intval($_GET['hwid']);
                    $sql = "SELECT homeworkTitle, lastDateofSubmission, classId FROM tblhomework WHERE id = :hwid";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':hwid', $hwid, PDO::PARAM_STR);
                    $query->execute();
                    $result = $query->fetch(PDO::FETCH_OBJ);
                    $classid = $result->classId;
                    ?>
                    <div class="mb-4">
                        <p><strong>Title:</strong> <?= htmlentities($result->homeworkTitle); ?><br>
                            <strong>Last Date of Submission:</strong> <?= htmlentities($result->lastDateofSubmission); ?>
                        </p>
                    </div>

                    <!-- Desktop Table -->
                    <div class="table-responsive border rounded p-1">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Student ID</th>
                                    <th>Student Class</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Admission Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $page_no = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
                                $records_per_page = 10;
                                $offset = ($page_no - 1) * $records_per_page;

                                $count_sql = "SELECT COUNT(*) FROM tblstudent WHERE StudentClass = :classid";
                                $stmt = $dbh->prepare($count_sql);
                                $stmt->bindParam(':classid', $classid, PDO::PARAM_STR);
                                $stmt->execute();
                                $total_rows = $stmt->fetchColumn();
                                $total_pages = ceil($total_rows / $records_per_page);

                                $sql = "SELECT tblstudent.StuID, tblstudent.ID as sid, tblstudent.StudentName, tblstudent.StudentEmail, tblstudent.DateofAdmission, tblclass.ClassName, tblclass.Section 
                                        FROM tblstudent 
                                        JOIN tblclass ON tblclass.ID = tblstudent.StudentClass 
                                        WHERE tblstudent.StudentClass = :classid 
                                        LIMIT $offset, $records_per_page";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':classid', $classid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $row) {
                                ?>
                                        <tr>
                                            <td><?= htmlentities($cnt); ?></td>
                                            <td><?= htmlentities($row->StuID); ?></td>
                                            <td><?= htmlentities($row->ClassName . ' ' . $row->Section); ?></td>
                                            <td><?= htmlentities($row->StudentName); ?></td>
                                            <td><?= htmlentities($row->StudentEmail); ?></td>
                                            <td><?= htmlentities($row->DateofAdmission); ?></td>
                                            <td><a href="view-hw.php?stid=<?= htmlentities($row->sid); ?>&hwid=<?= htmlentities($hwid); ?>" class="btn btn-info btn-sm" target="_blank">View</a></td>
                                        </tr>
                                <?php
                                        $cnt++;
                                    }
                                } else {
                                    echo '<tr><td colspan="7">No students found.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="homework-cards-mobile">
                        <?php $cnt = 1;
                        foreach ($results as $row) { ?>
                            <div class="homework-card">
                                <p><strong><?= $cnt . '. ' . htmlentities($row->StudentName); ?></strong></p>
                                <p><strong>Student ID:</strong> <?= htmlentities($row->StuID); ?></p>
                                <p><strong>Class:</strong> <?= htmlentities($row->ClassName); ?> <?= htmlentities($row->Section); ?></p>
                                <p><strong>Email:</strong> <?= htmlentities($row->StudentEmail); ?></p>
                                <p><strong>Admission Date:</strong> <?= htmlentities($row->DateofAdmission); ?></p>
                                <a href="view-hw.php?stid=<?= htmlentities($row->sid); ?>&hwid=<?= htmlentities($hwid); ?>" class="btn btn-info btn-sm" target="_blank">View</a>
                            </div>
                        <?php $cnt++;
                        } ?>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        <ul class="pagination">
                            <li><a href="?hwid=<?= $hwid ?>&pageno=1">First</a></li>
                            <li class="<?= ($page_no <= 1) ? 'disabled' : ''; ?>">
                                <a href="<?= ($page_no <= 1) ? '#' : "?hwid=$hwid&pageno=" . ($page_no - 1); ?>">Prev</a>
                            </li>
                            <li class="<?= ($page_no >= $total_pages) ? 'disabled' : ''; ?>">
                                <a href="<?= ($page_no >= $total_pages) ? '#' : "?hwid=$hwid&pageno=" . ($page_no + 1); ?>">Next</a>
                            </li>
                            <li><a href="?hwid=<?= $hwid ?>&pageno=<?= $total_pages; ?>">Last</a></li>
                        </ul>
                    </div>

                </div>
                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
    </div>

    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="./js/off-canvas.js"></script>
    <script src="./js/misc.js"></script>
</body>

</html>
